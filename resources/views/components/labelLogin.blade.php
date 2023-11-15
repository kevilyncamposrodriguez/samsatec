@props(['value'])

<label {{ $attributes->merge(['class' => 'col-lg-12 col-form-label']) }}>
    {{ $value ?? $slot }}
</label>
