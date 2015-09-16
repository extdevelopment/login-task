<?php

namespace Netpositive\LoginBundle\Tests;

use Netpositive\UtilBundle\Tests\TestHelperTrait;

/**
 * TestInitTrait.
 *
 * @author extdevelopment
 */
trait TestInitTrait
{
    use TestHelperTrait;

    /**
     * initTest.
     */
    public function initTest()
    {
        $this->initDB();

        //available features

        //$this->loadFixture();

        //$this->createFOSUser('admin', 'admin');
        //$this->addRoleFOSuser('admin', 'ROLE_ADMIN');
    }
}
