<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration {

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
            $table->string('action', 32)->nullable();
            $table->string('author', 32);
            $table->text('text');

            $table->unsignedInteger('accountId')->nullable();
            $table->unsignedInteger('contactId')->nullable();

            $table->foreign('accountId')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contactId')->references('id')->on('contacts')->onUpdate('cascade')->onDelete('cascade');

            $table->index('accountId');
            $table->index('contactId');

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
