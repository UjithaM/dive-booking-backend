<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCartItemRequest;
use App\Http\Requests\CheckoutCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private const BOOKABLE_MAP = [
        'activity' => Activity::class,
        'course' => Course::class,
        'service' => Service::class,
        'room' => Room::class,
    ];

    public function getCart(Request $request): JsonResponse
    {
        $cart = $this->resolveCartFromToken($request);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found.'], 404);
        }

        return response()->json($this->cartResponse($cart));
    }

    public function addItem(AddCartItemRequest $request): JsonResponse
    {
        $tenantId = $request->tenant_id;
        $token = $request->header('X-Cart-Token');
        $cart = null;

        if ($token) {
            $cart = Cart::findActiveByToken($token, $tenantId);
        }

        if (!$cart) {
            $cart = Cart::createForTenant($tenantId);
        }

        $bookableClass = self::BOOKABLE_MAP[$request->bookable_type];
        $bookable = $bookableClass::find($request->bookable_id);

        if (!$bookable) {
            return response()->json(['message' => ucfirst($request->bookable_type) . ' not found.'], 404);
        }

        if (isset($bookable->tenant_id) && $bookable->tenant_id !== $tenantId) {
            return response()->json(['message' => 'Item does not belong to your tenant.'], 403);
        }

        $unitPrice = (float) ($bookable->sale_price ?? $bookable->base_price ?? 0);
        $quantity = (int) ($request->quantity ?? 1);
        $participantCount = (int) ($request->participant_count ?? 1);
        $isPerPerson = (bool) ($bookable->is_per_person ?? false);

        $totalPrice = CartItem::calculateTotalPrice($unitPrice, $quantity, $participantCount, $isPerPerson);

        $cart->items()->create([
            'bookable_type' => $bookableClass,
            'bookable_id' => $bookable->id,
            'quantity' => $quantity,
            'participant_count' => $participantCount,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'notes' => $request->notes,
        ]);

        $cart->recalculateTotal();
        $cart->refresh();

        return response()->json([
            'cart_token' => $cart->token,
            'cart' => $this->cartResponse($cart),
        ], 201);
    }

    public function updateItem(UpdateCartItemRequest $request, CartItem $cartItem): JsonResponse
    {
        $cart = $this->resolveCartFromToken($request);

        if (!$cart || $cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Item not found in your cart.'], 404);
        }

        $quantity = (int) ($request->quantity ?? $cartItem->quantity);
        $participantCount = (int) ($request->participant_count ?? $cartItem->participant_count);
        $unitPrice = (float) $cartItem->unit_price;
        $bookable = $cartItem->bookable;
        $isPerPerson = (bool) ($bookable?->is_per_person ?? false);

        $totalPrice = CartItem::calculateTotalPrice($unitPrice, $quantity, $participantCount, $isPerPerson);

        $cartItem->update([
            'quantity' => $quantity,
            'participant_count' => $participantCount,
            'total_price' => $totalPrice,
            'scheduled_date' => $request->filled('scheduled_date') ? $request->scheduled_date : $cartItem->scheduled_date,
            'scheduled_time' => $request->filled('scheduled_time') ? $request->scheduled_time : $cartItem->scheduled_time,
            'notes' => $request->filled('notes') ? $request->notes : $cartItem->notes,
        ]);

        $cart->recalculateTotal();
        $cart->refresh();

        return response()->json($this->cartResponse($cart));
    }

    public function removeItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $cart = $this->resolveCartFromToken($request);

        if (!$cart || $cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Item not found in your cart.'], 404);
        }

        $cartItem->delete();
        $cart->recalculateTotal();
        $cart->refresh();

        return response()->json($this->cartResponse($cart));
    }

    public function checkout(CheckoutCartRequest $request): JsonResponse
    {
        $cart = $this->resolveCartFromToken($request);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found.'], 404);
        }

        $cart->loadMissing('items');

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        DB::beginTransaction();

        try {
            [$firstName, $lastName] = $this->splitName($request->customer_name);

            $customer = Customer::firstOrCreate(
                ['tenant_id' => $cart->tenant_id, 'email' => $request->customer_email],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $request->customer_phone,
                ]
            );

            $booking = Booking::create([
                'tenant_id' => $cart->tenant_id,
                'booking_reference' => $this->generateReference(),
                'customer_id' => $customer->id,
                'centre_id' => $request->centre_id,
                'status' => 'pending',
                'booking_date' => $request->booking_date,
                'total_amount' => $cart->total_amount,
                'discount_amount' => 0,
                'final_amount' => $cart->total_amount,
                'currency' => $cart->currency,
                'number_of_participants' => $cart->items->sum('participant_count'),
                'special_requests' => $request->special_requests,
            ]);

            foreach ($cart->items as $cartItem) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'bookable_type' => $cartItem->bookable_type,
                    'bookable_id' => $cartItem->bookable_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'scheduled_date' => $cartItem->scheduled_date,
                    'scheduled_time' => $cartItem->scheduled_time,
                    'status' => 'pending',
                    'notes' => $cartItem->notes,
                ]);
            }

            $cart->update([
                'status' => 'checked_out',
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'special_requests' => $request->special_requests,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Checkout successful.',
                'booking_reference' => $booking->booking_reference,
                'booking_id' => $booking->id,
                'total_amount' => $booking->total_amount,
                'currency' => $booking->currency,
                'customer' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function resolveCartFromToken(Request $request): ?Cart
    {
        $token = $request->header('X-Cart-Token');
        $tenantId = $request->tenant_id;

        if (!$token || !$tenantId) {
            return null;
        }

        return Cart::findActiveByToken($token, $tenantId);
    }

    private function cartResponse(Cart $cart): array
    {
        $cart->loadMissing('items');

        $items = $cart->items->map(function (CartItem $item) {
            $bookable = $item->bookable;
            return [
                'id' => $item->id,
                'bookable_type' => class_basename($item->bookable_type),
                'bookable_id' => $item->bookable_id,
                'bookable_name' => $bookable?->name,
                'quantity' => $item->quantity,
                'participant_count' => $item->participant_count,
                'unit_price' => (float) $item->unit_price,
                'total_price' => (float) $item->total_price,
                'scheduled_date' => $item->scheduled_date ? $item->scheduled_date->toDateString() : null,
                'scheduled_time' => $item->scheduled_time,
                'notes' => $item->notes,
            ];
        });

        return [
            'cart_token' => $cart->token,
            'status' => $cart->status,
            'currency' => $cart->currency,
            'total_amount' => (float) $cart->total_amount,
            'item_count' => $items->count(),
            'items' => $items,
        ];
    }

    private function splitName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);
        return [$parts[0], $parts[1] ?? ''];
    }

    private function generateReference(): string
    {
        do {
            $ref = 'BOOK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Booking::where('booking_reference', $ref)->exists());

        return $ref;
    }
}
