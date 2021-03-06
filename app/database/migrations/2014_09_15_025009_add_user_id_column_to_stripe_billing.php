<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdColumnToStripeBilling extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('billing_stripe', function(Blueprint $table)
        {
            $table->unsignedInteger('user_id')->nullable()->after('upgraded_id');
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
            $table->dropColumn('user_id');
        });
	}

}
