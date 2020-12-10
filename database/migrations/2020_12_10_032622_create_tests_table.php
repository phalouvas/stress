<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('rate')->default(1);
            $table->string('type', 4)->default('get');
            $table->string('environment');
            $table->string('path');
            $table->text('payload')->default('{}');
            $table->boolean('is_running')->default(false);
            $table->string('status')->nullable();
            $table->integer('hits')->default(0);
            $table->double('duration')->default(0);
            $table->integer('speed')->default(0);
            $table->string('timer')->nullable();
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
        Schema::dropIfExists('tests');
    }
}
