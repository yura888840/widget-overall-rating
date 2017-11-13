<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hotel;
use AppBundle\Repository\HotelRepository;
use AppBundle\Services\TwigRenderer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/widget/{UUID}.js", name="widget")
     */
    public function indexAction(Request $request, $UUID, EntityManagerInterface $em)
    {
        /** @var HotelRepository $hotelRepository */
        $hotelRepository = $em->getRepository(Hotel::class);

        /** @var Response $response */
        $response = new Response();

        try {
            $hotel = $hotelRepository
                ->find($UUID);

            if (!$hotel) {
                throw new \RuntimeException('No hotel with UUID: ' . $UUID);
            }

            $avgRating = $hotelRepository->getAverageRatingWithinLastYearForHotel($UUID);

            /** @var TwigRenderer $renderer */
            $renderer = $this->get('AppBundle\\Services\\TwigRenderer');
            $content = $renderer->render('widget_js', $avgRating);

            $response->setCache(['max_age' => 3600]);
        } catch (\Exception $e) {
            $content = '';
            /** LoggerInterface */
            $logger = $this->get('logger');
            $logger->critical('An error occurred while running banner application', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'exception' => serialize($e)
            ]);
        } finally {
            $response->headers->add(['Access-Control-Allow-Origin'=> '*']);
            $response->setContent($content);
        }

        return $response;
    }
}
