<?php

/**
 * This file is part of the LoginBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
