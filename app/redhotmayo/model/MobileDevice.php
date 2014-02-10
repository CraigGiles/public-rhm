<?php namespace redhotmayo\model;


class MobileDevice extends DataObject {
    private $userId;
    private $deviceType;
    private $installationId;
    private $appVersion;

    function __construct($userId, $installationId, $deviceType, $appVersion) {
        $this->userId = $userId;
        $this->installationId = $installationId;
        $this->deviceType = $deviceType;
        $this->appVersion = $appVersion;
    }

    public static function FromArray($input) {
        $userId = isset($input['userId']) ? $input['userId'] : null;
        $deviceType = isset($input['deviceType']) ? $input['deviceType'] : null;
        $installationId = isset($input['installationId']) ? $input['installationId'] : null;
        $appVersion = isset($input['appVersion']) ? $input['appVersion'] : null;

        return new MobileDevice($userId, $installationId, $deviceType, $appVersion);
    }

    public function setMobileId($value) {
        $this->setId($value);
    }

    public function getMobileId() {
        return $this->getId();
    }

    /**
     * @param mixed $appVersion
     */
    public function setAppVersion($appVersion) {
        $this->appVersion = $appVersion;
    }

    /**
     * @return mixed
     */
    public function getAppVersion() {
        return $this->appVersion;
    }

    /**
     * @param mixed $deviceType
     */
    public function setDeviceType($deviceType) {
        $this->deviceType = $deviceType;
    }

    /**
     * @return mixed
     */
    public function getDeviceType() {
        return $this->deviceType;
    }

    /**
     * @param mixed $installationId
     */
    public function setInstallationId($installationId) {
        $this->installationId = $installationId;
    }

    /**
     * @return mixed
     */
    public function getInstallationId() {
        return $this->installationId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }




} 