<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketColumnsToUtilityBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('utility_bills', function (Blueprint $table) {
            //
            $table->string('service')->nullable();
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->string('cost')->nullable();
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('utility_bills', function (Blueprint $table) {
            //
        });
    }
}
