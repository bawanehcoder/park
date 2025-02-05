<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('car_id')->unsigned();
            $table->bigInteger('parking_id')->unsigned();
            $table->bigInteger('slot_id')->unsigned()->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('parking_id')
                ->references('id')
                ->on('parkings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('slot_id')
                ->references('id')
                ->on('slots')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
