<?php

/**
 * Created by PhpStorm.
 * User: yuri
 * Date: 08.11.17
 * Time: 13:50
 */

namespace AppBundle\Services;

class TwigRenderer
{
    private $twig;

    public function __construct()
    {
        $templateDirectory = __DIR__.'/../../../app/Resources/template/';
        $loader = new \Twig_Loader_Filesystem($templateDirectory);
        $this->twig = new \Twig_Environment($loader);
    }

    public function render(string $template, int $avgRate): string
    {
        $rendered =
            $this->twig->render(
                sprintf("%s.twig", $template),
                [
                    'avg_rate' => $avgRate
                ]
            );

        return $rendered;
    }

}