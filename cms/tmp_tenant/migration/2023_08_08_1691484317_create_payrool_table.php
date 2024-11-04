<?php

use Egulias\EmailValidator\Warning\Comment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePayroolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("salery_particulars", function (Blueprint $table) {
            $table->increments("id");
            $table->string("particular_name");
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

        Schema::create("salery_template", function (Blueprint $table) {
            $table->increments("id");
            $table->string("grade_name")->comment("A,B,C,etc");
            $table->string("basic_salery");
            $table->json("particulars")->nullable();
            $table
                ->string("salery_with_particulars")
                ->nullable()
                ->comment("after particulars deducted final salery");
            $table
                ->string("total_deduction")
                ->nullable()
                ->comment("after particulars total_deduction");
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
        Schema::dropIfExists("payrool");
    }
}
