<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Booking Confirmation – {{ $booking->booking_reference }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;color:#2d3748;">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f4f8;padding:32px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

          {{-- ===== HEADER ===== --}}
          <tr>
            <td style="background:linear-gradient(135deg,#0c2340 0%,#1a4a7a 60%,#0e6ba8 100%);border-radius:12px 12px 0 0;padding:36px 40px;text-align:center;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" style="padding-bottom:16px;">
                    {{-- Scuba diver SVG icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 64 64" fill="none">
                      <circle cx="32" cy="32" r="32" fill="rgba(255,255,255,0.1)"/>
                      <ellipse cx="32" cy="26" rx="8" ry="10" fill="#7ec8e3"/>
                      <rect x="28" y="34" width="8" height="14" rx="4" fill="#7ec8e3"/>
                      <rect x="20" y="30" width="6" height="3" rx="1.5" fill="#fff" opacity=".6"/>
                      <rect x="38" y="30" width="6" height="3" rx="1.5" fill="#fff" opacity=".6"/>
                      <ellipse cx="32" cy="25" rx="5" ry="6" fill="#0e6ba8"/>
                      <circle cx="32" cy="22" r="3" fill="#7ec8e3" opacity=".7"/>
                      <path d="M24 44 Q28 50 32 44 Q36 50 40 44" stroke="#7ec8e3" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                  </td>
                </tr>
                <tr>
                  <td align="center">
                    <span style="font-size:22px;font-weight:700;color:#ffffff;letter-spacing:1.5px;text-transform:uppercase;">
                      {{ config('services.mailgun.from_name', 'Dive Booking') }}
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- ===== HERO: BOOKING CONFIRMED ===== --}}
          <tr>
            <td style="background:#0e6ba8;padding:28px 40px;text-align:center;">
              <p style="margin:0 0 8px;font-size:13px;font-weight:600;color:#7ec8e3;letter-spacing:2px;text-transform:uppercase;">Booking Confirmed</p>
              <p style="margin:0 0 4px;font-size:32px;font-weight:800;color:#ffffff;letter-spacing:3px;">{{ $booking->booking_reference }}</p>
              <p style="margin:0;font-size:14px;color:rgba(255,255,255,0.7);">{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F j, Y') }}</p>
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:16px auto 0;">
                <tr>
                  <td style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:20px;padding:6px 18px;">
                    <span style="font-size:12px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:1px;">✓ Confirmed</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- ===== WHITE BODY ===== --}}
          <tr>
            <td style="background:#ffffff;padding:40px 40px 0;">

              {{-- Greeting --}}
              <p style="margin:0 0 8px;font-size:20px;font-weight:700;color:#1a2e44;">
                Hi {{ $customer->first_name }} {{ $customer->last_name }},
              </p>
              <p style="margin:0 0 28px;font-size:15px;color:#718096;line-height:1.6;">
                Great news — your booking has been confirmed! We're excited to have you join us.
                Below you'll find a summary of everything you've booked.
              </p>

              {{-- Divider --}}
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr><td style="border-top:2px solid #e8f4fd;"></td></tr>
              </table>

              {{-- Booking Details --}}
              <p style="margin:0 0 16px;font-size:12px;font-weight:700;color:#7ec8e3;letter-spacing:2px;text-transform:uppercase;">Booking Details</p>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                  <td width="50%" style="padding:0 0 10px;">
                    <span style="font-size:12px;color:#a0aec0;display:block;margin-bottom:2px;">Reference</span>
                    <span style="font-size:14px;font-weight:700;color:#1a2e44;">{{ $booking->booking_reference }}</span>
                  </td>
                  <td width="50%" style="padding:0 0 10px;">
                    <span style="font-size:12px;color:#a0aec0;display:block;margin-bottom:2px;">Booking Date</span>
                    <span style="font-size:14px;font-weight:600;color:#2d3748;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y') }}</span>
                  </td>
                </tr>
                <tr>
                  <td width="50%" style="padding:0 0 10px;">
                    <span style="font-size:12px;color:#a0aec0;display:block;margin-bottom:2px;">Participants</span>
                    <span style="font-size:14px;font-weight:600;color:#2d3748;">{{ $booking->number_of_participants }}</span>
                  </td>
                  <td width="50%" style="padding:0 0 10px;">
                    <span style="font-size:12px;color:#a0aec0;display:block;margin-bottom:2px;">Status</span>
                    <span style="font-size:14px;font-weight:600;color:#38a169;">Confirmed</span>
                  </td>
                </tr>
              </table>

              {{-- Divider --}}
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr><td style="border-top:2px solid #e8f4fd;"></td></tr>
              </table>

              {{-- Items Table --}}
              <p style="margin:0 0 16px;font-size:12px;font-weight:700;color:#7ec8e3;letter-spacing:2px;text-transform:uppercase;">What You've Booked</p>

              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:4px;">
                {{-- Table Header --}}
                <tr style="background:#f7fafc;">
                  <td style="padding:10px 12px;font-size:11px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #e2e8f0;">Item</td>
                  <td style="padding:10px 12px;font-size:11px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #e2e8f0;text-align:center;">Qty</td>
                  <td style="padding:10px 12px;font-size:11px;font-weight:700;color:#a0aec0;text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid #e2e8f0;text-align:right;">Price</td>
                </tr>

                {{-- Items --}}
                @foreach ($items as $item)
                <tr style="border-bottom:1px solid #f0f4f8;">
                  <td style="padding:14px 12px;vertical-align:top;">
                    <span style="font-size:14px;font-weight:600;color:#1a2e44;display:block;">{{ $item['bookable_name'] }}</span>
                    @if (!empty($item['scheduled_date']))
                      <span style="font-size:12px;color:#718096;display:block;margin-top:3px;">
                        📅 {{ \Carbon\Carbon::parse($item['scheduled_date'])->format('M j, Y') }}
                        @if (!empty($item['scheduled_time']))
                          &nbsp;at&nbsp;{{ \Carbon\Carbon::parse($item['scheduled_time'])->format('g:i A') }}
                        @endif
                      </span>
                    @endif
                    @if (!empty($item['notes']))
                      <span style="font-size:12px;color:#a0aec0;display:block;margin-top:3px;font-style:italic;">{{ $item['notes'] }}</span>
                    @endif
                    <span style="font-size:12px;color:#a0aec0;display:block;margin-top:3px;">
                      {{ $booking->currency }} {{ number_format($item['unit_price'], 2) }} per unit
                    </span>
                  </td>
                  <td style="padding:14px 12px;text-align:center;vertical-align:top;">
                    <span style="font-size:14px;color:#2d3748;">{{ $item['quantity'] }}</span>
                  </td>
                  <td style="padding:14px 12px;text-align:right;vertical-align:top;">
                    <span style="font-size:14px;font-weight:600;color:#1a2e44;">{{ $booking->currency }} {{ number_format($item['total_price'], 2) }}</span>
                  </td>
                </tr>
                @endforeach

                {{-- Total Row --}}
                <tr style="background:linear-gradient(135deg,#e8f4fd,#f0f9ff);">
                  <td colspan="2" style="padding:16px 12px;font-size:15px;font-weight:700;color:#0e6ba8;">Total Amount</td>
                  <td style="padding:16px 12px;text-align:right;font-size:18px;font-weight:800;color:#0e6ba8;">
                    {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
                  </td>
                </tr>
              </table>

              {{-- Special Requests --}}
              @if ($booking->special_requests)
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:28px;margin-bottom:4px;">
                <tr><td style="border-top:2px solid #e8f4fd;"></td></tr>
              </table>
              <p style="margin:20px 0 12px;font-size:12px;font-weight:700;color:#7ec8e3;letter-spacing:2px;text-transform:uppercase;">Special Requests</p>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="background:#fffbeb;border-left:4px solid #f6ad55;border-radius:0 6px 6px 0;padding:14px 16px;">
                    <p style="margin:0;font-size:14px;color:#744210;line-height:1.6;">{{ $booking->special_requests }}</p>
                  </td>
                </tr>
              </table>
              @endif

            </td>
          </tr>

          {{-- ===== WHAT'S NEXT SECTION ===== --}}
          <tr>
            <td style="background:#ffffff;padding:28px 40px 0;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:4px;">
                <tr><td style="border-top:2px solid #e8f4fd;"></td></tr>
              </table>
              <p style="margin:20px 0 16px;font-size:12px;font-weight:700;color:#7ec8e3;letter-spacing:2px;text-transform:uppercase;">What's Next</p>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="32" style="vertical-align:top;padding-top:2px;">
                    <span style="font-size:18px;">🎽</span>
                  </td>
                  <td style="padding-left:12px;padding-bottom:12px;">
                    <span style="font-size:14px;font-weight:600;color:#1a2e44;display:block;">Prepare your gear</span>
                    <span style="font-size:13px;color:#718096;line-height:1.5;">Bring comfortable clothing and any personal dive equipment you own. We'll provide the rest.</span>
                  </td>
                </tr>
                <tr>
                  <td width="32" style="vertical-align:top;padding-top:2px;">
                    <span style="font-size:18px;">📋</span>
                  </td>
                  <td style="padding-left:12px;padding-bottom:12px;">
                    <span style="font-size:14px;font-weight:600;color:#1a2e44;display:block;">Check-in early</span>
                    <span style="font-size:13px;color:#718096;line-height:1.5;">Please arrive 15 minutes before your scheduled time to complete any remaining paperwork.</span>
                  </td>
                </tr>
                <tr>
                  <td width="32" style="vertical-align:top;padding-top:2px;">
                    <span style="font-size:18px;">📞</span>
                  </td>
                  <td style="padding-left:12px;">
                    <span style="font-size:14px;font-weight:600;color:#1a2e44;display:block;">Questions?</span>
                    <span style="font-size:13px;color:#718096;line-height:1.5;">Reply to this email or contact us directly — we're happy to help with anything you need.</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- ===== FOOTER ===== --}}
          <tr>
            <td style="background:#ffffff;padding:32px 40px 0;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr><td style="border-top:2px solid #e8f4fd;"></td></tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="background:#0c2340;border-radius:0 0 12px 12px;padding:32px 40px;text-align:center;">
              <p style="margin:0 0 8px;font-size:18px;font-weight:700;color:#ffffff;">
                🌊 See you in the water!
              </p>
              <p style="margin:0 0 20px;font-size:13px;color:rgba(255,255,255,0.6);line-height:1.6;">
                Thank you for choosing {{ config('services.mailgun.from_name', 'Dive Booking') }}.<br/>
                We can't wait to share an incredible underwater experience with you.
              </p>
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 20px;">
                <tr>
                  <td style="background:#0e6ba8;border-radius:6px;padding:12px 28px;">
                    <span style="font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">
                      Reference: {{ $booking->booking_reference }}
                    </span>
                  </td>
                </tr>
              </table>
              <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.35);line-height:1.6;">
                This is an automated confirmation email.<br/>
                {{ config('services.mailgun.from_name', 'Dive Booking') }} &bull;
                <a href="mailto:{{ config('services.mailgun.from', 'noreply@diveking.co') }}" style="color:rgba(255,255,255,0.5);text-decoration:none;">
                  {{ config('services.mailgun.from', 'noreply@diveking.co') }}
                </a>
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
