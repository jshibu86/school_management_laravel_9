<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlanPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_price', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('plan_id')->unsigned();            
            $table->integer('term_amount')->unsigned();
            $table->integer('session_amount')->unsigned();
            $table->integer('visible_status')->default(0);                     
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));                      
            $table
                ->foreign("plan_id")
                ->references("id")
                ->on("subscription_plan");
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
