<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use redhotmayo\model\MobileDevice;

class MobileDeviceSQL {
    const TABLE_NAME = 'mobile_devices';
    const C_ID = 'id as mobileId';

    const C_USER_ID = 'userId';
    const C_DEVICE_TYPE = 'deviceType';
    const C_INSTALLATION_ID = 'installationId';
    const C_APP_VERSION = 'appVersion';

    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public static function GetColumns() {
        return array(
            self::TABLE_NAME . '.' . self::C_ID,
            self::TABLE_NAME . '.' . self::C_USER_ID,
            self::TABLE_NAME . '.' . self::C_DEVICE_TYPE,
            self::TABLE_NAME . '.' . self::C_INSTALLATION_ID,
            self::TABLE_NAME . '.' . self::C_APP_VERSION,
            self::TABLE_NAME . '.' . self::C_CREATED_AT,
            self::TABLE_NAME . '.' . self::C_UPDATED_AT,
        );
    }

    /**
     * @param MobileDevice $mobile
     * @return int
     */
    public function save(MobileDevice $mobile) {
        $id = $mobile->getMobileId();
        if (isset($id)) {
            $this->update($mobile);
        } else {
            $id = DB::table('mobile_devices')->insertGetId([
                self::C_USER_ID => $mobile->getUserId(),
                self::C_DEVICE_TYPE => $mobile->getDeviceType(),
                self::C_INSTALLATION_ID => $mobile->getInstallationId(),
                self::C_APP_VERSION => $mobile->getAppVersion(),
                self::C_CREATED_AT => Carbon::now(),
                self::C_UPDATED_AT => Carbon::now(),
            ]);
            $mobile->setMobileId($id);
        }
        return $id;
    }

    private function update(MobileDevice $mobile) {
        $values = [
            self::C_UPDATED_AT => Carbon::now(),
        ];

        $deviceType = $mobile->getDeviceType();
        $installationId = $mobile->getInstallationId();
        $appVersion = $mobile->getAppVersion();

        if (isset($deviceType)) $values[self::C_DEVICE_TYPE] = $deviceType;
        if (isset($installationId)) $values[self::C_INSTALLATION_ID] = $installationId;
        if (isset($appVersion)) $values[self::C_APP_VERSION] = $appVersion;

        DB::table(self::TABLE_NAME)
            ->where(self::C_USER_ID, '=', $mobile->getUserId())
            ->update($values);
    }

    public function findByUserId($id) {
        return DB::table(self::TABLE_NAME)
            ->where(self::C_USER_ID, '=', $id)
            ->first();
    }

}