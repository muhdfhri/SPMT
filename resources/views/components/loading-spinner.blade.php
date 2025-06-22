@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-4 w-4',
        'md' => 'h-8 w-8',
        'lg' => 'h-12 w-12',
    ][$size];
    
    $borderSizes = [
        'sm' => 'border-2',
        'md' => 'border-4',
        'lg' => 'border-8',
    ][$size];
@endphp

<div {{ $attributes->merge(['class' => 'flex justify-center items-center']) }}>
    <div class="animate-spin rounded-full {{ $borderSizes }} border-blue-500 border-t-transparent {{ $sizes }}"></div>
</div>
