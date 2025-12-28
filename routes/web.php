<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;
use App\Livewire\Calculator;

Route::get('/', LandingPage::class)->name('home');
Route::get('/kalkulator/{category}', Calculator::class)->name('calculator');
