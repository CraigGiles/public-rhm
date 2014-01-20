<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table)
		{
            $table->increments('id');

            $table->string('accountName');
            $table->boolean('isMaster')->default(false);
            $table->string('owner')->nullable();
            $table->string('contactName')->nullable();
            $table->string('emailAddress')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobilePhone')->nullable();
            $table->unsignedInteger('addressId');

            $table->string('cuisineType')->nullable();
            $table->string('openDate')->nullable();
            $table->string('operatorType')->nullable();
            $table->string('seatCount')->nullable();
            $table->string('serviceType')->nullable();
            $table->unsignedInteger('weeklyOpportunity')->nullable();
            $table->unsignedInteger('estimatedAnnualSales')->nullable();
            $table->unsignedInteger('averageCheck')->nullable();
            $table->string('website')->nullable();
            $table->boolean('isTargetAccount')->default(false);

            $table->unsignedInteger('userId')->nullable();
            $table->boolean('deleted')->default(intval(false));

            // set up foreign key constraints
            $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userId')->references('id')->on('users')->onUpdate('cascade');
            $table->index('addressId');
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
		Schema::drop('accounts');
	}

}
