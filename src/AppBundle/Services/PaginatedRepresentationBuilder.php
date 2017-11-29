<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 29.11.17
 * Time: 13:44
 */

namespace AppBundle\Services;

use AppBundle\Repository\HotelRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatedRepresentationBuilder
{
    /**
     * @var HotelRepository
     */
    private $repo;

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * @var int
     */
    private $perPage;

    /**
     * PaginatedRepresentationBuilder constructor.
     * @param RequestStack $request
     * @param HotelRepository $repository
     * @param int $perPage
     */
    public function __construct(RequestStack $request, HotelRepository $repository, int $perPage)
    {
        $this->repo = $repository;
        $this->request = $request;
        $this->perPage = $perPage;
    }

    /**
     * @param mixed $data
     * @param string $format
     * @param int $page
     * @param string $UUID
     * @return array
     */
    public function create($data, string $format, int $page, string $UUID)
    {
        $hrefPaginationBase = $this->buildUrl($format);
        $totalReviews = $this->repo->getTotalReviewCountForHotel($UUID);
        $totalPages = ceil($totalReviews / $this->perPage);

        $linksInner = [
            'self' => [
                'href' => sprintf("%s%d", $hrefPaginationBase, $page),
            ],
            'first' => [
                'href' => sprintf("%s%d", $hrefPaginationBase, 1),
            ],
            'last' => [
                'href' => sprintf("%s%d", $hrefPaginationBase, $totalPages)
            ]
        ];

        if ($page > 1) {
            $linksInner = array_merge(
                $linksInner,
                [
                    'prev' => [
                        'href' => sprintf("%s%d", $hrefPaginationBase, $page - 1),
                    ],
                ]
            );
        }

        if ($page < $totalPages - 1) {
            $linksInner = array_merge(
                $linksInner,
                [
                    'next' => [
                        'href' => sprintf("%s%d", $hrefPaginationBase, $page + 1),
                    ],
                ]
            );
        }

        $paginatedData = [
            '_links' => $linksInner,
            'count' => count($data),
            'total' => $totalReviews,
            '_embedded' => $data,
        ];

        return $paginatedData;
    }

    /**
     * @param string $format
     * @return string
     */
    protected function buildUrl(string $format) : string
    {
        $request = $this->request->getCurrentRequest();

        $schemeAndHttpHost = $request->getSchemeAndHttpHost();
        $pathInfo = $request->getPathInfo();
        $hrefWithPaginationBase = sprintf("%s%s?format=%s?page=", $schemeAndHttpHost, $pathInfo, $format);

        return $hrefWithPaginationBase;
    }
}
