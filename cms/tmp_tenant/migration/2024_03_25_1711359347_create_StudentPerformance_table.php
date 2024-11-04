<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStudentPerformanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentperformance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("academic_year")->unsigned();
            $table->integer("term_id")->unsigned();
            $table->unsignedInteger("class_id");
            $table->integer("section_id")->unsigned();
            $table->string("period")->nullable();
            $table->string('month_year')->nullable();
            $table
            ->integer("status")
            ->default(1)
            ->comment("-1=>trash,0=>disable,1=>active");
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
        Schema::dropIfExists('studentperformance');
    }
}
