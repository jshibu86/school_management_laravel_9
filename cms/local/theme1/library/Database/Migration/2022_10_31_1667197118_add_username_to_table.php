<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernameToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("library_member", function (Blueprint $table) {
            $table->string("academic_year")->after("member_type");

            $table->string("member_username")->after("member_id");
            $table->string("date_ofjoin")->after("member_username");
            $table
                ->string("date_ofleave")
                ->after("date_ofjoin")
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
