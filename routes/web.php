<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get("/", [DashboardController::class, "index"])->name("home");
Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");
Route::get("/products", fn() => redirect("/dashboard"))->name("products");
Route::get("/salon", fn() => redirect("/dashboard"))->name("salon");
Route::get("/kitchen", fn() => redirect("/dashboard"))->name("kitchen");

