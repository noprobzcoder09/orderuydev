<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Carbon\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function tearDown()
    {
        parent::tearDown();

        Carbon::setTestNow(); // this will clear the mock!
    }
}
