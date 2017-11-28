<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testOverallCountEndpointOnNonExistsHotel()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rating/HOTEL_HASH_TEST1');
        $this->assertEquals(500, $client->getResponse()->getStatusCode());

    }

    public function testOverallCountEndpoint()
    {
        $client = static::createClient();

        $client->request('GET', '/rating/HOTEL_HASH_TEST');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('{"number":"5","avg_rating":"60.2000"}', $client->getResponse()->getContent());
    }

    public function testFetchIndividualReviewsWithoutPageJson()
    {
        $client = static::createClient();

        $client->request('GET', '/reviews/HOTEL_HASH_TEST?format=json');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('{"review":[{"id":1,"rating":55,"createdDate":"2017-01-01T00:00:01+00:00"},{"id":3,"rating":75,"createdDate":"2017-05-01T08:30:01+00:00"},{"id":4,"rating":45,"createdDate":"2017-08-01T08:30:01+00:00"}]}', $client->getResponse()->getContent());
    }

    public function testFetchIndividualReviewsPageTwoJsonOutput()
    {
        $client = static::createClient();

        $client->request('GET', '/reviews/HOTEL_HASH_TEST?format=json&page=2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('{"reviews":{"review":[{"id":5,"rating":38,"createdDate":"2017-08-23T08:30:01+00:00"},{"id":6,"rating":88,"createdDate":"2016-08-23T08:30:01+00:00"}]}}', $client->getResponse()->getContent());
    }

    public function testFetchIndividualReviewsPageTwoXMLOutput()
    {
        $client = static::createClient();

        $client->request('GET', '/reviews/HOTEL_HASH_TEST?format=xml&page=2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('<response><reviews><review><id>5</id><rating>38</rating><createdDate>2017-08-23T08:30:01+00:00</createdDate></review><review><id>6</id><rating>88</rating><createdDate>2016-08-23T08:30:01+00:00</createdDate></review></reviews></response>', $client->getResponse()->getContent());
    }

    public function testFetchReviewsWithoutPageWithIncorrectOutputparamShouldOutputJson()
    {
        $client = static::createClient();

        $client->request('GET', '/reviews/HOTEL_HASH_TEST?format=some_format');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('{"reviews":{"review":[{"id":1,"rating":55,"createdDate":"2017-01-01T00:00:01+00:00"},{"id":3,"rating":75,"createdDate":"2017-05-01T08:30:01+00:00"},{"id":4,"rating":45,"createdDate":"2017-08-01T08:30:01+00:00"}]}}', $client->getResponse()->getContent());
    }
}
