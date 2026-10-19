<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProductController;

Route::get("/", [DashboardController::class, "index"])->name("home");
Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");

// ─── Módulo de Productos ─────────────────────────────────────────
// CRUD completo bajo el prefijo /products con nombres de ruta products.*
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');                   // Listado + filtros
    Route::get('/crear', [ProductController::class, 'create'])->name('create');             // Formulario nuevo
    Route::post('/', [ProductController::class, 'store'])->name('store');                   // Guardar nuevo
    Route::get('/{product}/editar', [ProductController::class, 'edit'])->name('edit');      // Formulario edición
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');         // Actualizar
    Route::patch('/{product}/estado', [ProductController::class, 'toggleStatus'])->name('toggle'); // Toggle activo/inactivo
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');    // Borrado lógico
});

Route::get("/salon", fn() => redirect("/dashboard"))->name("salon");
Route::get("/kitchen", fn() => redirect("/dashboard"))->name("kitchen");

