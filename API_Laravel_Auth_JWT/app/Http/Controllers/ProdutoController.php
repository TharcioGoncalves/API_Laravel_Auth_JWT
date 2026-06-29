<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProdutoRequest;

class ProdutoController extends Controller
{
    
    public function index():JsonResponse
    {
        $produtos = Produto::all();

        return response()->json([
            "status" => true,
            "produtos" => $produtos
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

                $request->image->move(public_path("img/image"), $imageName);
            }
            $produto = Produto::create([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "image" => $imageName,
                "stock" => $request->stock
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Produto cadastrado com sucesso",
                "product" => $produto
            ], 201);

        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                "status" => false,
                "message" => "Produto não cadastrado"
            ],400);
        
        }
    }

    public function show(Produto $produto):JsonResponse
    {
        return response()->json([
            "status" => true,
            "produto" => $produto
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

                $request->image->move(public_path("img/image"), $imageName);
            }

            $produto->update([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "image" => $imageName,
                "stock" => $request->stock
            ]);

            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "Produto cadastrado com sucesso",
                "produto" => $produto
            ],200);
        }catch(Exception $erro){
            DB::rollback();

            return response()->json([
                "status" => false,
                "message" => $erro->getMessage(),
                "dados negados" => $request->name
            ],400);
        }

    }

    public function destroy(Produto $produto):JsonResponse
    {
        try{
            $file= public_path("img/imagens/".$produto->image);
            if(File::exists($file)){
                File::delete($file);
            }
            $produto->delete();

            return response()->json([
                "status" => true,
                "message" => "Produto deletado com sucesso"
            ],200);
        }catch(Exception $erro){
            return response()->json([
                "status"=>false,
                "message"=>"Produto não eliminado"
            ], 400);
        }
    }

}
