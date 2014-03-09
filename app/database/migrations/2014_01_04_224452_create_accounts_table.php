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
            $table->string('contactTitle', 30)->nullable();
            $table->string('owner', 30)->nullable();
            $table->string('contactName', 30)->nullable();
            $table->string('emailAddress', 40)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobilePhone', 20)->nullable();
            $table->unsignedInteger('addressId');

            $table->string('cuisineType', 40)->nullable();
            $table->string('openDate', 30)->nullable();
            $table->string('operatorType', 40)->nullable();
            $table->string('operatorSize', 40)->nullable();
            $table->string('operatorStatus', 40)->nullable();
            $table->string('seatCount', 30)->nullable();
            $table->string('serviceType', 30)->nullable();
            $table->string('alcoholService', 20)->nullable();
            $table->string('mealPeriod', 20)->nullable();
            $table->unsignedInteger('weeklyOpportunity')->nullable();
            $table->unsignedInteger('estimatedAnnualSales')->nullable();
            $table->string('averageCheck', 20)->nullable();
            $table->string('website', 50)->nullable();
            $table->boolean('isTargetAccount')->default(false);

            $table->unsignedInteger('cuisineId')->nullable();
            $table->unsignedInteger('userId')->nullable();

            // set up foreign key constraints
            $table->foreign('addressId')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('userId')->references('id')->on('users')->onUpdate('cascade');
            $table->index('addressId');
            $table->index('userId');

            $table->softDeletes();
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
