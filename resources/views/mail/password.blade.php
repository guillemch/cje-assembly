@component('mail::message')
**Hola {{ $name }},**

Te has acreditado correctamente en la **Asamblea del Consejo de la Juventud de España**.

A continuación te adjuntamos tu contraseña para acceder a las votaciones:

-------------------
# {{ $password }}
-------------------

{{-- Action Button --}}
@component('mail::button', ['url' => url('/'), 'color' => 'primary'])
Entrar
@endcomponent

{{-- Subcopy --}}
@component('mail::subcopy')
@lang(
    "También puedes acceder entrando a [:actionURL](:actionURL)",
    ['actionURL' => url('/'),]
)
@endcomponent
@endcomponent
