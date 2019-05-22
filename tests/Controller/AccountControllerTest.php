<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testAccountSecurity()
    {
        $this->client->request('GET', '/account');

        $this->assertSame(Response::HTTP_MOVED_PERMANENTLY, $this->client->getResponse()->getStatusCode());
    }
}
