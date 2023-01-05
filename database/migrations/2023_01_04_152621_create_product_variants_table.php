<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->integer('price');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_image_id')->constrained()->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['product_variants_size_id_foreign']);
            $table->dropForeign(['product_variants_color_id_foreign']);
            $table->dropForeign(['product_variants_product_id_foreign']);
        });
        
        Schema::dropIfExists('product_variants');
    }
}
