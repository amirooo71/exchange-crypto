<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('trade_id')->nullable();
            $table->integer('seller_id')->nullable();
            $table->integer('buyer_id')->nullable();
            $table->integer('order_buy_id')->nullable();
            $table->integer('order_sale_id')->nullable();
            $table->decimal('price', 16, 8);
            $table->decimal('amount', 16, 8);
            $table->string('type');
            $table->bigInteger('timestamp')->nullable();
            $table->timestamps();
            $table->bigInteger('t1')->nullable();
            $table->bigInteger('t5')->nullable();
            $table->bigInteger('t15')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
