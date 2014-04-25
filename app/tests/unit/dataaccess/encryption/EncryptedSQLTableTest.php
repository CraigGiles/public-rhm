<?php namespace encryption;

use Illuminate\Support\Facades\Crypt;
use Mockery as m;
use redhotmayo\dataaccess\repository\dao\sql\BillingSQL;
use TestCase;

class EncryptedSQLTableTest extends TestCase {

    /**
     * @test
     */
    public function it_should_encrypt_only_stripe_id_and_last_four_rows() {
        $columns = [
            'stripe_plan' => 'stripe_plan_decrypted',
            'stripe_id' => 'stripe_id_decrypted',
            'last_four' => 'value_decrypted',
        ];

        Crypt::shouldReceive('encrypt')
             ->once()
             ->with('stripe_id_decrypted')
             ->andReturn('stripe_id_encrypted');
        Crypt::shouldReceive('encrypt')
             ->once()
             ->with('value_decrypted')
             ->andReturn('value_encrypted');

        $expected = [
            'stripe_plan' => 'stripe_plan_decrypted',
            'stripe_id' => 'stripe_id_encrypted',
            'last_four' => 'value_encrypted',
        ];

        $billing = new BillingSQL();
        $result = $billing->encrypt($columns);
        $this->assertEquals($result, $expected);
    }

    /**
     * @test
     */
    public function it_should_decrypt_only_stripe_id_and_last_four_rows() {
        $columns = [
            'stripe_plan' => 'stripe_plan_decrypted',
            'stripe_id' => 'stripe_id_encrypted',
            'last_four' => 'value_encrypted',
        ];

        Crypt::shouldReceive('decrypt')
             ->once()
             ->with('stripe_id_encrypted')
             ->andReturn('stripe_id_decrypted');
        Crypt::shouldReceive('decrypt')
             ->once()
             ->with('value_encrypted')
             ->andReturn('value_decrypted');

        $expected = [
            'stripe_plan' => 'stripe_plan_decrypted',
            'stripe_id' => 'stripe_id_decrypted',
            'last_four' => 'value_decrypted',
        ];

        $billing = new BillingSQL();
        $result = $billing->decrypt($columns);
        $this->assertEquals($result, $expected);
    }
}
