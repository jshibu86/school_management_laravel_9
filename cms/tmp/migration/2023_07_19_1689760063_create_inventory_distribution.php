<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDistribution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("inventory_distribution", function (Blueprint $table) {
            $table->increments("id");

            $table
                ->integer("academic_year")
                ->unsigned()
                ->nullable();
            $table
                ->integer("user_group_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("category_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("product_id")
                ->unsigned()
                ->nullable();
            $table
                ->string("quantity")

                ->nullable();
            $table
                ->string("total_price")

                ->nullable();
            $table->string("distribution_date")->nullable();

            $table
                ->string("all_checked")
                ->default(0)
                ->nullable();
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

        Schema::create("inventory_distribution_users", function (
            Blueprint $table
        ) {
            $table->increments("id");

            $table
                ->integer("distribution_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("user_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("class_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("section_id")
                ->unsigned()
                ->nullable();

            $table
                ->integer("student_id")
                ->unsigned()
                ->nullable();

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
