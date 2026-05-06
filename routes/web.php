<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about.html', [PageController::class, 'about'])->name('about');
Route::get('/products.html', [PageController::class, 'products'])->name('products');
Route::get('/cattle.html', [PageController::class, 'cattle'])->name('animals.cattle');
Route::get('/pigs.html', [PageController::class, 'pigs'])->name('animals.pigs');
Route::get('/poultry.html', [PageController::class, 'poultry'])->name('animals.poultry');
Route::get('/services.html', [PageController::class, 'services'])->name('services');
Route::get('/contact.html', [PageController::class, 'contact'])->name('contact');
Route::get('/faq.html', [PageController::class, 'faq'])->name('faq');
