<?php
/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 29.11.17
 * Time: 18:33
 */

namespace AppBundle\Tests\Services;

use AppBundle\Repository\HotelRepository;
use AppBundle\Services\PaginatedRepresentationBuilder;
use Symfony\Component\HttpFoundation\Request;

class PaginatedRepresentationBuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PaginatedRepresentationBuilder
     */
    private $builder;

    CONST PER_PAGE = 3;

    CONST TOTAL_RECORDS = 10;

    public function setUp()
    {
        $request = $this
            ->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository = $this
            ->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository
            ->expects($this->any())
            ->method('getTotalReviewCountForHotel')
            ->withAnyParameters()
            ->will($this->returnValue(self::TOTAL_RECORDS))
        ;

        $this->builder = new PaginatedRepresentationBuilder($request, $repository, self::PER_PAGE);
    }

    public function dummyTest()
    {
        $this->assertEquals(true, true);
    }

}