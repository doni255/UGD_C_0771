<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Valiadtion\Rule;
use Validator;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $products = Product::all();//mengambil semua data product

        if(count($products) > 0){
            return response ([
                'message' => 'Retrieve All Succes';
                'data' => $products
            ], 200);
        } // return data semua product dalam bentuk json

        return response ([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data course kosong
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $storeData = $request ->all();
        $validate = Validator::make($storeData, [
            'nama_barang' => 'required|max:60|unique:products',
            'kode' => 'required',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric'
        ]);

        if($validate -> fails())
            return response(['message' => $validate->errors()], 400);

            $product = Product::create($storeData);//membuat sebuah data product
            return response ([
                'message' => 'Add product Succes',
                'data' => $product
            ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product:find($id); //Mencari data product berdasarkan id

        if(!is_null($product)) {
            return response([
                'message' => 'Retrieve Prooduct Succes',
                'data' => $product
            ], 200);
        }

        return response ([
            'message' => 'Product not found',
            'data' => null
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
       


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)//method update atau mengubah sebuah data product
    {
        //
        $product = Product::find($id);
        if(is_null($product)) {
            return response ([
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_barang' => ['required', 'max:60', Rule::unique('products')->ignore($product)],
            'kode' => 'required',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric'
        ]);

        if($validate->fails())
            return response (['message' => $validate -> errors()], 400);


            $product->nama_barang = $updateData['nama_barang'];
            $product->kode = $updateData['kode'];
            $product->harga = $updateData['harga'];
            $product->jumlah = $updateData['jumlah'];


            if($product->save()) {
                return response([
                    'message' => 'Update Product Succes',
                    'data' => $product
                ], 200);
            }

            return response([
                'message' => 'Update Product Failed',
                'data' => null
            ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::find($id);

            if(is_null($product)){
                return response([
                    'message' => 'Product Not Found',
                    'data' => null
                ], 404);
            }

            if($product->delete()){
                return response ([
                    'message' => 'Delete Product Succes'.,
                    'data' => $product
                ], 200);
            }

            return response ([
                'message' => 'Delete Product Failed',
                'data' => null
            ], 400);          
    }
}
