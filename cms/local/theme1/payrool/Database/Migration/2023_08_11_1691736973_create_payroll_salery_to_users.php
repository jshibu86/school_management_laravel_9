<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollSaleryToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("salery_payroll", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("group_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->integer("grade_id")->unsigned();
            $table
                ->string("basic_salery")
                ->nullable()
                ->comment(
                    "basic salery from selected grade,and usage of particular one person salery changed"
                );
            $table->string("month")->nullable();
            $table->string("year")->nullable();
            $table
                ->string("basic_salery_updated_month")
                ->nullable()
                ->comment("if basic salery updated particular one person");
            $table->string("basic_salery_updated_year")->nullable();
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
