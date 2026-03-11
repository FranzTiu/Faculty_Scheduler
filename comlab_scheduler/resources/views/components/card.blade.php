@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'bg-white shadow-md rounded-xl p-6 transition-all duration-200 ease-in-out ' . $class]) }}>
    {{ $slot }}
</div>
