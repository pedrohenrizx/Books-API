<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\PublisherController;
use App\Http\Controllers\Api\V1\ThemeController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\AuthController;

// Prefixo e versionamento
Route::prefix('v1')->group(function () {

    // Rotas públicas de autenticação
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // Rotas públicas de listagem e consulta
    Route::get('books', [BookController::class, 'index']);
    Route::get('books/{book}', [BookController::class, 'show']);
    Route::get('authors', [AuthorController::class, 'index']);
    Route::get('authors/{author}', [AuthorController::class, 'show']);
    Route::get('publishers', [PublisherController::class, 'index']);
    Route::get('publishers/{publisher}', [PublisherController::class, 'show']);
    Route::get('themes', [ThemeController::class, 'index']);
    Route::get('themes/{theme}', [ThemeController::class, 'show']);
    Route::get('books/{book}/reviews', [ReviewController::class, 'index']);

    // Rotas protegidas (usuário autenticado)
    Route::middleware('auth:sanctum')->group(function () {
        // Avaliações (usuário autenticado pode criar)
        Route::post('books/{book}/reviews', [ReviewController::class, 'store']);
        // Logout
        Route::post('logout', [AuthController::class, 'logout']);

        // Rotas apenas para admin
        Route::middleware('can:isAdmin')->group(function () {
            // CRUD completo de livros
            Route::post('books', [BookController::class, 'store']);
            Route::put('books/{book}', [BookController::class, 'update']);
            Route::delete('books/{book}', [BookController::class, 'destroy']);
            // Gerenciar autores de um livro
            Route::post('books/{book}/authors', [BookController::class, 'attachAuthor']);
            Route::delete('books/{book}/authors/{author}', [BookController::class, 'detachAuthor']);
            // Gerenciar temas de um livro
            Route::post('books/{book}/themes', [BookController::class, 'attachTheme']);
            Route::delete('books/{book}/themes/{theme}', [BookController::class, 'detachTheme']);

            // CRUD de autores
            Route::post('authors', [AuthorController::class, 'store']);
            Route::put('authors/{author}', [AuthorController::class, 'update']);
            Route::delete('authors/{author}', [AuthorController::class, 'destroy']);
            // CRUD de editoras
            Route::post('publishers', [PublisherController::class, 'store']);
            Route::put('publishers/{publisher}', [PublisherController::class, 'update']);
            Route::delete('publishers/{publisher}', [PublisherController::class, 'destroy']);
            // CRUD de temas
            Route::post('themes', [ThemeController::class, 'store']);
            Route::put('themes/{theme}', [ThemeController::class, 'update']);
            Route::delete('themes/{theme}', [ThemeController::class, 'destroy']);
        });
    });
});