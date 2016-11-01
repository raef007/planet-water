<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdPurchaseNumVehicleDeliveryTb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('vehicle_deliveries', function(Blueprint $table)
		{
			$table->dropColumn('purchase_number');
		});
        
		Schema::table('vehicle_deliveries', function(Blueprint $table)
		{
			$table->string('purchase_number', 70)->after('tank_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vehicle_deliveries', function(Blueprint $table)
		{
			$table->dropColumn('purchase_number');
		});
        
		Schema::table('vehicle_deliveries', function(Blueprint $table)
		{
			$table->integer('purchase_number')->after('tank_id');
		});
	}

}
