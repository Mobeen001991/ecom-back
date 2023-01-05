<?php
use App\Models\ProductImages; 

function uploadImage($image){ 
    $name = time().'.'.$image->getClientOriginalExtension();
    $destinationPath = public_path('/images');
    $image->move($destinationPath, $name);
    return $name;
}
function variantHandler($val,$productvaraintDb){
    $pimageid = null; 
    if (isset($val['image'])) { 
        $image = uploadImage($val['image']);
        if(isset($val['image_id'])){
            $pv = ProductImages::find($val['image_id']);
        }else{
            $pv = new ProductImages;
        }
        $pv->name = $image;
        $pv->save();
        $productvaraintDb->product_image_id = $pv->id; 
    }
    $productvaraintDb->price = $val['price'];
    $productvaraintDb->color_id = $val['color_id'];
    $productvaraintDb->quantity = $val['quantity'];
    $productvaraintDb->size_id = $val['size_id'];
    $productvaraintDb->product_id = $val['product_id'];
    $productvaraintDb->save();
    return $productvaraintDb;
}

?>