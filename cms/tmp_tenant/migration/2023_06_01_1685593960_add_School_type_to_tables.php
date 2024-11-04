<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolTypeToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("section", function (Blueprint $table) {
            $table
                ->integer("school_type")
                ->nullable()
                ->after("department_id")
                ->comment("1=>Secondary,2=>Primary,3=>Kindergarten");
        });

        Schema::table("subject", function (Blueprint $table) {
            $table
                ->integer("school_type")
                ->nullable()
                ->after("name")
                ->comment("1=>Secondary,2=>Primary,3=>Kindergarten");
            $table
                ->integer("department_id")
                ->nullable()
                ->after("school_type");
        });

        Schema::table("exam", function (Blueprint $table) {
            $table
                ->integer("school_type")
                ->nullable()
                ->after("department_id")
                ->comment("1=>Secondary,2=>Primary,3=>Kindergarten");
        });

        Schema::table("classtimetable", function (Blueprint $table) {
            $table
                ->integer("school_type")
                ->nullable()
                ->after("dept_id");
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
