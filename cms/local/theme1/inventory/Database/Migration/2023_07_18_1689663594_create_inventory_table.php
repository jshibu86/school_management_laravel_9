<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("productcategory", function (Blueprint $table) {
            $table
                ->string("category_type")
                ->default(1)
                ->after("category_desc")
                ->comment("1=>shop,2=>inventory");
        });

        Schema::table("products", function (Blueprint $table) {
            $table
                ->string("product_type")
                ->default(1)
                ->after("product_name")
                ->comment("1=>shop,2=>inventory");
        });

        Schema::table("purchase_order", function (Blueprint $table) {
            $table
                ->string("purchase_type")
                ->default(1)
                ->after("product_id")
                ->comment("1=>shop,2=>inventory");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("productcategory", function (Blueprint $table) {
            $table->dropColumn("category_type");
        });
    }
}
