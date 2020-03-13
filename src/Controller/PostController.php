<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
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
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form->createView()
        ]);
    }
}
