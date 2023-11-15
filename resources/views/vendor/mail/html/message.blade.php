@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<div class="footer-brand">
    <span class="">
        @if(isset(Auth::user()->currentTeam->logo_url) && Auth::user()->currentTeam->logo_url != "")
        <img style="width:100px; height:100px" src="{{ asset(Auth::user()->currentTeam->logo_url) }}">
        @endif
    </span>
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
© {{ date('Y') }} Samsatec. @lang('Todos los derechos reservados.')
@endcomponent
@endslot
@endcomponent