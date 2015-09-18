<?php

/**
 * This file is part of the LoginBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netpositive\LoginBundle\Security\User\Provider;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * customized OAuthUserProvider.
 *
 * @author zsolt.k
 */
class OAuthUserProvider extends BaseClass
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userId = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $userId));

        if (null === $user || !$user instanceof UserInterface) {
            // register user in FOSUserBundle
            $user = $this->userManager->createUser();

            $user->setEmail($response->getEmail());
            $user->setFullName($response->getRealName());
            $user->setPassword('');
            $user->setEnabled(true);

            $user->setFacebookId($userId);
            $user->setFacebookAccessToken($response->getAccessToken());

            $this->userManager->updateUser($user);
        } else {
            $checker = new UserChecker();
            $checker->checkPreAuth($user);
        }

        return $user;
    }
}
