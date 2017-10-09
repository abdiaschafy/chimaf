<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Group;
use AppBundle\Entity\User;

/**
 * UserRepository
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @return mixed | User
     */
    public function getAccountant()
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.groups', 'g')
            ->where('g.code = :code')
            ->setParameter('code', Group::ROLE_ACCOUNTANT)
            ->getQuery()->getResult()[0];
    }
}
