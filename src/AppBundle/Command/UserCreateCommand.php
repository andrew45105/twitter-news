<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UserCreateCommand
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class UserCreateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:user-create')
            ->setDescription('Creates an user');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = $this->getContainer()->getParameter('project_user');

        /* @var User */
        $user = $this->getContainer()->get('app.service.entity.user')->create(
            $userData['username'],
            $userData['email'],
            $userData['password']
        );

        $output->writeln(
            sprintf(
                '<fg=blue;bg=white>User %s (%s) was successfully created!</>',
                $user->getUsername(),
                $user->getEmail()
            )
        );
    }
}