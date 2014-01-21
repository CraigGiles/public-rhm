<?php namespace redhotmayo\facade;


use Illuminate\Support\Facades\Facade;

class ApiAuthorizer extends Facade {
    protected static function getFacadeAccessor() { return 'api_authorizer'; }
} 