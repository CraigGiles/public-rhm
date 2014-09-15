<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use redhotmayo\dataaccess\repository\dao\sql\BillingStripeSQL;

class RemoveStatusFromStripeBillingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table(BillingStripeSQL::TABLE_NAME, function(Blueprint $table)
        {
            $table->dropColumn('current_status');
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
            $table->string('current_status', 15);
        });
	}

}
