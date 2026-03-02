@extends('layouts.app')

@section('title', 'Blog – Estimo')

@section('content')
<div class="max-w-2xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8 lg:py-12">
    <p class="mb-6">
        <a href="{{ route('home') }}" class="text-white hover:text-indigo-400 font-medium">← Strona główna</a>
    </p>
    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-4">BLOG</h1>
    <p class="text-white">Sekcja blogowa – wkrótce. Artykuły i porady o remontach, materiałach i wycenach.</p>
</div>
@endsection
