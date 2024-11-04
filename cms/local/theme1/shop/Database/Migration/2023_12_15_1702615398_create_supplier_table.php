<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("supplier", function (Blueprint $table) {
            $table->increments("id");
            $table->string("supplier_name");
            $table->string("supplier_email");
            $table->string("supplier_mobile");
            $table->text("supplier_address")->nullable();
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

        Schema::table("products", function (Blueprint $table) {
            $table->dropColumn("supplier_name");
            $table->dropColumn("supplier_email");
            $table->dropColumn("supplier_mobile");
            $table->dropColumn("supplier_address");
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
