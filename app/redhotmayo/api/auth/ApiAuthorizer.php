<?php namespace redhotmayo\api\auth;

class ApiAuthorizer {
    public function authorize($input) {
        //user will send username, and token.
        // we will hash username, email, private key, and compare with their token
        // if the hashed values come out the same, return the auth token
        // otherwise, return error message
        return 'yippie kaiyay';
    }
}