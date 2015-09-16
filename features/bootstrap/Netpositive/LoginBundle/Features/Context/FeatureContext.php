<?php

namespace Netpositive\LoginBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Netpositive\Context\BuildWebTestCase;
use Behat\Symfony2Extension\Context\KernelDictionary;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeSuite
     *
     * @param BeforeSuiteScope $scope
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public static function prepare(BeforeSuiteScope $scope)
    {
        $testCase = new BuildWebTestCase();
        $testCase->initTest();
    }

    /**
     * @Then /^My Ip stored in user table$/
     */
    public function myIpStoredInUserTable()
    {
        $storedIp = $this
            ->getContainer()
            ->get('security.context')
            ->getToken()->getUser()
            ->getLastloginClientIp();

        \PHPUnit_Framework_Assert::assertNotEmpty($storedIp);

        // TODO Behat access to request_stack
        // $currentIp = $this
        //     ->getContainer()
        //     ->get('request_stack')
        //     ->getCurrentRequest()
        //     ->server
        //     ->get('REMOTE_ADDR');
        // echo var_dump($currentIp);


        // \PHPUnit_Framework_Assert::assertSame(
        //     $currentIp,
        //     $storedIp,
        //     'user table stored Ip and curret Ip have to same'
        // );
    }
}
