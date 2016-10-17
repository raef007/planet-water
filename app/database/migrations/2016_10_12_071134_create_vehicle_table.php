<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicles', function(Blueprint $table)
		{
			$table->increments('vehicle_id');
			$table->integer('driver_id');
			$table->string('vehicle_label', 250);
			$table->string('vehicle_description', 250);
			$table->string('tank_capacity', 50);
			$table->string('plate_number', 10);
			$table->string('address', 250);
			$table->string('city', 250);
			$table->string('state', 250);
			$table->string('latitude', 100);
			$table->string('longtitude', 100);
			$table->tinyInteger('status');
			$table->datetime('created_date');
			$table->datetime('updated_date');
		});
        
        Schema::create('vehicle_categories', function(Blueprint $table)
		{
			$table->increments('category_id');
			$table->integer('vehicle_id');
			$table->string('category_label', 250);
			$table->tinyInteger('status');
			$table->datetime('created_date');
			$table->datetime('updated_date');
		});
        
        Schema::create('company_categories', function(Blueprint $table)
		{
			$table->increments('category_id');
			$table->integer('tank_id');
			$table->tinyInteger('status');
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
        Schema::drop('vehicles');
		Schema::drop('vehicle_categories');
		Schema::drop('company_categories');
	}

}
