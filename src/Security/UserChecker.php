<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        // user is deleted, show a generic Account Not Found message.
        if ($user->getIsActive() == false) {
            throw new CustomUserMessageAuthenticationException('Ce compte a été désactivé');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        if ($user->getIsActive() == false) {
            throw new CustomUserMessageAuthenticationException('Ce compte a été désactivé');
        }
    }
}