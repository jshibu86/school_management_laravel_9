<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProductbrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("productbrand", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("category_id")
                ->unsigned()
                ->nullable();
            $table->string("brand_name");
            $table->string("brand_slug");
            $table->text("brand_desc")->nullable();
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
                ->foreign("category_id")
                ->references("id")
                ->on("productcategory");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("productbrand");
    }
}
