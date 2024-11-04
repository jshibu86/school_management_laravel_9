<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeacheridToSectionTable extends Migration
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
                ->integer("teacher_id")
                ->nullable()
                ->unsigned();

            $table
                ->foreign("teacher_id")
                ->references("id")
                ->on("teacher");
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
