<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\ProductVariants;
use App\Models\ProductImages; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Traits\ImageTrait;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = new Products;
        $data = $products->getAll();
        return response()->json(['data'=>$data], 200); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = $this->validateRequest($request);
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            return response()->json(['type'=>'error','error'=>$errors],400);
        }
        $image = uploadImage($request->file('image'));
        $product = Products::create(['image'=>$image,'description'=>$request->description,'name'=>$request->name,'user_id'=>$user->id]);
        $default_variant_id = $this->manageVarients($request->product_variant, $product->id);
        $product->default_variant_id = $default_variant_id;
        $product->save();
        return response()->json(['type'=>'success','msg'=>'Product Created'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,$id)
    {
        $products = new Products;
        $data = $products->getSingle($id);
        return response()->json(['data'=>$data], 200);  
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $products = Products::find($id);
        $validator = $this->validateRequest($request);
        if($validator->fails())
        {
            $messages=$validator->messages();
            $errors=$messages->all();
            return response()->json(['type'=>'error','error'=>$errors],400);
        }
        if($request->hasFile('image')){
            $products->image = uploadImage($request->file('image'));
        }
        $products->description = $request->description;
        $products->name = $request->name;
        $default_variant_id = $this->manageVarients($request->product_variant);
        $products->default_variant_id = $default_variant_id;
        $products->save();
        return response()->json(['type'=>'success','msg'=>'Product updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        $pr = Products::find($id);
        $v = $pr->variant();
        $pr->variant()->delete();
        $pr->delete();
        foreach($v as $im){
            ProductImages::find($im->product_image_id)->delete();
        }
        return response()->json(['type'=>'success','msg'=>'Product Deleted'], 200);
    }

    function validateRequest(Request $request){
        $validator=Validator::make($request->all(),[
            'name' => ['required', 'max:250'],
            'description' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'product_variant.*.size_id'=>'exists:sizes,id',
            'product_variant.*.color_id'=>'exists:colors,id',
            'product_variant.*.price'=>'required',
        ], [
            'product_variant.*.size_id.exists' => 'Invalid Size Passed', 
            'product_variant.*.color_id.exists' => 'Invalid Color Passed', 
            'product_variant.*.price.required' => 'Invalid Price Passed', 
        ]);
        return $validator;
        
    }
    function manageVarients($productVariant,$product_id=null){
        $default_variant_id = null;
        foreach($productVariant as $val){
            if (!isset($val['product_id'])) {
                $val['product_id'] = $product_id;
            }
            if(isset($val['id'])){
                $productvaraintDb = ProductVariants::find($val['id']);
            }else{
                $productvaraintDb = new ProductVariants;
            }
            $pv = variantHandler($val,$productvaraintDb);
            if(isset($val['isDefaultVaraint'])){
                $default_variant_id = $pv->id;
            }
        }
        return $default_variant_id;
    }
}
