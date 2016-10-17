<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBeforeFill extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('storage_updates', function(Blueprint $table)
		{
			$table->dropColumn('added_litres');
			$table->string('before_delivery', 50)->after('subtracted_litres');
		});
        
        Schema::table('storage_tanks', function(Blueprint $table)
		{
			$table->string('estimated_usage', 50)->after('sump_level');
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
			$table->string('added_litres')->after('initials');
			$table->dropColumn('before_delivery');
		});
        
        Schema::table('storage_tanks', function(Blueprint $table)
		{
			$table->dropColumn('estimated_usage');
		});
	}

}
