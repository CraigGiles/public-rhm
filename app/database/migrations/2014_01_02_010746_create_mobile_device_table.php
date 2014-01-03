<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileDeviceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mobile_devices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('deviceType');
			$table->string('installationId')->unique();
			$table->unsignedInteger('appVersion');
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
		Schema::drop('mobile_devices');
	}

}