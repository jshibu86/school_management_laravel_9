<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Examperionmapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examperiod_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table
            ->integer("exam_period_id")
            ->unsigned()
            ->nullable();
            $table->string("start_time")->nullable();
            $table->string("end_time")->nullable();
       
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
        Schema::dropIfExists('examperiod_mapping');
    }
}
