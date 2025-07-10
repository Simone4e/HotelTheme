<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>New Reservation Request</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;">

    <table width="100%" cellpadding="0" cellspacing="0"
        style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <tr>
            <td style="padding: 20px; border-bottom: 1px solid #eeeeee;">
                <h2 style="margin: 0; color: #333333;">🛎️ New Reservation Request</h2>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px;">
                <p style="margin: 0 0 10px;">
                    <strong style="color: #555;">Room:</strong><br>
                    {{ $room->name ?? 'N/A' }}
                </p>

                <p style="margin: 0 0 10px;">
                    <strong style="color: #555;">Name:</strong><br>
                    {{ $data['name_client'] }}
                </p>

                @if (!empty($data['email']))
                    <p style="margin: 0 0 10px;">
                        <strong style="color: #555;">Email:</strong><br>
                        {{ $data['email'] }}
                    </p>
                @endif

                @if (!empty($data['phone']))
                    <p style="margin: 0 0 10px;">
                        <strong style="color: #555;">Phone:</strong><br>
                        {{ $data['phone'] }}
                    </p>
                @endif

                <p style="margin: 0 0 10px;">
                    <strong style="color: #555;">Dates:</strong><br>
                    {{ $data['date_checkin'] }} to {{ $data['date_checkout'] }}
                </p>

                <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">

                <p style="margin: 0 0 5px; font-weight: bold; color: #555;">Message:</p>
                <p style="margin: 0; color: #333;">{{ $data['messages'] }}</p>
            </td>
        </tr>

        <tr>
            <td
                style="padding: 15px; text-align: center; background-color: #f2f2f2; border-top: 1px solid #eeeeee; font-size: 12px; color: #777;">
                Sent from {{ config('app.name') }}
            </td>
        </tr>
    </table>

</body>

</html>
