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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('profile', 255);
            $table->string('name', 50);
            $table->integer('age');
            $table->integer('height');
            $table->string('skin_color', 50);
            $table->string('hair_color', 50);
            $table->string('eye_color', 50);
            $table->string('size_body', 50);
            $table->text('body');
            $table->string('phone', 50);
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
        Schema::dropIfExists('information');
    }
};
