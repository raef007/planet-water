<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTankLocationFldTankTb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tank_informations', function(Blueprint $table)
		{
			$table->increments('tank_info_id');
			$table->integer('tank_id');
			$table->string('company_name', 250);
			$table->string('address', 250);
			$table->string('city', 250);
			$table->string('state', 250);
			$table->string('latitude', 70);
			$table->string('longtitude', 70);
			$table->string('contact_person', 250);
			$table->string('contact_number', 50);
			$table->string('contact_email', 100);
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
		Schema::drop('tank_informations');
	}

}
