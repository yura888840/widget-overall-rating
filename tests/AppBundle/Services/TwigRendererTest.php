<?php

/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 08.11.17
 * Time: 13:55
 */

namespace AppBundle\Tests\Services;

use \AppBundle\Services\TwigRenderer;

class TwigRendererTest extends \PHPUnit\Framework\TestCase
{
    /** @var  TwigRenderer */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new TwigRenderer();
    }

    /**
     * @test
     */
    public function shouldRenderValidJs()
    {
        $avg = 55;

        $actual = $this->renderer->render('widget_js', 55);
        $expected = file_get_contents(__DIR__.'/fixtures/output.js');

        $this->assertEquals($expected, $actual);
    }
}