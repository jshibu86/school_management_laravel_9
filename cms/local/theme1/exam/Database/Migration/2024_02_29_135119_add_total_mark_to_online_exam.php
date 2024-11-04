<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalMarkToOnlineExam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_exam', function (Blueprint $table) {
            $table
            ->string("total_marks")
            ->nullable()
            ->after("total_correct");
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_exam', function (Blueprint $table) {
            //
        });
    }
}
