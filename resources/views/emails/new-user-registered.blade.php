@component('mail::message')
# New Account Request

A new user has registered on **{{ config('app.name') }}** and is waiting for your approval.

## User Details

| Field | Value |
|-------|-------|
| **Name** | {{ $newUser->name }} |
| **Email** | {{ $newUser->email }} |
| **Registered** | {{ $newUser->created_at->format('M j, Y \a\t g:i A') }} |

@component('mail::button', ['url' => url('/admin/users')])
Review Pending Users
@endcomponent

---

*This notification was sent because a new account was requested.*
*Only admins receive this email.*

Thanks,
{{ config('app.name') }}
@endcomponent
