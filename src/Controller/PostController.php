<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
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
     * @Route("/content", name="post_content")
     * @return JsonResponse
     */
    public function content()
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $em = $this->getEntityManager();
        $posts = $em->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC']);

        return new JsonResponse(
            $this->renderView('post/_content.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts,
            ])
        );
    }

    /**
     * @Route("/content/{id}", name="post_content_specific")
     * @param Post $post
     * @return JsonResponse
     */
    public function contentSpecific(Post $post)
    {
        return new JsonResponse(
            $this->renderView('post/_post.html.twig', [
                'post' => $post
            ])
        );
    }

    /**
     * @Route("/form-view", name="post_form_view")
     * @return JsonResponse
     */
    public function formView()
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        return new JsonResponse(
            $this->renderView('post/_form.html.twig', [
                'form' => $form->createView()
            ])
        );
    }

    /**
     * @Route("/new", name="post_new", methods={"POST"})
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return JsonResponse
     */
    public function new(Request $request, PublisherInterface $publisher)
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

        $update = new Update(
            'new',
            json_encode(['id' => $post->getId(), 'type' => 'new'])
        );
        $publisher($update);

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

    /**
     * @Route("/delete/{id}", name="post_delete", methods={"POST"})
     * @param Post $post
     * @param PublisherInterface $publisher
     * @return JsonResponse
     */
    public function delete(Post $post, PublisherInterface $publisher)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $postId = $post->getId();

        $em = $this->getEntityManager();
        $em->remove($post);
        $em->flush();

        $update = new Update(
            'delete',
            json_encode(['id' => $postId, 'type' => 'delete'])
        );
        $publisher($update);

        return new JsonResponse(['status' => 'ok', 'post_id' => $postId]);
    }

    /**
     * @Route("/test", name="post_test", methods={"POST"})
     * @param PublisherInterface $publisher
     * @return JsonResponse
     */
    public function test(PublisherInterface $publisher)
    {
        $update = new Update(
            $this->generateUrl('post_test'),
            json_encode(['status' => 'hello'])
        );

        $publisher($update);

        return new JsonResponse('ok');
    }
}
