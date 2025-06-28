<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RevisorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\LoginController;


// Homepage
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

// Articoli
Route::get('/create/article', [ArticleController::class, 'create'])->name('create.article');
Route::get('/article/index', [ArticleController::class, 'index'])->name('article.index');
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show'); // cambiato da /show/article a /article
Route::get('/article/{article}/edit', [ArticleController::class, 'edit'])->name('article.edit')->middleware('auth'); // rotta per modifica articolo
Route::put('/article/{article}', [ArticleController::class, 'update'])->name('article.update')->middleware('auth'); // rotta per aggiornamento articolo
Route::delete('/article/image/{image}', [ArticleController::class, 'deleteImage'])->name('article.image.delete')->middleware('auth');


// Articoli per categoria
Route::get('/category/{category:slug}', [ArticleController::class, 'byCategory'])->name('byCategory');

// Ricerca
Route::get('/search/article', [PublicController::class, 'searchArticles'])->name('article.search');

// Revisore
Route::get('/revisor/index', [RevisorController::class, 'index'])->middleware('isRevisor')->name('revisor.index');

Route::patch('/accept/{article}', [RevisorController::class, 'accept'])->name('accept'); // patch serve per aggiornare solo una parte della risorsa
Route::patch('/reject/{article}', [RevisorController::class, 'reject'])->name('reject');
Route::patch('/undo-last-review', [RevisorController::class, 'undoLastReview'])->name('undo.last.review');

Route::get('/revisor/request', [RevisorController::class, 'becomeRevisor'])->middleware('auth')->name('become.revisor');
Route::get('/make/revisor/{user}', [RevisorController::class, 'makeRevisor'])->name('make.revisor');

// Lingua
Route::post('/lingua/{lang}', [PublicController::class, 'setLanguage'])->name('setLocale');

// Pagine statiche
Route::get('/shipping-and-returns', [PublicController::class, 'shippingAndReturns'])->name('shipping');
Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews');

// Newsletter
Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// Recensioni
Route::get('/recensioni', [ReviewController::class, 'index'])->name('reviews');
Route::post('/recensioni', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

Route::delete('/article/{article}', [ArticleController::class, 'destroy'])->name('article.destroy')->middleware('auth');

// Login personalizzato
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

