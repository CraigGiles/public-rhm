<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration {

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
            $table->string('contactName')->nullable();
            $table->string('cuisineType')->nullable();
            $table->string('emailAddress')->nullable();
            $table->string('openDate')->nullable();
            $table->string('operatorType')->nullable();
            $table->unsignedInteger('addressId');
            $table->string('phone')->nullable();
            $table->string('seatCount')->nullable();
            $table->string('serviceType')->nullable();
            $table->unsignedInteger('userId')->nullable();
            $table->unsignedInteger('weeklyOpportunity')->nullable();

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


/**
 * accountName
address -> FK to address table
contacts -> many FKs to contact_information table
contact_info -> FK to contact_information table
cuisineType
notes -> FK to note table
operatorType
seatCount
serviceType
openDate
weeklyOpportunity
accountAssignee -> FK to user table (null = master record)
 */