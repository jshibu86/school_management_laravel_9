<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamTimetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examtimetable', function (Blueprint $table) {
            $table->increments('id');
            $table
            ->string("date")
            ->nullable();
            $table
            ->integer("period_id")
            ->unsigned()
            ->nullable();
            $table
            ->integer("subject")
            ->unsigned()
            ->nullable();
            $table
            ->string("bordercolor")
            ->nullable();

            $table
            ->string("bgcolor")
            ->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examtimetable');
    }
}
