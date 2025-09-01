@component('mail::message')
# 🔐 Account Verification Required

<div style="max-width: 100%; overflow-x: auto;">
Dear Valued User,

We've received a request to verify your account. To ensure your security, please use the following One-Time Password (OTP):
</div>

@component('mail::panel')
<div style="text-align: center; font-size: 24px; letter-spacing: 5px; padding: 15px; word-break: break-all;">
    <strong>{{ $otp }}</strong>
</div>
@endcomponent

@component('mail::table')
| Important Information |  |
| -------------------- | ------------------- |
| ⏱️ Validity | 10 minutes from now |
| 🔍 Purpose | Account Verification |
| ✅ Action Required | Enter this code on the verification page |
@endcomponent

<div style="max-width: 100%; overflow-x: auto;">
🔒 **Security Notice:** If you didn't request this verification, please disregard this email and consider reviewing your account security.

❓ Need help? Contact our support team.

Best regards,<br>
The {{ config('app.name') }} Team
</div>

<small style="color: #718096; max-width: 100%; overflow-x: auto;">This is an automated message, please do not reply directly to this email.</small>
@endcomponent
