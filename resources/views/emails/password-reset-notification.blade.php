@component('mail::message')
Hi {{$user->name}}!,

<br />
<p>You have successfully updated your password.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
