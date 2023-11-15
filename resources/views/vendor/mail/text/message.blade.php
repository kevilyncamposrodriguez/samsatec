@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <div class="footer-brand">
                <span class=""><img src="assets/img/logo/logoFR-2.png" width="150" alt="Factura Rapida"></span>
            </div>
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
            © {{ date('Y') }} Factura Rápida. @lang('Todos los derechos reservados.')
        @endcomponent
    @endslot
@endcomponent
