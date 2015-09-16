<?php

namespace Netpositive\Context;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Netpositive\LoginBundle\Tests\TestInitTrait;

/**
 * Helper class to Behat test, possible to run WebTestCase PHPUnit test init functions.
 *
 * @author extdevelopment
 */
class BuildWebTestCase extends WebTestCase
{
    use TestInitTrait;

    /**
     * constructor.
     */
    public function __construct()
    {
        // avoid RuntimeException: You must override the KernelTestCase::createKernel() method
        // https://github.com/piece/makegood/issues/63#issuecomment-49115874
        $_SERVER['KERNEL_DIR'] = __DIR__.'/../../../../app';
    }
}
