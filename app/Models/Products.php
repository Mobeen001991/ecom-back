<?php

namespace App\Models; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function variant(){
        return $this->hasMany(ProductVariants::class,'product_id');
    }
    public function getAll(){
        return $this->with(['variant.color','variant.size','variant.image'])->get();
    }
    public function getSingle($id){
        return $this->with(['variant.color','variant.size','variant.image'])->find($id);
    }
}
