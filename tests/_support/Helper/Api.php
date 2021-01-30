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

    public function checkNotificationData($email, $phone)
    {
        try {
            $response = $this->getModule('REST')->response;
            $array = json_decode($response, true);
            $this->assertContains($email ,$array['notification'],"Notification field should contains  $email for success.");
            $this->assertContains($phone ,$array['notification'],"Notification field should contains  $phone for success.");

        }
        catch (ModuleException $e) {
        }
    }

    public function checkMessageContainsRequiredFieldName($absent_fields)
    {
        try {
            $response = $this->getModule('REST')->response;
            $array = json_decode($response, true);
            foreach ($absent_fields as $field) {
                $this->assertContains($field ,$array['message'],"Notification field should contains  $field for success.");
            }
        }
        catch (ModuleException $e) {
        }
    }


}
