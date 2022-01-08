<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('enable')->default(1);

            // 주소기반
            $table->string('uri')->nullable();

            // 테이블 이름
            $table->string('tablename')->nullable();

            // 필드명 과 타입
            $table->string('field')->nullable(); // 필드명
            $table->string('type')->nullable(); // 데이터타입
            $table->string('default')->nullable(); //초기값

            $table->string('validate')->nullable(); //유효성

            $table->string('tab')->nullable(); //텝그룹화
            $table->string('tab_pos')->nullable(); //탭 순서

            // cell
            $table->string('label')->nullable();

            // 입력셀 타입
            // text, number, email, password, time, date
            // select, checkbox, textarea
            $table->string('input')->nullable(); // 입력셀 타입
            $table->string('link')->nullable();
            $table->string('help')->nullable(); // 도움말

            // 출력순서
            $table->integer('pos')->nullable();

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
        Schema::dropIfExists('table_forms');
    }
}
