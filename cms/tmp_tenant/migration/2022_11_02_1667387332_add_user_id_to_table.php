<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("leave", function (Blueprint $table) {
            $table
                ->integer("user_id")
                ->unsigned()
                ->nullable()
                ->after("leave_type_id");
            $table
                ->integer("group_id")
                ->unsigned()
                ->nullable()
                ->after("user_id");

            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users");
            $table
                ->foreign("group_id")
                ->references("id")
                ->on("user_groups");
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
