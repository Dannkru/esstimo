<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;

Route::get('/', LandingPage::class)->name('home');
Route::get('/kalkulator/{category}', function ($category) {
    return view('calculator', ['category' => $category]);
})->name('calculator');
