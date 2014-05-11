<?php namespace redhotmayo\dataaccess\repository\sql;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\BillingRepository;
use redhotmayo\dataaccess\repository\dao\BillingStripeDAO;
use redhotmayo\dataaccess\repository\dao\sql\BillingStripeSQL;
use redhotmayo\model\User;
use redhotmayo\utility\Arrays;

class BillingRepositorySQL extends RepositorySQL implements BillingRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\BillingRepositorySQL';

    /** @var BillingStripeDAO $dao */
    private $dao;

    public function __construct() {
        $this->dao = new BillingStripeSQL();
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($object) {
        $dao = new BillingStripeSQL();//todo: dont do this
        $dao->save($object);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll(array $objects) {
        // TODO: Implement saveAll() method.
    }

    public function getTableName() {
        return BillingStripeSQL::TABLE_NAME;
    }

    public function getColumns() {
    }

    protected function getConstraints($parameters) {
    }

    public function getCustomerToken(User $user) {
        $token = (array)DB::table($this->getTableName())
            ->select(BillingStripeSQL::C_CUSTOMER_TOKEN)
            ->where(BillingStripeSQL::C_USER_ID, '=', $user->getId())
            ->first();

        $decryptedRow = $this->dao->decrypt($token);
        return Arrays::GetValue($decryptedRow, BillingStripeSQL::C_CUSTOMER_TOKEN, false);
    }
}
