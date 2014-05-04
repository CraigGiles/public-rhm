<?php namespace encryption;

use Illuminate\Support\Facades\Crypt;
use Mockery as m;
use redhotmayo\dataaccess\repository\dao\sql\BillingStripeSQL;
use TestCase;

class EncryptedSQLTableTest extends TestCase {

    /**
     * @test
     */
    public function it_should_encrypt_only_customer_token() {
        $columns = [
            'plan_id' => 'stripe_plan_decrypted',
            'customer_token' => 'customer_token_decrypted',
        ];

        Crypt::shouldReceive('encrypt')
             ->once()
             ->with('customer_token_decrypted')
             ->andReturn('customer_token_encrypted');

        $expected = [
            'plan_id' => 'stripe_plan_decrypted',
            'customer_token' => 'customer_token_encrypted',
        ];

        $billing = new BillingStripeSQL();
        $result = $billing->encrypt($columns);
        $this->assertEquals($result, $expected);
    }

    /**
     * @test
     */
    public function it_should_decrypt_only_stripe_id_and_last_four_rows() {
        $columns = [
            'plan_id' => 'stripe_plan_decrypted',
            'customer_token' => 'customer_token_encrypted',
        ];

        Crypt::shouldReceive('decrypt')
             ->once()
             ->with('customer_token_encrypted')
             ->andReturn('customer_token_decrypted');

        $expected = [
            'plan_id' => 'stripe_plan_decrypted',
            'customer_token' => 'customer_token_decrypted',
        ];

        $billing = new BillingStripeSQL();
        $result = $billing->decrypt($columns);
        $this->assertEquals($result, $expected);
    }
}
