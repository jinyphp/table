<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_columns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('enable')->default(1);

            // 주소기반
            $table->string('uri')->nullable();

            // 테이블 이름
            $table->string('tablename')->nullable();

            // 필드명 과 타입
            $table->string('field')->nullable();
            $table->string('type')->nullable();

            // cell
            $table->string('title')->nullable();
            $table->string('cell')->nullable();
            $table->string('link')->nullable();
            $table->string('edit')->nullable();

            // 필드숨김
            $table->string('display')->nullable();

            // 출력순서
            $table->integer('pos')->nullable();
            // 가로폭
            $table->string('width')->nullable();
            //정렬여부
            $table->string('sort')->nullable();

            $table->string('description')->nullable();

            // 작업자ID
            $table->unsignedBigInteger('user_id')->default(0);
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
        Schema::dropIfExists('table_columns');
    }
}
