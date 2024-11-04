<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToStudentsTable extends Migration
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
                ->integer("parent_id")
                ->unsigned()
                ->after("section_id");

            $table
                ->foreign("parent_id")
                ->references("id")
                ->on("parent");
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
