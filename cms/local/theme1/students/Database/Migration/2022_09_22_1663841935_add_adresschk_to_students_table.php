<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdresschkToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("students", function (Blueprint $table) {
            $table
                ->string("address_check")
                ->after("student_type")
                ->nullable();
        });
        Schema::table("parent", function (Blueprint $table) {
            $table
                ->string("address_check")
                ->after("status")
                ->nullable();
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
