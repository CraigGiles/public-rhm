<?php

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use redhotmayo\billing\BillingService;
use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\distribution\exception\AccountSubscriptionException;
use redhotmayo\distribution\AccountSubscriptionManager;
use redhotmayo\model\Region;
use redhotmayo\model\User;

class AdministrationController extends RedHotMayoWebController {

	const INDEX = 'administration.index';
	const ADMINISTRATION = 'administration';
	const SUCCESS_MESSAGE = 'The file was uploaded and will be processed soon.';
	const FAILURE_MESSAGE = 'Unable to upload and process the file.  Please contact support.';

    public function __construct() {
		$this->beforeFilter('auth');
    }

    /**
     * Currently just forwards to the index page.
     *
     * @return Response
     *
     * @author Jeff Weyer <jweyer@redhotmayo.com>
     */
    public function index() {
        $user = $this->getAuthedUser();
        return View::make(self::INDEX);
    }

	/**
	 * Allows a user to upload an excel spreadsheet containing lead data.
	 *
	 * @author Jeff Weyer <jweyer@redhotmayo.com>
	 */
	public function upload() {

		$file = Input::file('file');
		$files = array('file' => $file);
		$rules = array('file' => array('required', 'max:5000', 'mimes:xls,xlsx'));

		$validation = Validator::make($files, $rules);
		if ($validation->fails()) {
			return Redirect::to(self::ADMINISTRATION)->withErrors($validation);
		}

		$originalFileName = $file->getClientOriginalName();
		$fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
		$destination = storage_path() . '/uploads';

		$args = array($originalFileName, $fileName, $destination);
		$entry = vsprintf('Saving uploaded lead file [originalFileName=%s, newFileName=%s, destination=%s]', $args);
		Log::info($entry);

		$uploaded = null;
		try {
			$uploaded = $file->move($destination, $fileName);
			throw new Exception("Testing the exception handling");
		} catch(Exception $exception) {
		    $entry = vsprintf('Unable to move an uploaded lead file [originalFileName=%s, newFileName=%s, destination=%s]', $args);
		    Log::error($entry);
		    Log::error($exception);
		    return Redirect::to(self::ADMINISTRATION)->with('message', self::FAILURE_MESSAGE);;;
		}

		if (null != $uploaded) {
		   return Redirect::to(self::ADMINISTRATION)->with('message', self::SUCCESS_MESSAGE);;
		} else {
		   return Redirect::to(self::ADMINISTRATION)->with('message', self::FAILURE_MESSAGE);;;
		}

	}
}
