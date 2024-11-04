<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("products", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("brand_id")->nullable();
            $table->integer("category_id")->nullable();
            $table->integer("subcategory_id")->nullable();
            $table->integer("supplier_id")->nullable();
            $table->string("product_name");
            $table->string("product_slug");
            $table->string("product_code");
            $table->string("product_min_qty")->nullable();
            $table->string("product_qty");
            $table->string("product_tags")->nullable();
            $table->string("product_unit")->nullable();
            $table->string("product_sku")->nullable();
            $table->string("product_tax")->nullable();
            $table->string("discount_type")->nullable();
            $table->string("selling_price");
            $table->string("supplier_name");
            $table->string("supplier_email");
            $table->string("supplier_mobile");
            $table->text("supplier_address")->nullable();
            $table->text("short_descp")->nullable();
            $table->text("long_descp")->nullable();
            $table->string("product_thambnail")->nullable();
            $table->integer("hot_deals")->nullable();
            $table->integer("featured")->nullable();
            $table->integer("special_offer")->nullable();
            $table->integer("special_offer_startdate")->nullable();
            $table->integer("special_offer_enddate")->nullable();
            $table->integer("special_deals")->nullable();
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

            $table->userstamps();
            $table->softUserstamps();
            $table->softDeletes();

            // $table
            // ->foreign("class_id")
            // ->references("id")
            // ->on("lclass");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("shop");
    }
}
