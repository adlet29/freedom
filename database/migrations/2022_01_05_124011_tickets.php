<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->string('file_path')->nullable();
            // $table->boolean('viewed')->nullable();
            $table->boolean('viewed')->default(0);
            $table->timestamps();

            // $table->integer('user_id');
            $table->foreignId('user_id')->nullable()
                                                ->constrained();
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
