@props([
    'type' => 'button',
    'variant' => 'primary',
    'id' => null,
    'onclick' => null
])

@php
    $baseClasses = 'px-6 py-2.5 rounded-full font-bold transition-all duration-200 ease-in-out flex justify-center items-center whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-opacity-50';
    $variants = [
        'primary' => 'bg-[#1e1b4b] text-white hover:bg-[#312e81] shadow-md hover:shadow-lg focus:ring-[#1e1b4b]',
        'warning' => 'bg-[#fbbf24] text-[#1e1b4b] hover:bg-[#f59e0b] shadow-md hover:shadow-lg focus:ring-[#fbbf24]',
        'outline' => 'bg-white border-[3px] border-[#fbbf24] text-[#1e1b4b] hover:bg-[#fbbf24] hover:text-[#1e1b4b] shadow-sm hover:shadow-md focus:ring-[#fbbf24]',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 border-none focus:ring-gray-300'
    ];

    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

<button 
    type="{{ $type }}"
    @if($id) id="{{ $id }}" @endif
    @if($onclick) onclick="{{ $onclick }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>
