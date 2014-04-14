<?php


class RedHotMayoController extends BaseController {
    use RedHotMayoRedirectorTrait;

    /**
     * Get the authenticated user. If there is no currently authenticated user, null is returned.
     *
     * @return User|null
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getAuthedUser() {
        $user = null;

        if (Auth::user()) {
            /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepo */
            $userRepo = App::make('UserRepository');
            $user = $userRepo->find(['username' => Auth::user()->username]);
        }

        return $user;
    }
}
