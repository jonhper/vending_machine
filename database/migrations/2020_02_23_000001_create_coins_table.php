<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('coin', 8, 2);
            $table->integer('items');
        });

        // Set initial coins
        DB::table('coins')->insert(
            array(
              array('coin' => 0.05,'items' => 30),
              array('coin' => 0.10,'items' => 30),
              array('coin' => 0.25,'items' => 30),
              array('coin' => 1,'items' => 30)
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
        Schema::dropIfExists('coins');
    }
}
