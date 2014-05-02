<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRememberMeTokenToUsersTable extends Migration {
    const REMEMBER_TOKEN = 'remember_token';
    const LENGTH = 100;

    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
            $table->string(self::REMEMBER_TOKEN, self::LENGTH)->nullable()->after('permissions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn(self::REMEMBER_TOKEN);
		});
	}

}
