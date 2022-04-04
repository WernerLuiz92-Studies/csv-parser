<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->float('initial_value')->nullable();
            $table->date('initial_date')->nullable();
            $table->float('balance')->nullable();
            $table->unsignedBigInteger('main_currency_id')->nullable(false);
            $table->foreign('main_currency_id')->references('id')->on('currencies');
            $table->unsignedBigInteger('secondary_currency_id')->nullable();
            $table->foreign('secondary_currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};