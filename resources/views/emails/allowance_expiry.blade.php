<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Allowance Expiry Notification</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;padding:40px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;font-family:Arial, sans-serif;">
          
          <!-- Header -->
          <tr>
            <td style="background-color:#EC8B18;padding:20px;text-align:center;color:#ffffff;font-size:24px;font-weight:bold;">
              Allowance Expiry Notification
            </td>
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding:30px;font-size:16px;line-height:1.6;color:#333333;">
              {!! $messageContent !!}
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color:#f9f9f9;padding:15px;text-align:center;font-size:13px;color:#777;">
              This is an automated message. Please do not reply directly to this email.
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
