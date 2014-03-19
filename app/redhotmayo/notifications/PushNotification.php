<?php namespace redhotmayo\notifications;


use redhotmayo\model\User;

interface PushNotification {
    public function send(User $user, $message, $title, $tickerText);
}