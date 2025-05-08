<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;

Route::get('/', [PublicController::class, 'homepage'])->name('homepage');

Route::get('/create/article', [ArticleController::class, 'create'])->name('create.article');

Route::get('/article/index', [ArticleController::class, 'index'])->name('article.index');

Route::get('/show/article/{article}', [ArticleController::class, 'show'])->name('article.show');

Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('byCategory');