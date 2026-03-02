@extends('layouts.app')

@section('title', 'Lista zakupów – Estimo')

@section('content')
<div class="max-w-3xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8">
    <p class="mb-4">
        <a href="{{ route('home') }}" class="text-xl font-bold text-white hover:text-indigo-600 dark:hover:text-indigo-400">Estimo</a>
        <span class="text-white mx-2">/</span>
        <a href="{{ route('materials.app') }}" class="text-white hover:text-indigo-600 dark:hover:text-indigo-400">Kalkulator materiałów</a>
    </p>
    @livewire('quote-summary')
</div>
@endsection
