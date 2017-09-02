<?php

namespace AppBundle\Traits;

use Doctrine\ORM\EntityManager;

/**
 * Class SetEntityManagerTrait
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
trait SetEntityManagerTrait
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}