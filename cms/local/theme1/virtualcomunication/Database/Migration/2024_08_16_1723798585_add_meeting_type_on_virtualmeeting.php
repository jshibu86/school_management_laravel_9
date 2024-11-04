<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMeetingTypeOnVirtualmeeting extends Migration
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
                ->integer("meeting_type")
                ->unsigned()
                ->comment("1=>pta_meeting,0=>normal_meeting")
                ->after("description");
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
