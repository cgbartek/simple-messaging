<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::defaultStringLength(191);
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('channel')->nullable();
            $table->integer('user_to')->nullable();
            $table->integer('user_from')->nullable();
            $table->boolean('user_to_ack')->default(0);
            $table->boolean('user_from_ack')->default(0);
            $table->mediumText('message');
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
        Schema::dropIfExists('messages');
    }
}
