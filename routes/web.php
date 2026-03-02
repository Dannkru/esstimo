<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;
use App\Livewire\Calculator;
use App\Http\Controllers\PdfController;

Route::get('/', LandingPage::class)->name('home');
Route::get('/kalkulator', Calculator::class)->name('calculator');

// Kalkulator materiałów (z kalx)
Route::get('/kalkulator-materialow', function () {
    return view('materials.app');
})->name('materials.app');
Route::get('/lista-zakupow', function () {
    return view('materials.summary');
})->name('materials.summary');
Route::get('/quote/pdf', [PdfController::class, 'quoteDownload'])->name('quote.pdf');

// Rate limiting: 60 requestów na minutę
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/kalkulator/{category}', Calculator::class)
        ->where('category', '[a-z0-9_-]+')
        ->name('calculator.category');
});
