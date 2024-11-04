<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeInVisitorBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("visitorbook", function (Blueprint $table) {
            $table
                ->string("visit_time_in")
                ->nullable()
                ->after("visit_date");
            $table
                ->string("visit_time_out")
                ->nullable()
                ->after("visit_time_in");
            $table
                ->string("meet_person_name")
                ->nullable()
                ->after("visit_time_out");
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
