<?php namespace redhotmayo\notifications;

use Illuminate\Support\Facades\Config;
use redhotmayo\model\User;

class GooglePushNotification {
    public function send(User $user, $data) {
        $installationId = $user->getMobileDeviceInstallationId();

        $installationId = 'APA91bEvbEXrb54VN4kdOeDLWOlCQ-3hdqdBdEqwePrYFeffa6tabL_Ez3dka149UuY4COBqs7HJUIYlS3T0ONjaLsM6bmhlkCrfVX63dwQj0YDldYHAVYmJLNesbdUxekSw00tTe9rrbrsLs-k7fvxGzjCuIcJcdw';
        if (isset($installationId)) {
            $fields = [
                'data' => $data,
                'registration_ids' => [
                    $installationId
                ]
            ];

            $headers = [
                'Authorization: key=' . Config::get('google.push_notification_key'),
                'Content-Type: application/json'
            ];

            $ch = curl_init();

            curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

            $result = curl_exec($ch );

            curl_close( $ch );

            return $result;
        }
    }
}

