<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tank_informations', function(Blueprint $table)
		{
			$table->string('delivery_time', 250)->after('contact_email');
		});
        
        Schema::table('vehicle_deliveries', function(Blueprint $table)
		{
			$table->text('remarks')->after('remaining_litres');
		});
        
        Schema::table('transaction_logs', function(Blueprint $table)
		{
			$table->text('before_fill')->after('delivered_by');
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
			$table->dropColumn('delivery_time');
		});
        
        Schema::table('vehicle_deliveries', function(Blueprint $table)
		{
			$table->dropColumn('remarks');
		});
        
        Schema::table('transaction_logs', function(Blueprint $table)
		{
			$table->dropColumn('before_fill');
		});
	}

}
