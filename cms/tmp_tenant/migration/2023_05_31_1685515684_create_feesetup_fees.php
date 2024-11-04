<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesetupFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("fee_setup", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("academic_year")->unsigned();
            $table->integer("class_id")->unsigned();
            $table->string("payment_type")->nullable();

            $table
                ->integer("school_type")
                ->unsigned()
                ->nullable();
            $table
                ->integer("department_id")
                ->unsigned()
                ->nullable();

            $table->string("total_amount");

            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
        });

        Schema::create("fee_setup_lists", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("fee_setup_id")->unsigned();
            $table->integer("fee_id")->unsigned();
            $table->string("fee_name")->nullable();
            $table->string("fee_amount")->nullable();

            $table
                ->integer("status")
                ->default(1)
                ->comment("-1=>trash,0=>disable,1=>active");
            $table->timestamp("created_at")->useCurrent();
            $table
                ->timestamp("updated_at")
                ->default(
                    DB::raw("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
                );
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
