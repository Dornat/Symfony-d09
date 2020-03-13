<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route(
 *     "/{_locale}/page",
 * )
 */
class PostController extends AbstractController
{

    /**
     * @Route("/", name="post_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
