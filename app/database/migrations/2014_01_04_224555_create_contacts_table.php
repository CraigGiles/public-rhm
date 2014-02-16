<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('accountId');
            $table->string('name');
            $table->string('title', 30)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 40)->nullable();

            $table->index('accountId');

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
		Schema::drop('contacts');
	}

}
