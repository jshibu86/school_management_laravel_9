<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("fee_collection", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("academic_year")->unsigned();
            $table
                ->integer("class_id")
                ->unsigned()
                ->nullable();
            $table
                ->integer("section_id")
                ->unsigned()
                ->nullable();
            $table->integer("student_id")->unsigned();
            $table->integer("fee_setup_id")->unsigned();
            $table->string("bill_no");
            $table->decimal("paid_amount", 11, 2);
            $table->decimal("fine_amount", 11, 2)->default(0);
            $table->decimal("discount_amount", 11, 2)->default(0);
            $table->integer("payment_method");
            $table->string("payment_date");
            $table->string("payment_month");
            $table->string("payment_year");
            $table->text("remark")->nullable();
            $table
                ->string("pay_type")
                ->default(1)
                ->comment("0 =>Monthly,1=>TermWise,2=>One Payment");
            $table
                ->integer("pay_term_id")
                ->unsigned()
                ->nullable();
            $table->string("due_date")->nullable();
            $table->string("pay_month")->nullable();
            $table->string("pay_month_year")->nullable();
            $table->string("receipt_url")->nullable();

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
            $table->softDeletes();
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
