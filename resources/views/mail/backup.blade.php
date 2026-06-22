<!DOCTYPE html>
<html>
<head>
    <title>Database Backup</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #2e6da4;">📌 Daily Database Backup</h2>
        <p>Dear Admin,</p>
        <p>Attached is the latest SQL database backup for <strong>Jan Muhammad & Co</strong>.</p>
        <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin-top: 15px;">
            <ul style="list-style: none; padding: 0;">
                <li><strong>Date:</strong> {{ date('Y-m-d') }}</li>
                <li><strong>Time:</strong> {{ date('H:i:s') }}</li>
                <li><strong>Format:</strong> SQL File</li>
            </ul>
        </div>
        <p style="margin-top: 20px;">Please download and keep this safely. This backup was generated automatically.</p>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #777;">System generated email from Jan Muhammad & Co Dashboard.</p>
    </div>
</body>
</html>
