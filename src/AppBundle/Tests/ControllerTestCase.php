<?php

namespace AppBundle\Tests;

abstract class ControllerTestCase extends RestTestCase
{
    protected $apiToken;

    public function setUp()
    {
        parent::setUp();
        $this->apiToken = $this->logIn(1)->getAccessToken();
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->apiToken);
    }
}
