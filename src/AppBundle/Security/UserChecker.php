<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account after authentication.
     *
     * @param UserInterface $user a UserInterface instance
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }

    /**
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        if(!$user->getIsActive()){
            throw new AccountExpiredException('Votre compte est désactivé');
        }
        if(!$user->getIsActiveByAdmin()){
            throw new AccountExpiredException('Votre compte est désactivé');
        }
    }

}