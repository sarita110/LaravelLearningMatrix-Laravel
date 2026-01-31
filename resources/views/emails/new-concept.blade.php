@component('mail::message')
# New Concept Published

A new Laravel concept has been published on **The Laravel Learning Matrix**.

## {{ $concept->title }}

{{ $concept->description }}

**Phase:** {{ $concept->phase }}

@component('mail::button', ['url' => url('/concepts/' . $concept->slug)])
View Concept
@endcomponent

---

*This notification was sent because a new concept was published.*
*The email was dispatched from a queued job — not during the HTTP request.*

Thanks,
{{ config('app.name') }}
@endcomponent
