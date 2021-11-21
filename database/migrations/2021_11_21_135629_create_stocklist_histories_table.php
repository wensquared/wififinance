<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocklistHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocklist_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stocklist_id');
            $table->unsignedBigInteger('amount');
            $table->unsignedDecimal('price', 8, 2);
            $table->boolean('action');
            $table->timestamps();

            $table->foreign('stocklist_id')->on('stocklists')->references('id')->onDelete('restrict')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocklist_histories');
    }
}
