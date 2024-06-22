@component('mail::message')
![{{ config('app.name') }} Logo](https://www.dsautomobiles.pl/content/dam/ds/master/home/DS_D1_Logoheader-Desktop-new.png)
# Dzień dobry, {{ $user->name }}

{{ $messageContent }}

Dziękujemy,<br>
{{ config('app.name') }}
@endcomponent
