<?php namespace redhotmayo\mailers;


use redhotmayo\model\User;

class UserMailer extends Mailer {

    public function welcome(User $user) {
        $view = 'emails.registration.newuser';
        $data = [];
        $subject = 'Welcome to RedHotMAYO';
        return $this->sendTo($user->getEmail(), $subject, $view, $data);
    }
} 