<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThrottleTable extends Migration {
//`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//`user_id` int(10) unsigned NOT NULL,
//`ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
//`attempts` int(11) NOT NULL DEFAULT '0',
//`suspended` tinyint(4) NOT NULL DEFAULT '0',
//`banned` tinyint(4) NOT NULL DEFAULT '0',
//`last_attempt_at` timestamp NULL DEFAULT NULL,
//`suspended_at` timestamp NULL DEFAULT NULL,
//`banned_at` timestamp NULL DEFAULT NULL,
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('throttle', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('userId');
            $table->string('ipAddress')->nullable();
            $table->integer('attempts')->default(0);
            $table->boolean('suspended')->default(false);
            $table->boolean('banned')->default(false);
            $table->timestamp('lastAttemptAt')->nullable();
            $table->timestamp('suspendedAt')->nullable();
            $table->timestamp('bannedAt')->nullable();
            $table->timestamps();

            // We'll need to ensure that MySQL uses the InnoDB engine to
            // support the indexes, other engines aren't affected.
            $table->engine = 'InnoDB';
            $table->index('userId');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('throttle');
	}

}
