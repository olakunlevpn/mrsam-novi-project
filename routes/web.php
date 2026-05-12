<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Clean named routes (primary). Standard Laravel-style URLs — no `.html`.
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/products', [PageController::class, 'products'])->name('products');
Route::get('/cattle', [PageController::class, 'cattle'])->name('animals.cattle');
Route::get('/pigs', [PageController::class, 'pigs'])->name('animals.pigs');
Route::get('/poultry', [PageController::class, 'poultry'])->name('animals.poultry');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// Per-product detail page (server-rendered; replaces the in-page SPA detail
// view that used to live inside the catalog).
Route::get('/products/{product:slug}', [ProductDetailController::class, 'show'])
    ->name('products.show');

// Dynamic sitemap endpoint. SitemapController caches its render for 1 hour.
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin-editable robots.txt (Setting::get('seo.robots_txt')). The legacy
// static `public/robots.txt` was deleted so this route wins.
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

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

// 301 redirects from old `.html` URLs to the clean equivalents. Preserves
// SEO and inbound links from before the conversion (plan D4 decision).
Route::redirect('/about.html',    '/about',    301);
Route::redirect('/products.html', '/products', 301);
Route::redirect('/cattle.html',   '/cattle',   301);
Route::redirect('/pigs.html',     '/pigs',     301);
Route::redirect('/poultry.html',  '/poultry',  301);
Route::redirect('/services.html', '/services', 301);
Route::redirect('/contact.html',  '/contact',  301);
Route::redirect('/faq.html',      '/faq',      301);

// Generic `.html` catch-all -> 301 redirect to the clean slug. Covers any
// admin-created custom page that was previously served at `/{slug}.html`.
Route::get('/{slug}.html', fn (string $slug) => redirect("/{$slug}", 301))
    ->where('slug', '[A-Za-z0-9\-]+');

// Generic catch-all for admin-created custom pages. Must be LAST so it does
// not steal traffic from any named route, auth route, or admin panel above.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('page.show');
