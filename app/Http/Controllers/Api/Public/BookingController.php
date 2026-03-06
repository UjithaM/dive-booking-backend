<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\CreateBookingRequest;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mailgun\Mailgun;

class BookingController extends Controller
{
    private const BOOKABLE_MAP = [
        'activity' => Activity::class,
        'course'   => Course::class,
        'service'  => Service::class,
    ];

    public function store(CreateBookingRequest $request): JsonResponse
    {
        $tenantId    = $request->tenant_id;
        $data        = $request->validated();
        $customerData = $data['customer'];

        DB::beginTransaction();

        try {
            $customer = Customer::updateOrCreate(
                ['tenant_id' => $tenantId, 'email' => $customerData['email']],
                [
                    'first_name'              => $customerData['first_name'],
                    'last_name'               => $customerData['last_name'],
                    'phone'                   => $customerData['phone'] ?? null,
                    'date_of_birth'           => $customerData['date_of_birth'] ?? null,
                    'nationality'             => $customerData['nationality'] ?? null,
                    'diving_experience_level' => $customerData['diving_experience_level'] ?? null,
                ]
            );

            $totalAmount    = 0;
            $resolvedItems  = [];

            foreach ($data['items'] as $item) {
                $bookableClass = self::BOOKABLE_MAP[$item['bookable_type']];
                $bookable      = $bookableClass::find($item['bookable_id']);

                if (!$bookable) {
                    DB::rollBack();
                    return response()->json([
                        'message' => ucfirst($item['bookable_type']) . ' not found: ' . $item['bookable_id'],
                    ], 422);
                }

                if (isset($bookable->tenant_id) && $bookable->tenant_id !== $tenantId) {
                    DB::rollBack();
                    return response()->json(['message' => 'Item does not belong to this tenant.'], 403);
                }

                $unitPrice      = (float) ($bookable->sale_price ?? $bookable->base_price ?? 0);
                $quantity       = (int) $item['quantity'];
                $participantCount = (int) ($item['participant_count'] ?? 1);
                $isPerPerson    = (bool) ($bookable->is_per_person ?? false);
                $totalPrice     = CartItem::calculateTotalPrice($unitPrice, $quantity, $participantCount, $isPerPerson);

                $totalAmount += $totalPrice;

                $resolvedItems[] = [
                    'bookable_type'    => $bookableClass,
                    'bookable_id'      => $bookable->id,
                    'bookable_name'    => $bookable->name,
                    'quantity'         => $quantity,
                    'participant_count' => $participantCount,
                    'unit_price'       => $unitPrice,
                    'total_price'      => $totalPrice,
                    'scheduled_date'   => $item['scheduled_date'] ?? null,
                    'scheduled_time'   => $item['scheduled_time'] ?? null,
                    'status'           => 'pending',
                    'notes'            => $item['notes'] ?? null,
                ];
            }

            $booking = Booking::create([
                'tenant_id'            => $tenantId,
                'booking_reference'    => $this->generateReference(),
                'customer_id'          => $customer->id,
                'centre_id'            => $data['centre_id'],
                'status'               => 'pending',
                'booking_date'         => $data['booking_date'],
                'booking_time'         => $data['booking_time'] ?? null,
                'total_amount'         => $totalAmount,
                'discount_amount'      => 0,
                'final_amount'         => $totalAmount,
                'currency'             => $data['currency'] ?? 'USD',
                'number_of_participants' => array_sum(array_column($resolvedItems, 'participant_count')),
                'special_requests'     => $data['special_requests'] ?? null,
            ]);

            foreach ($resolvedItems as $itemData) {
                BookingItem::create([
                    'booking_id'    => $booking->id,
                    'bookable_type' => $itemData['bookable_type'],
                    'bookable_id'   => $itemData['bookable_id'],
                    'quantity'      => $itemData['quantity'],
                    'unit_price'    => $itemData['unit_price'],
                    'total_price'   => $itemData['total_price'],
                    'scheduled_date' => $itemData['scheduled_date'],
                    'scheduled_time' => $itemData['scheduled_time'],
                    'status'        => 'pending',
                    'notes'         => $itemData['notes'],
                ]);
            }

            DB::commit();

            $this->sendConfirmationEmail($customer, $booking, $resolvedItems);

            return response()->json([
                'message'           => 'Booking created successfully.',
                'booking_reference' => $booking->booking_reference,
                'booking_id'        => $booking->id,
                'booking_date'      => $booking->booking_date->format('Y-m-d'),
                'booking_time'      => $booking->booking_time,
                'total_amount'      => $booking->total_amount,
                'currency'          => $booking->currency,
                'customer'          => [
                    'first_name' => $customer->first_name,
                    'last_name'  => $customer->last_name,
                    'email'      => $customer->email,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function sendConfirmationEmail(Customer $customer, Booking $booking, array $items): void
    {
        $apiKey   = config('services.mailgun.api_key');
        $domain   = config('services.mailgun.domain');
        $from     = config('services.mailgun.from');
        $fromName = config('services.mailgun.from_name');

        if (!$apiKey || !$domain) {
            return;
        }

        $html = view('emails.booking-confirmation', compact('customer', 'booking', 'items'))->render();

        $itemLines = array_map(function (array $item) use ($booking) {
            $schedule = '';
            if ($item['scheduled_date']) {
                $schedule = ' on ' . $item['scheduled_date'];
                if ($item['scheduled_time']) {
                    $schedule .= ' at ' . $item['scheduled_time'];
                }
            }
            return sprintf(
                '  - %s x%d @ %s %.2f each = %s %.2f%s',
                $item['bookable_name'],
                $item['quantity'],
                $booking->currency,
                $item['unit_price'],
                $booking->currency,
                $item['total_price'],
                $schedule
            );
        }, $items);

        $bookingDateTime = $booking->booking_date->format('Y-m-d') . ($booking->booking_time ? ' at ' . $booking->booking_time : '');

        $text = implode("\n", array_filter([
            "Hi {$customer->first_name} {$customer->last_name},",
            '',
            'Your booking has been confirmed!',
            '',
            "Booking Reference: {$booking->booking_reference}",
            "Booking Date/Time: {$bookingDateTime}",
            "Currency:          {$booking->currency}",
            '',
            'Items:',
            implode("\n", $itemLines),
            '',
            "Total: {$booking->currency} " . number_format((float) $booking->total_amount, 2),
            $booking->special_requests ? "Special Requests: {$booking->special_requests}" : null,
            '',
            'Thank you for booking with us!',
        ], fn ($line) => $line !== null));

        Mailgun::create($apiKey)->messages()->send($domain, [
            'from'    => "{$fromName} <{$from}>",
            'to'      => "{$customer->first_name} {$customer->last_name} <{$customer->email}>",
            'subject' => "Booking Confirmation – {$booking->booking_reference}",
            'html'    => $html,
            'text'    => $text,
        ]);
    }

    private function generateReference(): string
    {
        do {
            $ref = 'BOOK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Booking::where('booking_reference', $ref)->exists());

        return $ref;
    }
}
