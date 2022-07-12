<?php

namespace App\Controller;

use App\Entity\Shooting;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $shootings = $doctrine->getRepository(Shooting::class)->findBy(["owner" => $this->getUser()]);
        return $this->render('profile/my_profile.html.twig', [
            'shootings' => $shootings
        ]);
    }

    /**
     * @Route("/user/{id}", name="show_user")
     */
    public function profile(): Response
    {
        return $this->render('profile/profile.html.twig', [
            
        ]);
    }
}
