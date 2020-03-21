<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    /**
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return User
     */
    protected function getUser(): User
    {
        /** @var User $user */
        $user = parent::getUser();

        return $user;
    }
}