<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactSubmissionController;
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

Route::post('/contact', [ContactSubmissionController::class, 'store'])
    ->name('contact.submit')
    ->middleware('throttle:6,1');

// Blog. Archive routes are declared before the single-post route so the
// {post:slug} binding does not steal `/blog/category/...` or `/blog/tag/...`.
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{tag:slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])
    ->where('post', '[A-Za-z0-9\-]+')
    ->name('blog.show');

// Comment posting: login + verified email + 10/hour per user. See
// CommentController + StoreCommentRequest for the full gate (honeypot,
// min-fill-time, parent-depth, post-publish guard). Must precede the
// generic catch-all below.
Route::post('/blog/{post:slug}/comments', [CommentController::class, 'store'])
    ->middleware(['auth', 'verified', 'throttle:comments'])
    ->where('post', '[A-Za-z0-9\-]+')
    ->name('comments.store');

require __DIR__.'/auth.php';

// Generic catch-all for admin-created pages with custom slugs. Must be last
// so it does not steal traffic from the named routes above.
Route::get('/{slug}.html', [PageController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('page.show');
