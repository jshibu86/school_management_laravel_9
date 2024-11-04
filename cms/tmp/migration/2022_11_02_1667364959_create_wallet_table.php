<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("wallet", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("parent_id")->unsigned();
            $table->integer("user_id")->unsigned();
            $table->string("wallet_type");
            $table->string("wallet_amount")->default(0);
            $table->string("deposit_date");

            $table->string("is_approved")->default(1);
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
                ->foreign("parent_id")
                ->references("id")
                ->on("parent");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("wallet");
    }
}
