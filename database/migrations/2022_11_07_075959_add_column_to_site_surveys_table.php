<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSiteSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_surveys', function (Blueprint $table) {
            //
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->string('filename')->nullable();
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_surveys', function (Blueprint $table) {
            //
        });
    }
}
