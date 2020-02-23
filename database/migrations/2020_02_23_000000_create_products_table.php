<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('items');
        });

        // Set initial products
        DB::table('products')->insert(
            array(
              array('name' => 'Water','price' => 0.65,'items' => 10),
              array('name' => 'Juice','price' => 1.00,'items' => 10),
              array('name' => 'Soda','price' => 1.50,'items' => 10)
            )
         );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
