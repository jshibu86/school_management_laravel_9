<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedByLeave extends Migration
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
                ->integer("approved_by")
                ->unsigned()
                ->nullable()
                ->after("application_status");
            $table
                ->integer("rejected_by")
                ->unsigned()
                ->nullable()
                ->after("approved_by");
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
