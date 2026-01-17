# Fix SMTP Authentication Error on Remote Server

## Error: "535 Incorrect authentication data"

This guide will help you fix the SMTP authentication error on your remote server.

## Step-by-Step Fix

### 1. SSH into Your Remote Server

```bash
ssh user@your-server-ip
cd /path/to/your/laravel-project
```

### 2. Check Current Email Configuration

View your current `.env` file:

```bash
cat .env | grep MAIL_
```

### 3. Update `.env` File with Correct SMTP Settings

Edit the `.env` file:

```bash
nano .env
```

**For cPanel/Shared Hosting (nurusmesolution.com domain):**

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.nurusmesolution.com
MAIL_PORT=587
MAIL_USERNAME=mail@nurusmesolution.com
MAIL_PASSWORD="your-actual-email-password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mail@nurusmesolution.com
MAIL_FROM_NAME="${APP_NAME}"
```

**OR if port 587 doesn't work, try port 465 with SSL:**

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.nurusmesolution.com
MAIL_PORT=465
MAIL_USERNAME=mail@nurusmesolution.com
MAIL_PASSWORD="your-actual-email-password"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=mail@nurusmesolution.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Important: Quote Your Password

If your password contains special characters like `#`, `@`, `$`, spaces, etc., **wrap it in quotes**:

```env
MAIL_PASSWORD="your#password@123"  # ✅ Correct - quoted
MAIL_PASSWORD=your#password@123    # ❌ Wrong - # will be treated as comment
```

### 5. Clear Laravel Configuration Cache

**This is critical!** After updating `.env`, clear the cache:

```bash
php artisan config:clear
php artisan cache:clear
```

If you're using config caching in production:

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

### 6. Verify Email Credentials

**Option A: Test via Telnet (if available on server)**

```bash
telnet mail.nurusmesolution.com 587
```

Then manually test authentication.

**Option B: Use cPanel Email Configuration**

1. Log into cPanel
2. Go to "Email Accounts"
3. Verify `mail@nurusmesolution.com` exists and is active
4. Reset the password if needed
5. Note the correct SMTP settings shown in cPanel

### 7. Check for cPanel-Specific Settings

Some cPanel hosts require:

- **SMTP Authentication**: Must be enabled in cPanel email settings
- **Outgoing Mail Server**: Might be `mail.nurusmesolution.com` or `smtp.nurusmesolution.com`
- **Port**: Usually 587 (TLS) or 465 (SSL)
- **Username**: Must be the **full email address** `mail@nurusmesolution.com`
- **Password**: The email account password (not cPanel password)

### 8. Alternative: Use cPanel's SMTP Relay

If direct SMTP doesn't work, you might need to use cPanel's relay:

```env
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

Then configure sendmail/postfix on the server.

### 9. Test Email Configuration

Create a test command or use Laravel Tinker:

```bash
php artisan tinker
```

Then in Tinker:

```php
Mail::raw('Test email', function($message) {
    $message->to('your-test-email@example.com')
            ->subject('SMTP Test');
});
```

Or use a simple test route.

### 10. Check Server Logs

If still failing, check Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

Look for detailed SMTP error messages.

## Common Issues & Solutions

### Issue 1: Password Contains `#` Symbol

**Problem:** `.env` treats `#` as a comment marker.

**Solution:** Wrap password in quotes:
```env
MAIL_PASSWORD="password#123"  # ✅
```

### Issue 2: Wrong Port/Encryption Combination

**Problem:** Port 587 with `ssl` or port 465 with `tls`.

**Solution:** Use correct combinations:
- Port **587** → `MAIL_ENCRYPTION=tls`
- Port **465** → `MAIL_ENCRYPTION=ssl`

### Issue 3: Configuration Cache Not Cleared

**Problem:** Laravel using old cached configuration.

**Solution:** Always run after `.env` changes:
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue 4: Email Account Not Set Up in cPanel

**Problem:** Email account doesn't exist or is disabled.

**Solution:** 
1. Log into cPanel
2. Create/activate email account `mail@nurusmesolution.com`
3. Set a password
4. Use that password in `.env`

### Issue 5: SMTP Auth Disabled on Server

**Problem:** Server has SMTP authentication disabled.

**Solution:** Contact hosting provider to enable SMTP authentication for your account.

### Issue 6: Firewall Blocking SMTP Port

**Problem:** Server firewall blocking outbound SMTP connections.

**Solution:** Contact hosting provider to allow outbound connections on ports 587/465.

## Recommended Settings for cPanel/Hosting

If you're unsure of the exact settings, try these common cPanel configurations:

**Try Configuration 1 (Most Common):**
```env
MAIL_HOST=mail.nurusmesolution.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=mail@nurusmesolution.com
MAIL_PASSWORD="your-password-here"
```

**Try Configuration 2 (If 587 fails):**
```env
MAIL_HOST=mail.nurusmesolution.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=mail@nurusmesolution.com
MAIL_PASSWORD="your-password-here"
```

**Try Configuration 3 (Alternative hostname):**
```env
MAIL_HOST=smtp.nurusmesolution.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=mail@nurusmesolution.com
MAIL_PASSWORD="your-password-here"
```

## Quick Fix Commands (Run on Server)

Copy and paste these commands on your remote server:

```bash
# 1. Clear cache
php artisan config:clear
php artisan cache:clear

# 2. Check current mail config
php artisan tinker --execute="dd(config('mail'));"

# 3. Test email (adjust email address)
php artisan tinker --execute="Mail::raw('Test', fn(\$m) => \$m->to('your@email.com')->subject('Test'));"

# 4. Check Laravel logs
tail -n 50 storage/logs/laravel.log
```

## Still Having Issues?

1. **Contact your hosting provider** - Ask for exact SMTP settings for `nurusmesolution.com`
2. **Verify email account exists** - Check in cPanel Email Accounts section
3. **Test credentials** - Try logging into webmail with same credentials
4. **Check server IP whitelist** - Some email servers require IP whitelisting

## Security Note

Never commit your `.env` file to version control. The `.env` file should already be in `.gitignore`.
