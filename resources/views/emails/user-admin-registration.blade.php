@component('mail::message')

Hi! {{$name}},

Please enter a password to create your account

@component('mail::button', ['url' => $url])
Setup password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent