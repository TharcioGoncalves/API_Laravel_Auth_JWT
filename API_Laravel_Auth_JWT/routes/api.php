<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/produtos/trash", [ProdutoController::class, "trash"])->middleware("auth:api");
Route::delete("/produtos/delete/{id}", [ProdutoController::class, "delete"])->middleware("auth:api");
Route::get("/produtos/restore/{id}", [ProdutoController::class, "restore"])->middleware("auth:api");

Route::post("/login", [AuthController::Class, "login"]);
Route::post("/register", [AuthController::Class, "register"]);
Route::post("/logout", [AuthController::Class, "logout"])->middleware("auth:api");


Route::get("/produtos", [ProdutoController::class, "index"]);
Route::get("/produtos/{produto}", [ProdutoController::class, "show"])->middleware("auth:api");
Route::post("/produtos", [ProdutoController::class, "store"])->middleware("auth:api");
Route::put("/produtos/{produto}", [ProdutoController::class, "update"])->middleware("auth:api");
Route::delete("/produtos/{produto}", [ProdutoController::class, "destroy"])->middleware("auth:api");