<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        $user = $this->getUser();

        return new Response(
            $this->renderView('security/login.html.twig', [])
        );
    }

    /**
     * @Route("/login/view", name="app_login_view", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function loginView(Request $request)
    {
        return new Response($this->renderView('security/login.html.twig', ['error' => false, 'last_username' => null]));
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
