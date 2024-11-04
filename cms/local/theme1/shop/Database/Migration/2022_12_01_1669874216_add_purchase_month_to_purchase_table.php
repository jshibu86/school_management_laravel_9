<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseMonthToPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("purchase_order", function (Blueprint $table) {
            $table
                ->string("purchase_month")
                ->nullable()
                ->after("purchase_date");
            $table
                ->string("purchase_year")
                ->nullable()
                ->after("purchase_month");
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
