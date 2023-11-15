@props(['value'])

<label {{ $attributes->merge(['class' => 'col-lg-4 col-form-label']) }}>
    {{ $value ?? $slot }}
</label>
