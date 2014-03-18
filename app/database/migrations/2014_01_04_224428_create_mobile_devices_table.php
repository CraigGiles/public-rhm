<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileDevicesTable extends Migration {

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
            $table->unsignedInteger('userId');
            $table->string('deviceType', 15);
            $table->string('installationId')->nullable()->unique();
            $table->string('appVersion', 20);

            $table->foreign('userId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->index('userId');

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
