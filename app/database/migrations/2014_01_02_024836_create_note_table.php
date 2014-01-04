<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('accountId')->nullable();
            $table->unsignedInteger('contactId')->nullable();
//            $table->foreign('accountId')->references('id')->on('accounts');
//            $table->foreign('contactId')->references('id')->on('contacts');

            $table->string('action', 32)->nullable();
            $table->string('author', 32);
            $table->text('text');
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
		Schema::drop('notes');
	}

}
