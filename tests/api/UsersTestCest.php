<?php

use Codeception\Example;

class UsersTestCest
{
    private $secret="";

    /**
     * @return array
     */
    protected function objectProvider()
    {
        return [
            ['resp_code'=>0, 'data' =>[
                'amount' => 333,
                'currency' => 'RUB',
                'order_id' => 'testorder222',
                'type_order' => 1,
                'day' => '24-12-2021',
                'buyer_first_name' => 'rion',
                'buyer_last_name' => 'basov',
                'comment' => 'buy something',
                'phone' => 89996651212,
                'email' => 'davert@codeception.com',
                'notification' => true,
                'address' => 'Lativa'
            ]],
            ['resp_code'=>111, 'data' =>[
                'amount' => '333',
                'currency' => 'RUB',
                'order_id' => 'testorder222',
                'type_order' => "1",
                'day' => '24-12-2021',
                'buyer_first_name' => 'rion',
                'buyer_last_name' => 'basov',
                'comment' => 'buy something',
                'phone' => 89996651212,
                'email' => 'davert@codeception.com',
                'notification' => true,
                'address' => 'Lativa'
            ]],
            ['resp_code'=>222, 'data' =>[
                'amount' => 333,
                'currency' => 'RUB',
                'order_id' => 'testorder222',
                'type_order' => 1,
                'day' => '24-12-2021',
                'buyer_first_name' => 'rion',
                'buyer_last_name' => 'basov',
                'comment' => 'buy something',
                'phone' => 89996651212,
                'email' => 'davert@codeception.com',
                'notification' => true,
            ]],
            ['resp_code'=>333, 'data' =>[
                'amount' => 3333333333333333333333333333,
                'currency' => 'RUB',
                'order_id' => 'testorder222',
                'type_order' => 1,
                'day' => '24-12-2021',
                'buyer_first_name' => 'rion',
                'buyer_last_name' => 'basov',
                'comment' => 'buy something',
                'phone' => 89996651212,
                'email' => 'davert@codeception.com',
                'notification' => true,
                'address' => 'Lativa'
            ]]
        ];
    }

    public function _before()
    {
        try {
            $config = \Codeception\Configuration::config();
            $apiSettings = \Codeception\Configuration::suiteSettings('api', $config);
            $this->secret = $apiSettings['params']['secret'];
        }
        catch (Exception $e) {
        }
    }

    // tests

    /**
     * @dataProvider objectProvider
     * @param ApiTester $I
     * @param Example $example
     */
    public function ByPositiveTest(ApiTester $I, Example $example)
    {
        $I->haveHttpHeader('secret', $this->secret);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/', $example['data']);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->checkOperationStatusSuccess($example['resp_code']);//check resp_code
    }
}
