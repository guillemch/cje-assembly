@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ url('/img/logo.jpg') }}" alt="CJE" width="40" style="vertical-align: middle" />
            Asamblea CJE
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            {{ date('Y') }} Asamblea Consejo de la Juventud de España
        @endcomponent
    @endslot
@endcomponent
