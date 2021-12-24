<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploadfile', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('enable')->default(1);

            $table->text('table')->nullable();
            $table->unsignedBigInteger('table_id')->default(0);
            $table->text('field')->nullable();
            $table->text('visible')->nullable();

            $table->text('description')->nullable();
            // 작업자ID
            $table->unsignedBigInteger('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploadfile');
    }
}
