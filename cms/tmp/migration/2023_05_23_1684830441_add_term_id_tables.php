<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTermIdTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("attendance", function (Blueprint $table) {
            $table
                ->string("academic_term")
                ->nullable()
                ->after("academic_year");
        });
        Schema::table("period_class", function (Blueprint $table) {
            $table
                ->string("academic_term")
                ->nullable()
                ->after("academic_year");
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
