<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_deliveries', function(Blueprint $table)
		{
			$table->increments('vehicle_delivery_id');
			$table->integer('vehicle_id');
            $table->integer('tank_id');
            $table->integer('purchase_number');
			$table->string('volume_manual', 100);
			$table->string('remaining_litres', 100);
			$table->tinyInteger('status');
			$table->datetime('delivery_date');
			$table->datetime('created_date');
			$table->datetime('updated_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_deliveries');
	}

}
