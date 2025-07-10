<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Reservation Request</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0"
        style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px;">
        <tr>
            <td style="padding: 20px; border-bottom: 1px solid #eeeeee;">
                <h2 style="margin: 0; color: #333;">🛎️ Thank You for Your Reservation Request</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <p>Hi {{ $data['name_client'] }},</p>
                <p>Thank you for your reservation request at <strong>{{ $room->name }}</strong>.</p>
                <p>We have received your request for dates:</p>
                <p><strong>{{ $data['date_checkin'] }} to {{ $data['date_checkout'] }}</strong></p>
                <p>Our team will review your request and contact you shortly to confirm availability and details.</p>
                <p>If you have questions, feel free to reply to this email.</p>
                <p>Best regards,<br>
                    {{ config('app.name') }} Team</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 15px; text-align: center; background-color: #f2f2f2; font-size: 12px; color: #777;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </td>
        </tr>
    </table>
</body>

</html>
