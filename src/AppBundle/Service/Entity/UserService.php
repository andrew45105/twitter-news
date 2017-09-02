<?php

namespace AppBundle\Service\Entity;

use AppBundle\Entity\User;
use AppBundle\Traits\SetEntityManagerTrait;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserService
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class UserService
{
    use SetEntityManagerTrait;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Creates an user
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     */
    public function create(string $username, string $email, string $password)
    {
        $user = (new User())
            ->setUsername($username)
            ->setEmail($email);

        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}