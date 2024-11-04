<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("members", function (Blueprint $table) {
            $table->increments("id");
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("email")->nullable();
            $table->string("mobile")->nullable();
            $table->string("dob")->nullable();
            $table->string("gender")->nullable();
            $table->string("department")->nullable();
            $table->string("department_id")->nullable();
            $table
                ->integer("status")
                ->default(0)
                ->comment("-1=>trash,0=>active,1=>disable");
            $table->timestamps();
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
