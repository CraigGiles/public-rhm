<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeBillingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('billing_stripe', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('upgraded_id')->nullable();
            $table->integer('plan_id')->unsigned();
            $table->string('current_status', 15);
            $table->string('customer_token', 255);
            $table->boolean('auto_renew');
            $table->timestamp('subscription_ends_at');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('upgraded_at')->nullable();

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
		Schema::drop('billing_stripe');
	}

}
