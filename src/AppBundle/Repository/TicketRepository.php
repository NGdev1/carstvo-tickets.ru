<?php

namespace AppBundle\Repository;

use AppBundle\Entity\PriceCategory;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\UserApplication;

/**
 * TicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param UserApplication $userApplication
     * @param PriceCategory $priceCategory
     * @return Ticket
     */
    public function findInApplicationWithCategory(UserApplication $userApplication, PriceCategory $priceCategory)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t
                FROM AppBundle:Ticket t
                WHERE t.application = :app
                AND t.priceCategory = :category
            ')
            ->setParameter('app', $userApplication)
            ->setParameter('category', $priceCategory)
            ->getSingleResult();
    }
}
