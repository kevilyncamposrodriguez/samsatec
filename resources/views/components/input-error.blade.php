@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-red']) }}>{{ $message }}</p>
@enderror
