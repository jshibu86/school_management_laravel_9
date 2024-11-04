<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAttachementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("attachements", function (Blueprint $table) {
            $table->increments("id");
            $table
                ->integer("student_id")
                ->unsigned()
                ->nullable();
            $table->string("attachment_name");
            $table->string("attachment_url");
            $table->string("attachment_description")->nullable();

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
                ->foreign("student_id")
                ->references("id")
                ->on("students");
        });
        Schema::table("students", function (Blueprint $table) {
            $table
                ->string("username")
                ->nullable()
                ->after("last_name");
        });
        Schema::table("parent", function (Blueprint $table) {
            $table
                ->string("father_name")
                ->nullable()
                ->change();
            $table
                ->string("father_email")
                ->nullable()
                ->change();
            $table
                ->string("father_mobile")
                ->nullable()
                ->change();
            $table
                ->string("fathernat_id")
                ->nullable()
                ->change();
            $table
                ->integer("user_id")
                ->unsigned()
                ->after("student_id");
            $table
                ->string("guardian_relation")
                ->nullable()
                ->after("guardian_occupation");
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
        //
    }
}
