<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookingItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_item', function (Blueprint $table) {
            $table->id();
            $table->foreignID('booking_id')->constrained("booking");
            $table->foreignID('item_id')->constrained("item");
            $table->integer('amount');
            $table->boolean('return_status')->default(0);
            $table->date('date_return');
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
        //
    }
}
