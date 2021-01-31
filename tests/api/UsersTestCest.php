<?php

use Codeception\Example;
use Yandex\Allure\Adapter\Annotation\Title;
use Yandex\Allure\Adapter\Annotation\Description;

class UsersTestCest
{
    private $secret="";
    private $inputDataFile = 'app/dataProvider/inputData.json';

    /**
     * @return array
     */
    protected function objectProviderCheckStatus(): array
    {
        $string = file_get_contents($this->inputDataFile);

        $result = json_decode($string, true);
        return $result["check_status"];
    }

    /**
     * @return array
     */
    protected function objectProviderRequiredFields(): array
    {
        $string = file_get_contents($this->inputDataFile);
        $result = json_decode($string, true);
        return $result["required_fields"];
    }

    public function _before()
    {
        try {
            $config = \Codeception\Configuration::config();
            $apiSettings = \Codeception\Configuration::suiteSettings('api', $config);
            $this->secret = $apiSettings['params']['secret'];
            $this->inputDataFile = $apiSettings['params']['inputDataFile'];

        }
        catch (Exception $e) {
        }
    }

    // tests

    /**
     * @dataProvider objectProviderCheckStatus
     * @param ApiTester $I
     * @param Example $example
     * @Title("Check positive sceraios")
     * @Description("Get request body from InputData file and send request, checking response")
     */
    public function ByPositiveTest(ApiTester $I, Example $example)
    {
        //prepare request's headers
        $I->haveHttpHeader('secret', $this->secret);
        $I->haveHttpHeader('Content-Type', 'application/json');
        //send request with data from dataProvider
        $I->sendPost('/', $example['data']);
        //check http response code
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        //check resp_code according to data from dataProvider
        $I->checkOperationStatusSuccess($example['resp_code']);//check resp_code
        if($example['resp_code'] == 0) {
            //if request is correct - check schema of response
            $I->seeResponseMatchesJsonType([
                'payment_id' => 'string',
                'order_id' => 'integer',
                'resp_code' => 'integer',
                'check' => 'string',
                'notification' => 'boolean'
            ]);
            $I->checkNotificationData($example['data']['phone'], $example['data']['email']);
        }
        else if($example['resp_code'] != 0) {
            //if request is incorrect - check schema of response
            $I->seeResponseMatchesJsonType([
                'resp_code' => 'integer',
                'message' => 'string'
            ]);
        }
    }

    /**
     * @dataProvider objectProviderRequiredFields
     * @param ApiTester $I
     * @param Example $example
     * @Title("Check required fields")
     * @Description("Get request body from InputData file and send request, checking response ")
     */
    public function CheckRequiredFields(ApiTester $I, Example $example) {
        $I->haveHttpHeader('secret', $this->secret);
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/', $example['data']);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->checkOperationStatusSuccess($example['resp_code']);//check resp_code
        $I->checkMessageContainsRequiredFieldName($example['absent_fields']);

    }
}
