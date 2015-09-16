<?php

namespace Netpositive\UtilBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Symfony\Component\BrowserKit\Cookie;
use FOS\UserBundle\Command\CreateUserCommand;
use FOS\UserBundle\Command\PromoteUserCommand;

/**
 * Symfony2 PHPUnit test helper functions DBinit, loadfixture, create user...
 * ideas from:.
 *
 * @link http://intelligentbee.com/blog/2013/08/15/symfony2-jobeet-day-9-the-functional-tests/
 *
 * @author extdevelopment
 */
trait TestHelperTrait
{
    private $em;
    private $application;

    /**
     * create test db.
     */
    public function initDB()
    {
        static::bootKernel();

        $this->application = new Application(static::$kernel);
        $this->em = $this->getEntityManager();

        // drop the database
        $command = new DropDatabaseDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ));
        $command->run($input, new NullOutput());

        // we have to close the connection after dropping the database so we don't get "No database selected" error
        $connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();
        if ($connection->isConnected()) {
            $connection->close();
        }

        // create the database
        $command = new CreateDatabaseDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:create',
        ));
        $command->run($input, new NullOutput());

        // create schema
        $command = new CreateSchemaDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:create',
        ));
        $command->run($input, new NullOutput());
    }

    /**
     * @param array $fixtures optional fixtures location if not set will load all fixtures
     *                        example:
     *                        array(
     *                        "@ExtdevelopmentJobExamBundle/DataFixtures/ORM/LoadCustomerData",
     *                        "@ExtdevelopmentJobExamBundle/DataFixtures/ORM/LoadProductData",
     *                        )
     */
    public function loadFixture(array $fixtures = array())
    {
        if (empty($fixtures)) {
            // load fixture
            $command = new LoadDataFixturesDoctrineCommand();

            $this->application->add($command);
            $input = new ArrayInput(array(
                'command' => 'doctrine:fixtures:load --append',
            ));
            $input->setInteractive(false);

            $command->run($input, new NullOutput());

            return;
        }

        // load fixtures specified location
        $loader = new \Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader(static::$kernel->getContainer());

        foreach ($fixtures as $oneFixture) {
            $loader->loadFromFile(static::$kernel->locateResource($oneFixture.'.php'));
        }

        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * wait For Press Enter (pause test).
     */
    public function waitForPressEnter()
    {
        if (trim(fgets(fopen('php://stdin', 'r'))) != chr(13)) {
            return;
        }
    }

    /**
     * write To Consol Var (debug helper).
     *
     * @param mixed $var
     */
    public function writeToConsolVar($var)
    {
        fwrite(STDERR, print_r(var_dump($var), true));
    }

    /**
     * @param string $username username
     *
     * @see https://advancingusability.wordpress.com
     * /2013/11/15/functional-testing-with-authentication-and-symfony-2-3-fosuserbundle/
     *
     * @return Client A Client instance
     */
    public function createAuthorizedClient($username)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => $username));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set(
            '_security_'.$firewallName,
            serialize($container->get('security.context')->getToken())
        );
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    /**
     * @param string $username username
     * @param string $password password
     */
    protected function createFOSUser($username, $password)
    {
        $command = new CreateUserCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'fos:user:create',
            'username' => $username,
            'password' => $password,
            'email' => $username.'@example.com',
        ));
        $input->setInteractive(false);

        $command->run($input, new NullOutput());
    }

    /**
     * @param string $username username
     * @param string $role     role
     */
    protected function addRoleFOSuser($username, $role)
    {
        $command = new PromoteUserCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'fos:user:promote',
            'username' => $username,
            'role' => $role,
        ));
        $input->setInteractive(false);

        $command->run($input, new NullOutput());
    }

    /**
     * get EntityManager.
     *
     * @return EntityManager instance of EntityManager
     */
    public function getEntityManager()
    {
        return static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
