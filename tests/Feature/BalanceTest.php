<?php

namespace Tests\Feature;

use Tests\TestCase;

class BalanceTest extends TestCase
{
    /**
     * A reset application test.
     *
     * @return void
     */
    public function test_reset_the_application()
    {
        $reset = $this->post('/reset');
        $reset->assertStatus(200)->assertContent('OK');

    }
    /**
     * A test of balance for non existing account.
     *
     * @return void
     */
    public function test_balance_for_non_existing_account()
    {
        $reset = $this->get('/balance/account_id=1234');
        $reset->assertStatus(404)->assertContent('0');

    }
    /**
     * Test for Create account with initial balance.
     *
     * @return void
     */
    public function test_create_account_with_initial_balance()
    {
        $reset = $this->post('/event', ["type" => "deposit", "destination" => "100", "amount"=>10]);
        $reset->assertStatus(201)->assertJson(["destination"=> ["id"=>"100", "balance"=>10]]);

    }
    /**
     * Test for deposit into existing account
     *
     * @return void
     */
    public function test_deposit_into_existing_account()
    {
        $reset = $this->post('/event', ["type" => "deposit", "destination" => "100", "amount"=>10]);
        $reset->assertStatus(201)->assertJson(["destination"=> ["id"=>"100", "balance"=>20]]);

    }
    /**
     * Test get balance for existing account
     *
     * @return void
     */
    public function test_get_balance_for_existing_account()
    {
        $reset = $this->get('/balance?account_id=100');
        $reset->assertStatus(200)->assertContent('20');

    }
    /**
     * Test for withdraw from non-existing account
     *
     * @return void
     */
    public function test_withdraw_from_non_existing_account()
    {
        $reset = $this->post('/event', ["type" => "withdraw", "origin" => "200", "amount"=>10]);
        $reset->assertStatus(404)->assertContent('0');

    }
    /**
     * Test for withdraw from existing account
     *
     * @return void
     */
    public function test_withdraw_from_existing_account()
    {
        $reset = $this->post('/event', ["type" => "withdraw", "origin" => "100", "amount"=>5]);
        $reset->assertStatus(201)->assertJson(["origin"=> ["id"=>"100", "balance"=>15]]);
        
    }
    /**
     * Test for transfer from existing account
     *
     * @return void
     */
    public function test_transfer_from_existing_account()
    {
        $reset = $this->post('/event', ["type" => "transfer", "origin" => "100", "amount"=>15, "destination" => "300"]);
        $reset->assertStatus(201)->assertJson([
            "origin"=> ["id"=>"100", "balance"=>0],
            "destination"=> ["id"=>"300", "balance"=>15]
        ]);

    }
    /**
     * Test for transfer from non-existing account
     *
     * @return void
     */
    public function test_transfer_from_non_existing_account()
    {
        $reset = $this->post('/event', ["type" => "transfer", "origin" => "200", "amount"=>15, "destination" => "300"]);
        $reset->assertStatus(404)->assertContent('0');

    }

}
