<?php

namespace Tests;

use Carbon\Carbon;
use Laravel\BrowserKitTesting\TestCase as BrowserBaseTestCase;

abstract class TestBrowserCase extends BrowserBaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://localhost';


    protected function tearDown()
    {
        parent::tearDown();

        Carbon::setTestNow(); // this will clear the mock!
    }
}