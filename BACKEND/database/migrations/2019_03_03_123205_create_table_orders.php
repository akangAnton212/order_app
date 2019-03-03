<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->uuid('id_supplier');
            $table->string('po_number', 15);
            $table->datetime('po_time');
            $table->string('po_assigne', 20);
            $table->string('ro_number', 20)->nullable();
            $table->date('ro_date')->nullable();
            $table->string('ro_assigne', 20)->nullable();
            $table->boolean('is_approved')->nullable();
            $table->softdeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_orders');
    }
}
