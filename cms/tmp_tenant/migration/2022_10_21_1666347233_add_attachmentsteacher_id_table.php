<?php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttachmentsteacherIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        Schema::table("attachements", function (Blueprint $table) {
            $table
                ->integer("teacher_id")
                ->unsigned()
                ->nullable()
                ->after("student_id");
        });
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
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
