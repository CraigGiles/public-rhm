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
			$table->string('street');
			$table->string('streetSuffix', 4)->nullable();
			$table->string('suiteType')->nullable();
			$table->string('suiteNumber')->nullable();
			$table->string('city');
			$table->string('county')->nullable();
			$table->string('state');
			$table->integer('zipCode');
			$table->integer('plus4Code')->nullable();
			$table->integer('longitude')->unsigned()->nullable();
			$table->integer('latitude')->unsigned()->nullable();
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
