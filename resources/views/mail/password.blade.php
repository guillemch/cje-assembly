@component('mail::message')
**Hola {{ $name }},**

Tu contraseña para acceder a las votaciones es

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
    "Si tienes problemas para hacer clic en el botón \":actionText\", copia y pega la siguiente dirección\n".
    'en el navegador: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endcomponent
@endcomponent
