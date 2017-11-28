<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 08.11.17
 * Time: 11:56
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Review;
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

    /**
     * @param $UUID
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOverallRatingAndCount($UUID)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('count(r.id) AS number, avg(r.rating) AS avg_rating')
            ->from('AppBundle\Entity\Review', 'r')
            ->where('r.hotel=:hotel')
            ->setParameter('hotel', $UUID);

        return
            $qb->getQuery()->getSingleResult();
    }

    /**
     * @param $UUID
     * @return \Doctrine\ORM\Query
     */
    public function getReviewQuery($UUID)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->select('r.id, r.rating, r.createdDate')
            ->from('AppBundle\Entity\Review', 'r')
            ->where('r.hotel=:hotel')
            ->setParameter('hotel', $UUID)
        ;

        return $qb->getQuery();
    }
}
