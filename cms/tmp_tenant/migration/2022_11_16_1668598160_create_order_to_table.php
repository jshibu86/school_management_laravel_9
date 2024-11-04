<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("orders", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("user_id")->unsigned();
            $table
                ->integer("student_id")
                ->nullable()
                ->unsigned();
            $table
                ->integer("parent_id")
                ->nullable()
                ->unsigned();

            $table->string("order_number");
            $table->string("order_amount");
            $table->string("payment_type")->nullable();
            $table->string("payment_method")->nullable();
            $table->string("transaction_id")->nullable();
            $table->string("payment_status")->default(0);
            $table->string("currency")->nullable();
            $table->string("order_date");
            $table->string("order_month");
            $table->string("order_year");
            $table->string("invoice_url")->nullable();
            $table->string("processing_date")->nullable();
            $table->string("shipped_date")->nullable();
            $table->string("delivery_date")->nullable();
            $table->string("cancel_date")->nullable();
            $table->string("return_date")->nullable();
            $table->string("return_reason")->nullable();

            //
            $table
                ->integer("order_status")
                ->default(1)
                ->comment(
                    "-2=>return,-1=>cancel,0=>new,1=>processing,2=>shipped,3=>deliver"
                );
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

            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users");
            $table
                ->foreign("student_id")
                ->references("id")
                ->on("students");
            $table
                ->foreign("parent_id")
                ->references("id")
                ->on("parent");
        });

        Schema::create("order_items", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("order_id")->unsigned();
            $table->integer("product_id")->unsigned();
            $table->string("product_name");
            $table->string("product_image");
            $table->string("product_code");
            $table->string("product_price");
            $table->string("qty");
            $table->string("total_price");
            //
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

            $table
                ->foreign("order_id")
                ->references("id")
                ->on("orders");
            $table
                ->foreign("product_id")
                ->references("id")
                ->on("products");
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
