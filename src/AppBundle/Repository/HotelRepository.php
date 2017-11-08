<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 08.11.17
 * Time: 11:56
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class HotelRepository extends EntityRepository
{
    /**
     * @param string $UUID
     * @return int
     */
    public function getAverageRatingWithinLastYearForHotel($UUID)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('avg(r.rating)')
            ->from('AppBundle\Entity\Review', 'r')
            ->where('r.hotel=:hotel and r.createdDate > :date')
            ->setParameter('hotel', $UUID)
            ->setParameter('date', new \DateTime('-1 year'));

        return
            (int) round($qb->getQuery()->getSingleScalarResult());
    }
}
