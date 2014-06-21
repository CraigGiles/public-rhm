<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpgradedAtToStripeBilling extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('billing_stripe', function(Blueprint $table)
		{
            $table->timestamp('upgraded_at')->nullable();
            $table->unsignedInteger('upgraded_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('billing_stripe', function(Blueprint $table)
		{
            $table->dropColumn('upgraded_at');
            $table->dropColumn('upgraded_id');
		});
	}

}
