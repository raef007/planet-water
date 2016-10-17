<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStorageUpdatesDelivery extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('storage_updates', function(Blueprint $table)
		{
			$table->tinyInteger('delivery_made')->after('remaining_litres');
			$table->string('initials', 50)->after('delivery_made');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('storage_updates', function(Blueprint $table)
		{
			$table->dropColumn('delivery_made');
			$table->dropColumn('initials');
		});
	}

}
