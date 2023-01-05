<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\ProductVariants;
use App\Models\ProductImages; 
use Illuminate\Http\Request; 
use Validator;

class ProductVarientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($product_id)
    {
        $products = new ProductVariants;
        $data = $products->getAll($product_id);
        return response()->json(['data'=>$data], 200); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            return response()->json(['type'=>'error','error'=>$errors],400);
        }
        $val = $request->all();
        $productvaraintDb = new ProductVariants;
        variantHandler($val,$productvaraintDb);
        return response()->json(['type'=>'success','msg'=>'Product variant Added'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $id)
    {
        $products = new ProductVariants;
        $data = $products->getSingle($id);
        return response()->json(['data'=>$data], 200);  
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $val = $request->all();
        $validator = $this->validateRequest($request);
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            return response()->json(['type'=>'error','error'=>$errors],400);
        }
        $productvaraintDb = ProductVariants::find($id);
        variantHandler($val,$id,$productvaraintDb);
        return response()->json(['type'=>'success','msg'=>'Product variant updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $isDefault = Products::where(['default_variant_id'=>$id])->first();
        if($isDefault){
            return response()->json(['type'=>'error','msg'=>'Default variant cannot be deleted'], 404);
        }else{

        }
        $productVarient = ProductVariants::find($id);
        $imageId = $productVarient->product_image_id;
        $productVarient->delete();
        ProductImages::find($imageId)->delete();
        return response()->json(['type'=>'success','msg'=>'Product variant Deleted'], 200);
    }    
    function validateRequest(Request $request){
        $validator=Validator::make($request->all(),[
            'size_id'=>['required','exists:sizes,id'],
            'product_id'=>['required','exists:products,id'],
            'color_id'=>['required','exists:colors,id'],
            'price'=>'required',
        ], [
            'size_id.exists' => 'Invalid Size Passed', 
            'color_id.exists' => 'Invalid Color Passed', 
            'product_id.exists' => 'Invalid Product id Passed', 
            'price.required' => 'Invalid Price Passed', 
        ]);
        return $validator;
        
    }
}
