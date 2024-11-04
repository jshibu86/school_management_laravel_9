<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("certificate_configurations", function (
            Blueprint $table
        ) {
            $table->increments("id");
            $table->string("head_line")->nullable();
            $table->string("tag_line1")->nullable();
            $table->string("tag_line2")->nullable();
            $table->string("name")->nullable();
            $table->string("paragraph")->nullable();
            $table->string("signature")->nullable();
            $table->string("logo_image")->nullable();
            $table->string("bottom_top_color")->nullable();
            $table->string("bottom_center_color")->nullable();
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
