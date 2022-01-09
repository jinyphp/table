<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormTabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_tabs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('enable')->default(1);
            $table->string('name');

            // 주소기반
            $table->string('uri')->nullable();

            $table->string('style')->nullable();

            $table->integer('pos')->default(1);

            $table->string('description')->nullable();

            // 작업자ID
            $table->unsignedBigInteger('_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_tabs');
    }
}
