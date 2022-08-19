<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OutOfService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_of_service', function (Blueprint $table) {
            $table->id();
            $table->foreignID('item_id')->constrained("item");
            $table->string("note")->default('');
            $table->integer('amount')->default(0);
            $table->boolean('ready_to_use')->default(0);
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
