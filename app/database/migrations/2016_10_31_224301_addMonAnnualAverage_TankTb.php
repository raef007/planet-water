<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonAnnualAverageTankTb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('storage_tanks', function(Blueprint $table)
		{
			$table->string('monthly_usage', 250)->after('estimated_usage');
			$table->string('annual_usage', 250)->after('monthly_usage');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('storage_tanks', function(Blueprint $table)
		{
			$table->dropColumn('monthly_usage');
			$table->dropColumn('annual_usage');
		});
	}

}
