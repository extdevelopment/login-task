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

        $this->assertTrue($crawler->filter('html:contains("Jelszó:")')->count() > 0);
    }
}
