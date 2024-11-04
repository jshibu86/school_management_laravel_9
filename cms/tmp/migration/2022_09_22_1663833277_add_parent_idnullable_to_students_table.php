<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdnullableToStudentsTable extends Migration
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
                ->nullable()
                ->change();
        });
        Schema::table("parent", function (Blueprint $table) {
            $table
                ->text("address_communication")
                ->nullable()
                ->change();
            $table
                ->text("address_residence")
                ->nullable()
                ->change();
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
