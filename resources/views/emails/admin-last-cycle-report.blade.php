@component('mail::message')
<p>Hi,</p>

<p>
    Please see attached for the last cycle generated report of {{$deliveryTiming}}.
</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent