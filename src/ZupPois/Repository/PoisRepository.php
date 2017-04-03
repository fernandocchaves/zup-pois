<?php

namespace ZupPois\Repository;

use Doctrine\ORM\EntityRepository;

class PoisRepository extends EntityRepository
{
    public function getResults() {
        $queryBuilder = $this->createQueryBuilder('p');
        return $queryBuilder->getQuery()->getResult();
    }
}