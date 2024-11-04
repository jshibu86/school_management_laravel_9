<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventDescriptionToEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("event", function (Blueprint $table) {
            $table
                ->text("description")
                ->nullable()
                ->after("event_date");
        });

        Schema::table("books", function (Blueprint $table) {
            $table
                ->integer("is_recommended")
                ->default(0)
                ->after("author_name");
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
