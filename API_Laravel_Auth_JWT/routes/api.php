<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/produtos", [ProdutoController::class, "index"]);
Route::get("/produtos/{produto}", [produtoController::class, "show"]);
Route::post("/produtos", [produtoController::class, "store"]);
Route::put("/produtos/{produto}", [produtoController::class, "update"]);
Route::delete("/produtos/{produto}", [produtoController::class, "destroy"]);

Route::get("/produtos/trash", [produtoController::class, "trash"]);
Route::delete("/produtos/delete/{produto}", [produtoController::class, "delete"]);
Route::get("/produtos/restore/{produto}", [produtoController::class, "restore"]);