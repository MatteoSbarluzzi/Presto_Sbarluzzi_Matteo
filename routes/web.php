<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RevisorController;

Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

Route::get('/create/article', [ArticleController::class, 'create'])->name('create.article');

Route::get('/article/index', [ArticleController::class, 'index'])->name('article.index');

Route::get('/show/article/{article}', [ArticleController::class, 'show'])->name('article.show');

Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('byCategory');

Route::get('revisor/index', [RevisorController::class, 'index'])->name('revisor.index');

Route::patch('/accept/{article}', [RevisorController::class, 'accept'])->name('accept'); //patch serve per aggiornare solo una parte della risorsa, possiamo dunque aggiornarla con solo i dati che ci interessano
Route::patch('/reject/{article}', [RevisorController::class, 'reject'])->name('reject'); 

Route::get('revisor/index', [RevisorController::class, 'index'])->middleware('isRevisor')->name('revisor.index');

Route::get('/revisor/request', [RevisorController::class, 'becomeRevisor'])->middleware('auth')->name('become.revisor'); // logica per far partire l'email (rotta protetta da auth)

Route::get('/make/revisor/{user}', [RevisorController::class, 'makeRevisor'])->name('make.revisor');

Route::patch('/undo-last-review', [RevisorController::class, 'undoLastReview'])->name('undo.last.review');

Route::get('/search/article', [PublicController::class, 'searchArticles'])->name('article.search'); // rotta per la ricerca degli articoli