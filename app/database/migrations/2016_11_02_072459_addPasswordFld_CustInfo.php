<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordFldCustInfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tank_informations', function(Blueprint $table)
		{
			$table->string('password_plain', 250)->after('contact_email');
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
			$table->dropColumn('password_plain');
		});
	}

}
