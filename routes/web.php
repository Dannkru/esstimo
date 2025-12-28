<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;
use App\Livewire\Calculator;

Route::get('/', LandingPage::class)->name('home');
Route::get('/kalkulator', Calculator::class)->name('calculator');

// Rate limiting: 60 requestów na minutę
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/kalkulator/{category}', Calculator::class)
        ->where('category', '[a-z0-9_-]+') // Walidacja slug w routingu
        ->name('calculator.category');
});
