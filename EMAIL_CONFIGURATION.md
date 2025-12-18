# Email Configuration Guide

## SMTP Authentication Error Fix

If you're seeing errors like:
- "Failed to authenticate on SMTP server"
- "535 Incorrect authentication data"
- "Expected response code '235' but got code '535'"

This means your email credentials are incorrect or the email server settings are wrong.

## Required Environment Variables

Add these to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-email@nurusmesolution.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mail@nurusmesolution.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Common SMTP Settings

### For Gmail:
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # Use App Password, not regular password
```

### For Outlook/Hotmail:
```env
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
```

### For Custom SMTP Server (like cPanel):
```env
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587  # or 465 for SSL
MAIL_ENCRYPTION=tls  # or 'ssl' for port 465
MAIL_USERNAME=mail@yourdomain.com
MAIL_PASSWORD=your-email-password
```

## Important Notes

1. **Port 587** uses **TLS** encryption
2. **Port 465** uses **SSL** encryption
3. Make sure `MAIL_ENCRYPTION` matches your port:
   - Port 587 → `MAIL_ENCRYPTION=tls`
   - Port 465 → `MAIL_ENCRYPTION=ssl`

4. **For Gmail**: You need to:
   - Enable 2-Step Verification
   - Generate an "App Password" (not your regular password)
   - Use the App Password in `MAIL_PASSWORD`

5. **For cPanel/Shared Hosting**:
   - The username is usually the full email address
   - The password is the email account password
   - Check with your hosting provider for the correct SMTP settings

## Testing Email Configuration

After updating your `.env` file:

1. Clear config cache:
   ```bash
   php artisan config:clear
   ```

2. Test email sending from the Loan Approvals page

3. Check Laravel logs if errors persist:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Troubleshooting

### Still getting authentication errors?

1. **Verify credentials**: Make sure username and password are correct
2. **Check port and encryption**: Ensure they match (587/TLS or 465/SSL)
3. **Check firewall**: Ensure your server can connect to the SMTP port
4. **Check email account**: Make sure the email account exists and is active
5. **Check hosting restrictions**: Some hosting providers block SMTP connections

### Need help?

Contact your hosting provider or email service administrator for the correct SMTP settings.




