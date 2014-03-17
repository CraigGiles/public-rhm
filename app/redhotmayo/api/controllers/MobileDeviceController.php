<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\repository\MobileDeviceRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\model\MobileDevice;
use redhotmayo\model\User;
use redhotmayo\validation\MobileDeviceValidator;
use redhotmayo\validation\ValidationException;

class MobileDeviceController extends BaseController {
    const CREATE = 'redhotmayo\api\controllers\MobileDeviceController@create';
    const UPDATE = 'redhotmayo\api\controllers\MobileDeviceController@update';
    const DESTROY = 'redhotmayo\api\controllers\MobileDeviceController@destroy';

    /** @var \redhotmayo\dataaccess\repository\MobileDeviceRepository $mobileDeviceRepo */
    private $mobileDeviceRepo;

    /** @var \redhotmayo\dataaccess\repository\UserRepository $userRepo */
    private $userRepo;

    /** @var \redhotmayo\validation\MobileDeviceValidator $mobileDevice */
    private $mobileDevice;

    public function __construct(MobileDeviceRepository $repo, UserRepository $userRepository) {
        $this->userRepo = $userRepository;
        $this->mobileDeviceRepo = $repo;
        $this->mobileDevice = new MobileDeviceValidator();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $status = false;
        $code = Response::HTTP_CREATED;
        $result = [];

        try {
            $username = Input::json('username');

            /** @var User $user */
            $user = $this->userRepo->find(['username' => $username]);
            $validated = $this->mobileDevice->validate(Input::json()->all(), $this->mobileDevice->getCreationRules());

            if ($validated && $user->getUserId() != null) {
                $mobile = MobileDevice::FromArray(Input::json()->all());
                $mobile->setUserId($user->getUserId());

                $status = $this->mobileDeviceRepo->create($mobile);
            }

        } catch (ValidationException $ex) {
            $result['message'] = $ex->getMessage();
            $code = Response::HTTP_BAD_REQUEST;
            Log::error($ex);

        } catch (Exception $e) {
            $result['message'] = 'Unable to complete request';
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            Log::error($e);
        }

        $result['status'] = $status;
        return Response::create($result, $code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        return "update {$id}";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {
        return "destroy {$id}";
    }
}