<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transaction_logs', function(Blueprint $table)
		{
			$table->increments('transaction_id');
			$table->integer('tank_id');
			$table->string('company_name', 250);
			$table->string('address', 250);
			$table->string('purchase_number', 250);
			$table->string('product', 250);
			$table->string('batch', 250);
			$table->string('quantity', 250);
			$table->string('delivery_docket', 250);
			$table->string('planned_volume', 250);
			$table->string('invoice_number', 250);
			$table->string('actual_volume', 250);
			$table->datetime('delivery_date', 250);
			$table->string('remarks', 250);
			$table->datetime('order_date', 250);
			$table->string('remaining_litres', 250);
			$table->string('delivered_by', 250);
			$table->string('after_fill', 250);
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
		Schema::drop('transaction_logs');
	}

}
