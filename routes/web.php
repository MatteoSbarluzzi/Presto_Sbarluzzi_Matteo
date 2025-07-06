<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RevisorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\LoginController;

// Homepage
Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

// Rotte per Articoli
Route::get('/create/article', [ArticleController::class, 'create'])->name('create.article'); // Pagina creazione articolo
Route::get('/article/index', [ArticleController::class, 'index'])->name('article.index'); // Tutti gli articoli
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show'); // Dettaglio articolo
Route::get('/article/{article}/edit', [ArticleController::class, 'edit'])->name('article.edit')->middleware('auth'); // Modifica articolo
Route::put('/article/{article}', [ArticleController::class, 'update'])->name('article.update')->middleware('auth'); // Aggiorna articolo
Route::delete('/article/image/{image}', [ArticleController::class, 'deleteImage'])->name('article.image.delete')->middleware('auth'); // Elimina immagine da articolo
Route::delete('/article/{article}', [ArticleController::class, 'destroy'])->name('article.destroy')->middleware('auth'); // Elimina articolo


// Articoli per categoria
Route::get('/category/{category:slug}', [ArticleController::class, 'byCategory'])->name('byCategory'); // Articoli filtrati per categoria

// Ricerca articoli
Route::get('/search/article', [PublicController::class, 'searchArticles'])->name('article.search'); // Ricerca articoli

// Area Revisore
Route::get('/revisor/index', [RevisorController::class, 'index'])->middleware('isRevisor')->name('revisor.index'); // Dashboard revisore

Route::patch('/accept/{article}', [RevisorController::class, 'accept'])->name('accept'); // Accetta articolo
Route::patch('/reject/{article}', [RevisorController::class, 'reject'])->name('reject'); // Rifiuta articolo
Route::patch('/undo-last-review', [RevisorController::class, 'undoLastReview'])->name('undo.last.review'); // Annulla ultima revisione

Route::get('/revisor/request', [RevisorController::class, 'becomeRevisor'])->middleware('auth')->name('become.revisor'); // Richiesta per diventare revisore
Route::get('/make/revisor/{user}', [RevisorController::class, 'makeRevisor'])->name('make.revisor'); // Promuove un utente a revisore

// Impostazione lingua
Route::post('/lingua/{lang}', [PublicController::class, 'setLanguage'])->name('setLocale'); // Cambia lingua del sito

// Pagine statiche
Route::get('/shipping-and-returns', [PublicController::class, 'shippingAndReturns'])->name('shipping'); // Info spedizione e resi
Route::get('/reviews', [PublicController::class, 'reviews'])->name('reviews'); // Pagina recensioni (visibile a tutti)

// Newsletter
Route::post('/newsletter/subscribe', [PublicController::class, 'subscribeNewsletter'])->name('newsletter.subscribe'); // Iscrizione newsletter

// Recensioni utenti
Route::get('/recensioni', [ReviewController::class, 'index'])->name('reviews'); // Elenco recensioni
Route::post('/recensioni', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store'); // Invia nuova recensione

// Login personalizzato
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Form login personalizzato
Route::post('/login', [LoginController::class, 'login']); // Login utente
