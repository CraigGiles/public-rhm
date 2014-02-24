<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\dao\AccountDAO;
use redhotmayo\model\Account;

class AccountSQL implements AccountDAO {
    const TABLE_NAME = 'accounts';
    const C_ID = 'id';
    const C_ACCOUNT_ID = 'accountId';
    const C_USER = 'userId';
    const C_WEEKLY_OPPORTUNITY = 'weeklyOpportunity';
    const C_ACCOUNT_NAME = 'accountName';
    const C_OPERATOR_TYPE = 'operatorType';
    const C_ADDRESS_ID = 'addressId';
    const C_CONTACT_NAME = 'contactName';
    const C_PHONE = 'phone';
    const C_SERVICE_TYPE = 'serviceType';
    const C_CUISINE_TYPE = 'cuisineType';
    const C_CUISINE_ID = 'cuisineId';
    const C_SEAT_COUNT = 'seatCount';
    const C_AVERAGE_CHECK = 'averageCheck';
    const C_EMAIL_ADDRESS = 'emailAddress';
    const C_OPEN_DATE = 'openDate';
    const C_ESTIMATED_ANNUAL_SALES = 'estimatedAnnualSales';
    const C_OWNER = 'owner';
    const C_MOBILE_PHONE = 'mobilePhone';
    const C_WEBSITE = 'website';
    const C_IS_TARGET_ACCOUNT = 'isTargetAccount';
    const C_IS_MASTER = 'isMaster';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';
    const C_DELETED = 'deleted_at';

    //class constants
    const NOTES = 'notes';
    const ADDRESS = 'address';


    public static function GetColumns() {
        return array(
            self::TABLE_NAME . '.' . self::C_ID,
            self::TABLE_NAME . '.' . self::C_USER,
            self::TABLE_NAME . '.' . self::C_WEEKLY_OPPORTUNITY,
            self::TABLE_NAME . '.' . self::C_ACCOUNT_NAME,
            self::TABLE_NAME . '.' . self::C_OPERATOR_TYPE,
            self::TABLE_NAME . '.' . self::C_ADDRESS_ID,
            self::TABLE_NAME . '.' . self::C_CONTACT_NAME,
            self::TABLE_NAME . '.' . self::C_PHONE,
            self::TABLE_NAME . '.' . self::C_SERVICE_TYPE,
            self::TABLE_NAME . '.' . self::C_CUISINE_TYPE,
            self::TABLE_NAME . '.' . self::C_CUISINE_ID,
            self::TABLE_NAME . '.' . self::C_SEAT_COUNT,
            self::TABLE_NAME . '.' . self::C_AVERAGE_CHECK,
            self::TABLE_NAME . '.' . self::C_EMAIL_ADDRESS,
            self::TABLE_NAME . '.' . self::C_OPEN_DATE,
            self::TABLE_NAME . '.' . self::C_ESTIMATED_ANNUAL_SALES,
            self::TABLE_NAME . '.' . self::C_OWNER,
            self::TABLE_NAME . '.' . self::C_MOBILE_PHONE,
            self::TABLE_NAME . '.' . self::C_WEBSITE,
            self::TABLE_NAME . '.' . self::C_IS_TARGET_ACCOUNT,
            self::TABLE_NAME . '.' . self::C_IS_MASTER,
            self::TABLE_NAME . '.' . self::C_CREATED_AT,
            self::TABLE_NAME . '.' . self::C_UPDATED_AT,
        );
    }

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $accounts
     * @return array
     */
    public function saveAll(array $accounts) {
        $unsaved = array();
        foreach ($accounts as $account) {
            $id = $this->save($account);
            if (!isset($id)) {
                $unsaved[] = $account;
            }
        }

        return $unsaved;
    }

    /**
     * Save a record and return the objectId
     *
     * @param Account $address
     * @return int|null
     */
    public function save(Account $account) {
        $id = $account->getAccountId();
        if (isset($id)) {
            //update account
            $this->update($account);
        } else {
            //save account
            $userId = $account->getUserID();
            $master = !isset($userId);
            $address = $account->getAddress();

            $id = DB::table('accounts')
                    ->insertGetId(array(
                        self::C_USER => $userId,
                        self::C_WEEKLY_OPPORTUNITY => $account->getWeeklyOpportunity(),
                        self::C_ACCOUNT_NAME => $account->getAccountName(),
                        self::C_OPERATOR_TYPE => $account->getOperatorType(),
                        self::C_ADDRESS_ID => $address->getAddressId(),
                        self::C_CONTACT_NAME => $account->getContactName(),
                        self::C_PHONE => $account->getPhone(),
                        self::C_SERVICE_TYPE => $account->getServiceType(),
                        self::C_CUISINE_TYPE => $account->getCuisineType(),
                        self::C_CUISINE_ID => $account->getCuisineId(),
                        self::C_SEAT_COUNT => $account->getSeatCount(),
                        self::C_AVERAGE_CHECK => $account->getAverageCheck(),
                        self::C_EMAIL_ADDRESS => $account->getEmailAddress(),
                        self::C_OPEN_DATE => $account->getOpenDate(),
                        self::C_ESTIMATED_ANNUAL_SALES => $account->getEstimatedAnnualSales(),
                        self::C_OWNER => $account->getOwner(),
                        self::C_MOBILE_PHONE => $account->getMobilePhone(),
                        self::C_WEBSITE => $account->getWebsite(),
                        self::C_IS_TARGET_ACCOUNT => (bool)$account->getIsTargetAccount(),
                        self::C_IS_MASTER => $master,
                        self::C_CREATED_AT => Carbon::Now(),
                        self::C_UPDATED_AT => Carbon::Now(),
                    )
                );
            $account->setAccountId($id);
        }

        return $id;
    }

    public function target($accounts, $targeted=true) {
        foreach ($accounts as $id) {
            DB::table('accounts')
              ->where(self::C_ID, $id)
              ->update(array(
                  self::C_UPDATED_AT => Carbon::Now(),
                  self::C_IS_TARGET_ACCOUNT => $targeted
              ));
        }
    }

    public function delete($accounts) {
        foreach ($accounts as $id) {
            DB::table('accounts')
              ->where(self::C_ID, $id)
              ->update(array(
                  self::C_UPDATED_AT => Carbon::Now(),
                  self::C_DELETED => Carbon::Now()
              ));
        }
    }

    private function update(Account $account) {
        $id = $account->getAccountId();
        $values = $this->getValues($account, isset($id));
        DB::table('accounts')
            ->where(self::C_ID, $id)
            ->update($values);
    }

    private function getValues(Account $account, $updating=false) {
        //save account
        $userId = $account->getUserID();
        $master = !isset($userId);
        $address = $account->getAddress();

        $values = [
            self::C_USER => $userId,
            self::C_WEEKLY_OPPORTUNITY => $account->getWeeklyOpportunity(),
            self::C_ACCOUNT_NAME => $account->getAccountName(),
            self::C_OPERATOR_TYPE => $account->getOperatorType(),
            self::C_CONTACT_NAME => $account->getContactName(),
            self::C_PHONE => $account->getPhone(),
            self::C_SERVICE_TYPE => $account->getServiceType(),
            self::C_CUISINE_TYPE => $account->getCuisineType(),
            self::C_CUISINE_ID => $account->getCuisineId(),
            self::C_SEAT_COUNT => $account->getSeatCount(),
            self::C_AVERAGE_CHECK => $account->getAverageCheck(),
            self::C_EMAIL_ADDRESS => $account->getEmailAddress(),
            self::C_OPEN_DATE => $account->getOpenDate(),
            self::C_ESTIMATED_ANNUAL_SALES => $account->getEstimatedAnnualSales(),
            self::C_OWNER => $account->getOwner(),
            self::C_MOBILE_PHONE => $account->getMobilePhone(),
            self::C_WEBSITE => $account->getWebsite(),
            self::C_IS_TARGET_ACCOUNT => (bool)$account->getIsTargetAccount(),
            self::C_IS_MASTER => $master,
            self::C_UPDATED_AT => Carbon::Now(),
        ];

        if (!$updating) {
            $values[self::C_ADDRESS_ID] = $address->getAddressId();
            $values[self::C_CREATED_AT] = Carbon::Now();
        }

        return $values;
    }

    public function restore($accounts) {
        foreach ($accounts as $id) {
            DB::table('accounts')
              ->where(self::C_ID, $id)
              ->update(array(
                  self::C_UPDATED_AT => Carbon::Now(),
                  self::C_DELETED => null)
                );
        }
    }
}
