<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    use HasFactory;
    protected $guarded = [];
     
    public function color(){
        return $this->hasOne(Colors::class,'id','product_id');
    }
    public function size(){
        return $this->hasOne(Sizes::class,'id','product_id');
    }
    public function image(){
        return $this->hasOne(ProductImages::class,'id','product_id');
    }
    public function getAll($product_id){
        return $this->with(['color','size','image'])->where(['product_id'=>$product_id])->get();
    }
    public function getSingle($id){
        return $this->with(['color','size','image'])->find($id);
    }
    
}
