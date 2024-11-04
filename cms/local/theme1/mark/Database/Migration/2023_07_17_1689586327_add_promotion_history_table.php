<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromotionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("promotion_history", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("student_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("academic_year_from")
                ->unsigned()
                ->nullable();
            $table
                ->integer("class_id_from")
                ->unsigned()
                ->nullable();
            $table
                ->integer("section_id_from")
                ->unsigned()
                ->nullable();
            $table
                ->integer("academic_year_to")
                ->unsigned()
                ->nullable();
            $table
                ->integer("class_id_to")
                ->unsigned()
                ->nullable();
            $table
                ->integer("section_id_to")
                ->unsigned()
                ->nullable();
            $table
                ->integer("promotion_type")
                ->unsigned()
                ->nullable();

            $table->string("promotion_date")->nullable();

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
