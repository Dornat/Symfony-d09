<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_default")
     * @return RedirectResponse
     */
    public function index()
    {
        return $this->redirectToRoute('post_index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @Route("/change-locale", name="app_change_locale")
     */
    public function changeLocale(Request $request)
    {
        $locale = $request->getLocale();
        $supportedLocales = $this->getParameter('app.supported_locales');
        $newLocale = current(array_filter($supportedLocales, function($new) use ($locale) { return $new !== $locale; }));

        $request->setLocale($newLocale);
        $referer = $request->headers->get('referer');

        return new RedirectResponse($referer);
    }
}
