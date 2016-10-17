<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('company_categories');
        
        Schema::create('company_categories', function(Blueprint $table)
		{
			$table->increments('company_category_id');
			$table->integer('category_id');
			$table->integer('tank_id');
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
		Schema::drop('company_categories');
	}

}
