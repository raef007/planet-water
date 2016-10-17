<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTypFldUserTb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_accounts', function(Blueprint $table)
		{
			$table->tinyInteger('user_type')->after('status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_accounts', function(Blueprint $table)
		{
			$table->dropColumn('user_type');
		});
	}

}
