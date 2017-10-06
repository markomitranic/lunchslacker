<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\DocumentRepository;


class OrderRepository extends DocumentRepository
{
    public function findByUserAndDay($user, $day) {
        return $this
            ->createQueryBuilder()
            ->field('user')->references($user)
            ->getQuery()
            ->execute();
    }
}