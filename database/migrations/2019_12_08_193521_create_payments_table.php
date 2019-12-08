<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('order_id')->unsigned()->index();
          $table->string('amount');
          $table->string('currency');
          $table->integer('status')->default(0);
          $table->string("platform_status")->nullable();
          $table->string('ip', 20);
          $table->string('payment_code')->nullable();
          $table->longText('payment_response')->nullable();
          $table->longText('process_url')->nullable();
          $table->timestamps();

          $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
