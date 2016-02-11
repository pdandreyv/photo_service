<?php

namespace we\ClientBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UnitProtesterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends EntityRepository
{
    public function getSearch($val)
    {
        $res = $this->getEntityManager()
            ->createQuery("SELECT t.value, t.photo_id, p.filename, p.id 
                    FROM weClientBundle:Tag t
                    JOIN t.photo p
                    WHERE t.value like '%".$val."%'
                    GROUP BY t.photo_id
            ")
            ->getResult();
        return $res;
    }
    
}