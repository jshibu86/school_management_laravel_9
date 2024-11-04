<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRentBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("books", function (Blueprint $table) {
            $table
                ->string("book_rended")
                ->default(0)
                ->after("quantity");
            $table
                ->string("damaged_count")
                ->default(0)

                ->after("book_rended");
            $table
                ->string("lost_count")
                ->default(0)

                ->after("damaged_count");
            $table
                ->string("stolen_count")
                ->default(0)
                ->after("lost_count");
            $table
                ->string("active_count")
                ->nullable()
                ->after("stolen_count");
            $table
                ->string("inactive_count")
                ->default(0)
                ->after("active_count");
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
