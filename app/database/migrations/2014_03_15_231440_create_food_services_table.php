<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food_services', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('service', 30);
            $table->string('meta', 100)->nullable();
            $table->string('s2', 30);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('food_services');
	}

}
