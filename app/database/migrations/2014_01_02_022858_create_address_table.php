<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('primaryNumber')->unsigned();
			$table->string('streetPredirection', 4)->nullable();
			$table->string('streetName');
			$table->string('streetSuffix', 4)->nullable();
			$table->string('suiteType')->nullable();
			$table->string('suiteNumber')->nullable();
			$table->string('cityName');
			$table->string('countyName')->nullable();
			$table->string('stateAbbreviation');
			$table->integer('zipCode');
			$table->integer('plus4Code')->nullable();
			$table->double('longitude')->nullable();
			$table->double('latitude')->nullable();
			$table->boolean('cassVerified')->default(0);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('addresses');
	}

}
