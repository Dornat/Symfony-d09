<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route(
 *     "/{_locale}/post",
 * )
 */
class PostController extends BaseController
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

        $em = $this->getEntityManager();
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC']);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                $this->renderView('post/index.html.twig', [
                    'form' => $form->createView(),
                    'posts' => $posts,
                ])
            );
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);

        if (is_null($data)) {
            throw new BadRequestHttpException('Invalid JSON.');
        }

        $form = $this->createForm(PostType::class, null);

        $form->submit($data);

        if (!$form->isValid()) {
            $error = $this->getErrorsFromForm($form);
            return $this->createApiResponse($error, 400);
        }

        /** @var Post $post */
        $post = $form->getData();
        $post->setAuthor($this->getUser());

        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();

        return new JsonResponse('ok');
    }

    /**
     * @Route("/view/{id}", name="post_view", methods={"POST"})
     * @param Post $post
     * @return JsonResponse
     */
    public function view(Post $post)
    {
        return new JsonResponse(
            $this->renderView('post/details.html.twig', [
                'post' => $post,
            ])
        );
    }
}
