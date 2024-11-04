<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcademicYearTuckShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("orders", function (Blueprint $table) {
            $table
                ->integer("academic_year")
                ->after("user_id")
                ->nullable();
        });
        Schema::table("salery_payroll_payment", function (Blueprint $table) {
            $table
                ->integer("academic_year")
                ->after("user_id")
                ->nullable();
            $table
                ->string("payment_date")
                ->after("year")
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("orders", function (Blueprint $table) {
            $table->dropColumn("academic_year");
        });
        Schema::table("salery_payroll_payment", function (Blueprint $table) {
            $table->dropColumn("academic_year");
            $table->dropColumn("payment_date");
        });
    }
}
