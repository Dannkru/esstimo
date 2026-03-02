@extends('layouts.app')

@section('title', 'Kalkulator materiałów – Estimo')

@section('content')
<div class="max-w-2xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8">
    <p class="mb-4 flex flex-wrap items-center gap-2">
        <a href="{{ route('home') }}" class="text-xl font-bold text-white hover:text-indigo-600 dark:hover:text-indigo-400">Estimo</a>
        <a href="{{ route('materials.summary') }}" class="text-sm text-white hover:text-indigo-600 dark:hover:text-indigo-400 ml-2">Lista zakupów</a>
    </p>
    @livewire('kalkulator-wizard')
</div>
@endsection
