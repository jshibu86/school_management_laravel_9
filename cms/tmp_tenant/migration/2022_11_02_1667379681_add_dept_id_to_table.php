<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeptIdToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("chapter", function (Blueprint $table) {
            $table
                ->integer("dept_id")
                ->nullable()
                ->after("chapter_name");
        });

        Schema::table("homework", function (Blueprint $table) {
            $table
                ->integer("dept_id")
                ->nullable()
                ->after("subject_id");
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
