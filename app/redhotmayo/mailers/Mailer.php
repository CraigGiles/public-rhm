<?php namespace redhotmayo\mailers;


use Illuminate\Support\Facades\Mail;

abstract class Mailer {
    public function sendTo($email, $subject, $view, $data = []) {
        return Mail::queue($view, $data, function($message) use ($email, $subject) {
            $message->to($email)
                    ->subject($subject);
        });
    }
} 