<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProdutoRequest;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{

    public function index():JsonResponse
    {
        $produtos = Produto::all();

        if(count($produtos) == 0){
            return response()->json([
                "status" => true,
                "message" => "Não há produtos cadastrados"
            ],200);
        }

        return response()->json([
            "status" => true,
            "produtos" => ProdutoResource::collection($produtos)
        ],200);
    }

    public function store(produtoRequest $request)
    {
        DB::beginTransaction();

        try{
            if($request->hasFile("image") && $request->file("image")->isValid()){
                $extension = $request->image->extension();
                $imageName = md5($request->image->getClientOriginalName().
                strtotime("now")).".".$extension;

                $request->file("image")->storeAs("image", $imageName, "public");
            }
            $userId = Auth::id();
            $produto = Produto::create([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "image" => $imageName,
                "stock" => $request->stock,
                "user_id" => $userId
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Produto cadastrado com sucesso",
                "product" => new ProdutoResource($produto)
            ], 201);

        }catch(Exception $erro){
            DB::rollBack();

            return response()->json([
                "status" => false,
                "message" => $erro->getMessage(),
            ],400);

        }
    }

    public function show(Produto $produto):JsonResponse
    {
        return response()->json([
            "status" => true,
            "produto" => new ProdutoResource($produto)
        ],200);
    }

    public function update(produtoRequest $request, Produto $produto):JsonResponse
    {
        DB::beginTransaction();

        try{
            $imageName = "imagem.jpeg";
            if($request->hasFile("image") && $request->file("image")->isValid()){
                $file = public_path("img/image/".$produto->image);
                if(File::exists($file)){
                    File::delete($file);
                }

                $extension = $request->image->extension();
                $imageName = md5($request->image->getClientOriginalName().strtotime("now")).".".$extension;

                $request->file("image")->storeAs("image", $imageName, "public");
            }
            $userId = Auth::id();
            $produto->update([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "image" => $imageName,
                "stock" => $request->stock,
                "user_id" => $userId
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Produto editado com sucesso",
                "produto" => new ProdutoResource($produto)
            ],200);
        }catch(Exception $erro){
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => "Produto não editado",
                "dados negados" => $produto
            ],400);
        }

    }

    public function destroy(Produto $produto):JsonResponse
    {
        try{
            $produto->delete();

            return response()->json([
                "status" => true,
                "message" => "Produto enviado para a lixeira"
            ],200);
        }catch(Exception $erro){
            return response()->json([
                "status"=>false,
                "message"=>"Produto não eliminado"
            ], 400);
        }
    }

    public function trash():JsonResponse{
        $produtos = Produto::onlyTrashed()->get();

        if(count($produtos) == 0){

            return response()->json([
                "status" => false,
                "message" => "Não há produtos na lixeira",
            ], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "Lista de produtos da lixeira",
            "produtos da lixeira" => ProdutoResource::collection($produtos)
        ], 200);
    }

    public function delete($id):JsonResponse{
        try{
            $produto = Produto::withTrashed()->find($id);

            $file = public_path("img/image/".$produto->image);
            if(File::exists($file)){
                File::delete($file);
            }
            $produto->forceDelete();

            return response()->json([
                "status" => true,
                "message" => "Produto eliminado permanentemente"
            ], 200);
        }catch(Exception $erro){

           return response()->json([
                "status" => false,
                "message" => "Produto não foi eliminado"
            ], 404);
        }
    }

    public function restore($id):JsonResponse{
        try{
            $produto = Produto::onlyTrashed()->find($id);
            if($produto == null){
                return response()->json([
                    "status" => false,
                    "message" => "O produto não pode ser restaurado"
                ], 404);
            }else{
                $produto->restore();
            }

            return response()->json([
                "status" => true,
                "message" => "Produto restaurado da lixeira",
                "produto restaurado" => new ProdutoResource($produto)
            ],200);
        }catch(Exception $erro){

            return response()->json([
                "status" => false,
                "message" => "Produto não restaurado"
            ],404);
        }
    }



}
