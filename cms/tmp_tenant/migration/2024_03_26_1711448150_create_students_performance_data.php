<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsPerformanceData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentperformance_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->unsignedInteger('student_performance_id');
    
            // Define foreign key constraint
            $table->foreign('student_performance_id')
                  ->references('id')->on('studentperformance')
                  ->onDelete('cascade'); 
            $table->integer('academic')->nullable();    
            $table->integer('disciple_compliance')->nullable();   
            $table->integer('sport_event')->nullable(); 
            $table->integer('overall_average')->nullable();
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
        Schema::dropIfExists('studentperformance_data');
    }
}
