<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Alert</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .wrapper { max-width: 640px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #1a3c5e; padding: 24px 32px; color: #ffffff; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; font-size: 14px; color: #a8c4de; }
        .badge { display: inline-block; background: #e8f4fd; color: #1a3c5e; border-radius: 4px; padding: 4px 10px; font-size: 13px; font-weight: bold; margin-top: 8px; }
        .section { padding: 24px 32px; border-bottom: 1px solid #e8e8e8; }
        .section:last-child { border-bottom: none; }
        .section h2 { margin: 0 0 16px; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; color: #1a3c5e; }
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { padding: 7px 0; font-size: 14px; color: #333; vertical-align: top; }
        table.info td:first-child { color: #777; width: 42%; }
        table.items { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.items thead tr { background: #1a3c5e; color: #fff; }
        table.items thead td { padding: 9px 10px; }
        table.items tbody tr:nth-child(even) { background: #f9f9f9; }
        table.items tbody td { padding: 8px 10px; color: #333; border-bottom: 1px solid #eee; }
        .total-row { background: #1a3c5e !important; color: #ffffff !important; font-weight: bold; }
        .total-row td { padding: 10px 10px !important; color: #ffffff !important; }
        .footer { padding: 16px 32px; background: #f8f8f8; font-size: 12px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>&#128276; New Booking Received</h1>
        <p>A new booking has been placed on the platform.</p>
        <div class="badge">{{ $booking->booking_reference }}</div>
    </div>

    {{-- Booking Summary --}}
    <div class="section">
        <h2>Booking Details</h2>
        <table class="info">
            <tr>
                <td>Reference</td>
                <td><strong>{{ $booking->booking_reference }}</strong></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>{{ ucfirst($booking->status) }}</td>
            </tr>
            <tr>
                <td>Booking Date</td>
                <td>{{ $booking->booking_date->format('d M Y') }}{{ $booking->booking_time ? ' at ' . $booking->booking_time : '' }}</td>
            </tr>
            <tr>
                <td>Participants</td>
                <td>{{ $booking->number_of_participants }}</td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td><strong>{{ $booking->currency }} {{ number_format((float) $booking->total_amount, 2) }}</strong></td>
            </tr>
            @if ($booking->special_requests)
            <tr>
                <td>Special Requests</td>
                <td>{{ $booking->special_requests }}</td>
            </tr>
            @endif
            <tr>
                <td>Placed At</td>
                <td>{{ $booking->created_at->format('d M Y H:i') }} UTC</td>
            </tr>
        </table>
    </div>

    {{-- Customer Details --}}
    <div class="section">
        <h2>Customer Details</h2>
        <table class="info">
            <tr>
                <td>Name</td>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></td>
            </tr>
            @if ($customer->phone)
            <tr>
                <td>Phone</td>
                <td>{{ $customer->phone }}</td>
            </tr>
            @endif
            @if ($customer->nationality)
            <tr>
                <td>Nationality</td>
                <td>{{ $customer->nationality }}</td>
            </tr>
            @endif
            @if ($customer->date_of_birth)
            <tr>
                <td>Date of Birth</td>
                <td>{{ \Carbon\Carbon::parse($customer->date_of_birth)->format('d M Y') }}</td>
            </tr>
            @endif
            @if ($customer->diving_experience_level)
            <tr>
                <td>Dive Experience</td>
                <td>{{ ucfirst($customer->diving_experience_level) }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- Diving Centre Details --}}
    @if ($centre)
    <div class="section">
        <h2>Diving Centre</h2>
        <table class="info">
            <tr>
                <td>Centre Name</td>
                <td><strong>{{ $centre->name }}</strong></td>
            </tr>
            @if ($centre->address)
            <tr>
                <td>Address</td>
                <td>{{ $centre->address }}</td>
            </tr>
            @endif
            @if ($centre->city || $centre->country)
            <tr>
                <td>Location</td>
                <td>{{ implode(', ', array_filter([$centre->city, $centre->country])) }}</td>
            </tr>
            @endif
            @if ($centre->phone)
            <tr>
                <td>Phone</td>
                <td>{{ $centre->phone }}</td>
            </tr>
            @endif
            @if ($centre->email)
            <tr>
                <td>Email</td>
                <td><a href="mailto:{{ $centre->email }}">{{ $centre->email }}</a></td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- Tenant / Operator Details --}}
    @if ($tenant)
    <div class="section">
        <h2>Operator / Tenant</h2>
        <table class="info">
            <tr>
                <td>Operator Name</td>
                <td><strong>{{ $tenant->name }}</strong></td>
            </tr>
            @if ($tenant->email)
            <tr>
                <td>Email</td>
                <td><a href="mailto:{{ $tenant->email }}">{{ $tenant->email }}</a></td>
            </tr>
            @endif
            @if ($tenant->phone)
            <tr>
                <td>Phone</td>
                <td>{{ $tenant->phone }}</td>
            </tr>
            @endif
            @if ($tenant->website)
            <tr>
                <td>Website</td>
                <td><a href="{{ $tenant->website }}">{{ $tenant->website }}</a></td>
            </tr>
            @endif
            @if ($tenant->currency)
            <tr>
                <td>Currency</td>
                <td>{{ $tenant->currency }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- Booked Items --}}
    <div class="section">
        <h2>Booked Items</h2>
        <table class="items">
            <thead>
                <tr>
                    <td>Item</td>
                    <td style="text-align:center">Qty</td>
                    <td style="text-align:center">Participants</td>
                    <td style="text-align:right">Unit Price</td>
                    <td style="text-align:right">Total</td>
                    <td>Scheduled</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{ $item['bookable_name'] }}</td>
                    <td style="text-align:center">{{ $item['quantity'] }}</td>
                    <td style="text-align:center">{{ $item['participant_count'] }}</td>
                    <td style="text-align:right">{{ $booking->currency }} {{ number_format((float) $item['unit_price'], 2) }}</td>
                    <td style="text-align:right">{{ $booking->currency }} {{ number_format((float) $item['total_price'], 2) }}</td>
                    <td>
                        @if ($item['scheduled_date'])
                            {{ \Carbon\Carbon::parse($item['scheduled_date'])->format('d M Y') }}
                            @if ($item['scheduled_time'])
                                {{ $item['scheduled_time'] }}
                            @endif
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @if (!empty($item['notes']))
                <tr>
                    <td colspan="6" style="color:#777;font-size:12px;padding:2px 10px 8px;">Note: {{ $item['notes'] }}</td>
                </tr>
                @endif
                @endforeach
                <tr class="total-row">
                    <td colspan="4"><strong>Total</strong></td>
                    <td style="text-align:right"><strong>{{ $booking->currency }} {{ number_format((float) $booking->total_amount, 2) }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        This is an automated super admin alert from the Dive Booking platform.
    </div>
</div>
</body>
</html>
