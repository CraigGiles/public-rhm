<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\encryption\EncryptedSQLTable;
use redhotmayo\model\MobileDevice;

class MobileDeviceSQL extends EncryptedSQLTable {
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
     * Obtain a list of encrypted columns
     * @return array
     */
    public function getEncryptedColumns() {
        return [
            self::C_INSTALLATION_ID
        ];
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
            $id = DB::table(self::TABLE_NAME)->insertGetId(
                $this->getValues($mobile, false)
            );

            $mobile->setMobileId($id);
        }
        return $id;
    }

    private function update(MobileDevice $mobile) {
        $values = $this->getValues($mobile, true);

        DB::table(self::TABLE_NAME)
            ->where(self::C_USER_ID, '=', $mobile->getUserId())
            ->update($values);
    }

    public function findByUserId($id) {
        $values = (array)DB::table(self::TABLE_NAME)
            ->where(self::C_USER_ID, '=', $id)
            ->first();

        return $this->decrypt($values);
    }


    /**
     * Gets the values used for storing this object
     *
     * @param \redhotmayo\model\Billing|\redhotmayo\model\MobileDevice $mobile
     * @param bool $updating
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getValues(MobileDevice $mobile, $updating = false) {
        $values = [
            self::C_USER_ID => $mobile->getUserId(),
            self::C_DEVICE_TYPE => $mobile->getDeviceType(),
            self::C_INSTALLATION_ID => $mobile->getInstallationId(),
            self::C_APP_VERSION => $mobile->getAppVersion(),
            self::C_UPDATED_AT => Carbon::now(),
        ];

        if (!$updating) {
            $values[self::C_CREATED_AT] = Carbon::now();
        }

        return $this->encrypt($values);
    }
}
