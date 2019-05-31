<?php

namespace Tests\Unit\Infusionsoft;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Box;

class API extends TestCase
{
    private $api;
    private $contactId = 13;

    protected function setUp(){
        parent::setUp();

        $this->api = (new App\Services\InfusionsoftV2\InfusionsoftFactory('oauth2'))->service();
    }

    public function itCanFetchContactById()
    {
        $items = $this->api->fetchContactById($this->contactId);

        $box = new Box($items);
        $this->assertEquals($box->has($this->contactId));
    }
}
