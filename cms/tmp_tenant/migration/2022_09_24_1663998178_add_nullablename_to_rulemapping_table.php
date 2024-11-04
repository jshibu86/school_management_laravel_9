<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullablenameToRulemappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("parent", function (Blueprint $table) {
            $table
                ->string("mother_name")
                ->nullable()
                ->change();
        });
        Schema::table("students", function (Blueprint $table) {
            $table
                ->text("address_communication")
                ->nullable()
                ->change();
            $table
                ->text("address_residence")
                ->nullable()
                ->change();
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
