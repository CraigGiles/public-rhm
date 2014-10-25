<?php  namespace redhotmayo\auth;

use Illuminate\Support\Facades\App;
use redhotmayo\provider\RedHotMayoServiceProvider;

class AuthorizationServiceProvider extends RedHotMayoServiceProvider {
    const SERVICE_NAME = 'authorization';
    const ALIAS_NAME = 'Authorization';
    const ALIAS_VALUE = 'redhotmayo\auth\AuthorizationService';

    /**
     * Sets up any dependencies needed to construct the service and returns
     * the newly constructed service to the caller
     *
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getService() {
        $userRepo = App::make('UserRepository');
        return new AuthorizationService($userRepo);
    }

    public function getServiceName() {
        return self::SERVICE_NAME;
    }

    public function getAliasName() {
        return self::ALIAS_NAME;
    }

    public function getAliasValue() {
        return self::ALIAS_VALUE;
    }
}
