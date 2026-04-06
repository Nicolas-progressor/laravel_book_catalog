<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Middleware\AdminMiddleware;

// Главная страница
Route::get('/', [MainController::class, 'index'])->name('app_home');

// Аутентификация
Route::get('/login', [SecurityController::class, 'login'])->name('app_login');
Route::post('/login', [SecurityController::class, 'authenticate']);
Route::post('/logout', [SecurityController::class, 'logout'])->name('app_logout');

// Регистрация
Route::get('/register', [RegistrationController::class, 'register'])->name('app_register');
Route::post('/register', [RegistrationController::class, 'store']);

// Книги
Route::prefix('book')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('book_index');
    Route::get('/load-more', [BookController::class, 'more'])->name('book_more');
    
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/new', [BookController::class, 'create'])->name('book_new');
        Route::get('/new/{authorId}', [BookController::class, 'createWithAuthor'])->name('book_new_with_author');
        Route::post('/new', [BookController::class, 'store'])->name('book_store');
        Route::get('/{id}/edit', [BookController::class, 'edit'])->name('book_edit');
        Route::put('/{id}/edit', [BookController::class, 'update'])->name('book_update');
        Route::delete('/{id}/delete', [BookController::class, 'destroy'])->name('book_delete');
        Route::post('/{id}/delete-cover', [BookController::class, 'deleteCover'])->name('book_delete_cover');
    });
    
    Route::get('/{id}', [BookController::class, 'show'])->name('book_show');
});

// Авторы
Route::prefix('author')->group(function () {
    Route::get('/', [AuthorController::class, 'index'])->name('author_index');
    Route::get('/load-more', [AuthorController::class, 'more'])->name('author_more');
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/new', [AuthorController::class, 'create'])->name('author_new');
        Route::post('/new', [AuthorController::class, 'store'])->name('author_store');
        Route::get('/{id}/edit', [AuthorController::class, 'edit'])->name('author_edit');
        Route::put('/{id}/edit', [AuthorController::class, 'update'])->name('author_update');
        Route::delete('/{id}/delete', [AuthorController::class, 'destroy'])->name('author_delete');
    });
    Route::get('/{id}', [AuthorController::class, 'show'])->name('author_show');
    Route::get('/{id}/books-preview', [AuthorController::class, 'booksPreview'])->name('author_books_preview');
    Route::get('/{id}/books-more', [AuthorController::class, 'booksMore'])->name('author_books_more');
    Route::middleware(['auth'])->group(function () {
        Route::post('/{id}/subscribe', [AuthorController::class, 'subscribe'])->name('author_subscribe');
        Route::post('/{id}/unsubscribe', [AuthorController::class, 'unsubscribe'])->name('author_unsubscribe');
    });
});

// Профиль
Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('app_profile');
    Route::post('/', [ProfileController::class, 'update'])->name('app_profile_update');
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('app_notifications');
    Route::post('/notifications/{id}/read', [ProfileController::class, 'markAsRead'])->name('app_notification_read');
    Route::post('/notifications/mark-all-read', [ProfileController::class, 'markAllAsRead'])->name('app_notifications_mark_all_read');
});
