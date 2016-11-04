<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdZipcodeDelivNotesTankInfoDb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tank_informations', function(Blueprint $table)
		{
			$table->string('zipcode', 12)->after('state');
			$table->text('delivery_notes')->after('delivery_time');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tank_informations', function(Blueprint $table)
		{
			$table->dropColumn('zipcode');
			$table->dropColumn('delivery_notes');
		});
	}

}
