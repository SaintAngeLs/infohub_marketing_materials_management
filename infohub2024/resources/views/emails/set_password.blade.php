@component('mail::message')
# Dzień dobry

Zostało utworzone konto na naszej platformie. Aby rozpocząć, proszę ustawić hasło.

@component('mail::button', ['url' => $url])
Ustaw hasło
@endcomponent

Ten link wygaśnie za 7 dni. Jeśli nie zażądałeś konta, dalsze działania nie są wymagane.

Dziękujemy,<br>
{{ config('app.name') }}
@endcomponent
