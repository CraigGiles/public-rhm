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
            $table->string('streetName', 50);
            $table->string('streetSuffix', 4)->nullable();
            $table->string('suiteType', 4)->nullable();
            $table->string('suiteNumber', 8)->nullable();
            $table->string('cityName', 30);
            $table->string('countyName', 30)->nullable();
            $table->string('stateAbbreviation', 2);
            $table->string('zipCode', 5);
            $table->string('plus4Code', 4)->nullable();
            $table->boolean('googleGeocoded')->default(false);
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->boolean('cassVerified')->default(false);

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
