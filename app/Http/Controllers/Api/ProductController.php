<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    public function index(){
        return response()->json($this->product->paginate(10));
        //return $this->product->all();
    }

    public function show($id){
        $product = $this->product->find($id);
        if(!$product){
            return response()->json(ApiError::errorMessage('Produto não encontrado',404), 404);
        }
   
        $dataUnique = ['data'=> $product];
        return response()->json($dataUnique);

    }

    public function store(Request $request){
        try{
            $productData = $request->all();
            $this->product->create($productData);
            $return = [
                'data'=> ['msg' => "Produto criado com successo"]
            ];
            return response()->json($return, 201);

        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }else{
                return response()->json(ApiError::errorMessage("Ops! houve um erro ao criar producto", 1010), 500);
            }
        }
    }

    public function update(Request $request, $id){
        try{
            $productData = $request->all();
            $product = $this->product->find($id);
            $product->update($productData);
            $return = [
                'data'=> ['msg' => "Produto actualizado com successo"]
            ];
            return response()->json($return, 201);

        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }else{
                return response()->json(ApiError::errorMessage("Ops! houve um erro actualizar producto", 1011), 500);
            }
        }
    }

    public function destroy(Product $id){
        try{
             //$product = $this->product->find($id);
             $id->delete();
             $return = [
                'data'=> ['msg' => "Produto ".$id->name." deletado com successo"]
            ];
             return response()->json($return, 200);
        }catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }else{
                return response()->json(ApiError::errorMessage("Ops! houve um erro ", 1012), 500);
            }
        }
    }
}
