<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addclasssectiononvirtualmeeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("virtual_comunication_list", function (Blueprint $table) {
            $table
                ->integer("class")
                ->unsigned()
                ->nullable()
                ->after("title");
            $table
                ->integer("section")
                ->unsigned()
                ->nullable()
                ->after("class");
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
