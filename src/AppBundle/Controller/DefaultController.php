<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hotel;
use AppBundle\Repository\HotelRepository;
use AppBundle\Services\PaginatedRepresentation;
use AppBundle\Services\TwigRenderer;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Serializer;

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
                throw new HttpException(Response::HTTP_NOT_FOUND, 'No hotel with UUID: ' . $UUID);
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
                'exception' => $e
            ]);
        } finally {
            $response->headers->add(['Access-Control-Allow-Origin'=> '*']);
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * @Route("/rating/{UUID}", name="overall_rating")
     * @param $UUID
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function overallRatingAction($UUID, EntityManagerInterface $em)
    {
        /** @var HotelRepository $hotelRepository */
        $hotelRepository = $em->getRepository(Hotel::class);

        $hotel = $hotelRepository
            ->find($UUID);

        if (!$hotel) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'No hotel with UUID: ' . $UUID);
        }

        $data = $hotelRepository->getOverallRatingAndCount($UUID);

        return new JsonResponse($data);
    }

    /**
     * @Route("/reviews/{UUID}", name="individual_reviews")
     * @param $UUID
     * @return Response
     */
    public function individualReviewsAction($UUID, EntityManagerInterface $em, Request $request)
    {
        $outputFormat = strtolower($request->get('format', 'json'));
        if (!in_array($outputFormat, ['json', 'xml'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Output format not correct');
        }

        /** @var HotelRepository $hotelRepository */
        $hotelRepository = $em->getRepository(Hotel::class);
        $hotel = $hotelRepository
            ->find($UUID);

        if (!$hotel) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'No hotel with UUID: ' . $UUID);
        }

        $perPage    = $this->getParameter('items_per_page');
        $page       = $request->query->getInt('page', 1);

        /** @var \Doctrine\ORM\Query $query */
        $query = $hotelRepository->getReviewQuery($UUID);

        /** @var Paginator $pager */
        $pager = $this->get('knp_paginator');

        /** @var array $paginated */
        $paginated = $pager->paginate(
            $query,
            $page,
            $request->query->getInt('limit', $perPage)
        );

        /** @var PaginatedRepresentation $representationService */
        $representationService = $this->get('AppBundle\\Services\\PaginatedRepresentationBuilder');
        /** @var array $paginatedAsArray */
        $paginatedAsArray = $representationService->create($paginated, $outputFormat, $page, $UUID);

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $data = $serializer->serialize($paginatedAsArray, $outputFormat);

        /** @var Response $response */
        $response = new Response();

        $response->setContent($data);
        $response->headers->add(['Content-type' => sprintf('Application/%s', $outputFormat)]);

        return $response;
    }
}
