<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateExamTimetableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examperiod', function (Blueprint $table) {
            $table->increments('id');
            $table
            ->integer("academic_year")
            ->unsigned()
            ->nullable();
            $table
            ->integer("term_id")
            ->unsigned()
            ->nullable();
            $table
            ->integer("school_type")
            ->unsigned()
            ->nullable();
            $table
            ->integer("class_id")
            ->unsigned()
            ->nullable();

            $table->string("start_date")->nullable();
            $table->string("end_date")->nullable();

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
        Schema::dropIfExists('examperiod');
      
    }
}
