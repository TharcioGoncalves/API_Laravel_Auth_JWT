<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/produtos/trash", [ProdutoController::class, "trash"]);
Route::delete("/produtos/delete/{id}", [ProdutoController::class, "delete"]);
Route::get("/produtos/restore/{id}", [ProdutoController::class, "restore"]);

Route::get("/produtos", [ProdutoController::class, "index"]);
Route::get("/produtos/{produto}", [ProdutoController::class, "show"]);
Route::post("/produtos", [ProdutoController::class, "store"]);
Route::put("/produtos/{produto}", [ProdutoController::class, "update"]);
Route::delete("/produtos/{produto}", [ProdutoController::class, "destroy"]);