<?php namespace redhotmayo\dataaccess\repository\sql;


use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\model\User;

class UserRepositorySQL implements UserRepository {

    /**
     * Return an array of all objects
     *
     * @return array
     */
    public function all() {
        // TODO: Implement all() method.
    }

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $parameters
     * @return mixed
     */
    public function find($parameters) {
        $userDAO = DataAccessObject::GetUserDAO();
        $user = $userDAO->getUser($parameters);

        if (isset($user->id)) {
            $mobileDAO = DataAccessObject::GetMobileDevicesDAO();
            $user->mobileDevice = $mobileDAO->findByUserId($user->id);
        }

        return User::FromStdClass($user);
    }

    /**
     * Create an object from given input
     *
     * @param $input
     * @return mixed
     */
    public function create($input) {
        // TODO: Implement create() method.
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param User $user
     * @return bool
     */
    public function save($user) {
        DB::beginTransaction();
        try {
            $userDAO = DataAccessObject::GetUserDAO();
            $mobileDAO = DataAccessObject::GetMobileDevicesDAO();

            $id = $userDAO->save($user);
            $user->setUserId($id);
            $mobile = $user->getMobileDevice();

            if (isset($mobile)) {
                if ($mobile->getDeviceType() != null) {
                    $mobile->setUserId($id);
                    $mobileDAO->save($mobile);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            throw new Exception('Unable to register user', 500, $e);
            DB::rollback();
        }

        return (isset($id) && $id > 0);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects) {
        // TODO: Implement saveAll() method.
    }

    /**
     * Take an array of database records and convert them to the appropriate objects
     *
     * @param $records
     * @return array
     */
    function convertRecordsToJsonObjects($records) {
        // TODO: Implement convertRecordsToJsonObjects() method.
    }

    /**
     *
     *
     * @param $user
     * @return mixed
     */
    public function update($user) {
        // TODO: Implement update() method.
    }

    public function registerMobileDevice($user, $input) {
        $mobileDAO = DataAccessObject::GetMobileDevicesDAO();
        $mobileDAO->save($mobileDevice);
    }
}