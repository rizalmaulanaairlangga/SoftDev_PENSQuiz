@props([
    'href' => null,
    'ariaLabel' => 'PENSQuiz',
])

@php
    $wrapperClass = trim('inline-flex shrink-0 items-center focus:outline-none ' . ($attributes->get('class') ?? ''));
@endphp

@if ($href)
    <a href="{{ $href }}" aria-label="{{ $ariaLabel }}" class="{{ $wrapperClass }}">
        <img src="{{ asset('assets/images/img_logo.png') }}" alt="{{ $ariaLabel }}" class="block h-auto w-full object-contain">
    </a>
@else
    <span aria-label="{{ $ariaLabel }}" class="{{ $wrapperClass }}">
        <img src="{{ asset('assets/images/img_logo.png') }}" alt="{{ $ariaLabel }}" class="block h-auto w-full object-contain">
    </span>
@endif
