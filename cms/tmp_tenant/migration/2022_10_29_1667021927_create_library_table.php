<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // book category
        Schema::create("book_category", function (Blueprint $table) {
            $table->increments("id");
            $table->string("cat_name");

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

        // books
        Schema::create("books", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("category_id")->unsigned();
            $table->string("title");
            $table->string("book_no");
            $table->string("isbn_no");
            $table->string("publisher_name")->nullable();
            $table->string("author_name")->nullable();
            $table->string("rack_number")->nullable();
            $table->string("quantity")->nullable();
            $table->string("price")->nullable();
            $table->text("book_description")->nullable();
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
                ->on("book_category");
        });

        //add member

        Schema::create("library_member", function (Blueprint $table) {
            $table->increments("id");
            $table->string("member_type");
            $table->integer("group_id")->unsigned();
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
            $table->string("member_id");
            $table->string("member_name");
            $table->string("library_fine")->nullable();

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
                ->foreign("group_id")
                ->references("id")
                ->on("user_groups");
            $table
                ->foreign("class_id")
                ->references("id")
                ->on("lclass");
            $table
                ->foreign("section_id")
                ->references("id")
                ->on("section");
            $table
                ->foreign("student_id")
                ->references("id")
                ->on("students");
        });

        //issued books

        Schema::create("issued_books", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("member_id")->unsigned();
            $table->string("issued_date");
            $table->string("issued_by");
            $table->string("return_date")->nullable();
            $table
                ->integer("is_return")
                ->default(0)
                ->comment("-1=>fined,0=>notreturn,1=>return");

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
        Schema::dropIfExists("library");
    }
}
