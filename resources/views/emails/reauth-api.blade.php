
@component('mail::message')

Hi! Admin,

Automatic renewal of access token was failed! Please manually re-authenticate it by clicking the button below

@component('mail::button', ['url' => $link])
Renew Token
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

