<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     * @param Request $request
     * @return Response
     */
    public function test(Request $request)
    {
        dump($request->getLocale());
        return new Response($this->renderView('test/index.html.twig'));
    }
}
