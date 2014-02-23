<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuisinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cuisines', function(Blueprint $table)
		{
            $table->increments('id');

            $table->string('cuisine', 30);
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
		Schema::drop('cuisines');
	}

}
