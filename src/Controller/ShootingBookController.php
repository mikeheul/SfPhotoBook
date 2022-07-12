<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShootingBookController extends AbstractController
{
    /**
     * @Route("/shooting/book", name="app_shooting_book")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        return $this->render('shooting_book/index.html.twig', [
            
        ]);
    }
}
