<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollMakePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("salery_payroll_payment", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("group_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->string("month")->nullable();
            $table->string("year")->nullable();
            $table->string("basic_salery")->nullable();
            $table->json("deduction")->nullable();
            $table->json("particulars")->nullable();

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
