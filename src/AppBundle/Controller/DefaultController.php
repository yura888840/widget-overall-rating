<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hotel;
use AppBundle\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/widget/{UUID}.js", name="widget")
     */
    public function indexAction(Request $request, $UUID, EntityManagerInterface $em)
    {
        /** @var HotelRepository $hotelRepository */
        $hotelRepository = $em->getRepository(Hotel::class);
        $hotel = $hotelRepository
            ->find($UUID);

        if (!$hotel) {
            throw new \RuntimeException('No hotel with UUID: ' . $UUID);
        }

        try {
            $avgRating = $hotelRepository->getAverageRatingWithinLastYearForHotel($UUID);
        } catch (\Exception $e) {
            throw new \RuntimeException('Database error. (Need to handle it properly, but not implemented)');
        }

        return $this->render('default/index.html.twig', [
            'avg_rate' => $avgRating
        ]);
    }
}
