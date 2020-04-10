<?php


namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function preUpdate(User $user, LifecycleEventArgs $args)
    {
        $password = $user->getPassword();
        if (empty($password)) {
            $user->setPassword($args->getOldValue('password'));
        } else {
            $encoded = $this->encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
        }
    }
}
