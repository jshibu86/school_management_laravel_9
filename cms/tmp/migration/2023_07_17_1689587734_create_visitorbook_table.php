<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVisitorbookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("visitorbook", function (Blueprint $table) {
            $table->increments("id");
            $table->string("visitor_name");
            $table->string("visitor_email")->nullable();
            $table->string("visitor_phone")->nullable();
            $table->string("visit_date")->nullable();
            $table->string("visit_time")->nullable();
            $table->text("reason")->nullable();

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

        Schema::create("complaints", function (Blueprint $table) {
            $table->increments("id");
            $table->string("user_id");

            $table->string("complaint_date")->nullable();
            $table->string("complaint_time")->nullable();
            $table->text("complaint")->nullable();

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
        Schema::dropIfExists("visitorbook");
    }
}
