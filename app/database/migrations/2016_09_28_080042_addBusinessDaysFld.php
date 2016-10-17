<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessDaysFld extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('storage_tanks', function(Blueprint $table)
		{
			$table->tinyInteger('business_days')->after('estimated_usage');
		});
        
        Schema::table('storage_updates', function(Blueprint $table)
		{
            $table->dropColumn('subtracted_litres');
			$table->datetime('reading_date')->after('status');
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
			$table->dropColumn('business_days');
		});
        
        Schema::table('storage_updates', function(Blueprint $table)
		{
            $table->string('subtracted_litres', 50)->after('initials');
			$table->dropColumn('reading_date');
		});
	}

}
