<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTankTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('storage_tanks', function(Blueprint $table)
		{
			$table->increments('tank_id');
			$table->integer('user_id');
			$table->string('maximum_capacity', 100);
			$table->string('safety_limit', 100);
			$table->string('sump_level', 100);
			$table->tinyInteger('status');
			$table->datetime('created_date');
			$table->datetime('updated_date');
		});
        
        Schema::create('storage_updates', function(Blueprint $table)
		{
			$table->increments('update_id');
			$table->integer('tank_id');
			$table->string('remaining_litres', 100);
			$table->string('added_litres', 100);
			$table->string('subtracted_litres', 100);
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
		Schema::drop('storage_tanks');
		Schema::drop('storage_updates');
	}

}
