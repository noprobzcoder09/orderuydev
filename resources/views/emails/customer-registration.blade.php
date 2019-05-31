@component('mail::message')

Hi! {{$name}},

We successfully created your account!

@component('mail::button', ['url' => $url])
Setup password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent