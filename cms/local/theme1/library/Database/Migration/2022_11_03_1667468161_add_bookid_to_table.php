<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookidToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("issued_books", function (Blueprint $table) {
            $table
                ->integer("book_id")
                ->unsigned()
                ->after("member_id");

            $table
                ->foreign("book_id")
                ->references("id")
                ->on("books");
        });

        Schema::table("library_member", function (Blueprint $table) {
            $table
                ->string("no_ofbooks_issued")
                ->default(0)
                ->after("library_fine");
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
