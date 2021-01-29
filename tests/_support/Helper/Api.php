<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Exception\ModuleException;

class Api extends \Codeception\Module
{

    public function checkOperationStatusSuccess($resp_code)
    {
        try {
            $response = $this->getModule('REST')->response;
            $array = json_decode($response, true);
            $this->assertEquals($resp_code ,$array['resp_code'],"Operation status should be  $resp_code for success.");
        }
        catch (ModuleException $e) {
        }
    }
}
