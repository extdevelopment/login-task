<?php

namespace Netpositive\LoginBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * DefaultControllerTest.
 *
 * @author zsolt.k
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * testIndex.
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('html:contains("Password:")')->count() === 1);
        $this->assertTrue($crawler->filter('html:contains("Email:")')->count() === 1);
        $this->assertTrue($crawler->filter('html:contains("This is login protected page!")')->count() === 0);
    }
}
