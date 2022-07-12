<?php

namespace App\Controller;

use SearchForm;
use App\Data\SearchData;
use App\Entity\Shooting;
use App\Repository\ShootingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, ShootingRepository $sr, Request $request, PaginatorInterface $paginator): Response
    {
        $dataSearch = new SearchData();
        $formSearch = $this->createForm(SearchForm::class, $dataSearch);
        $formSearch->handleRequest($request);

        $data = $sr->findSearch($dataSearch);
        // shuffle($data);
        $shootings = $paginator->paginate($data, $request->query->getInt("page", 1), 8);

        return $this->render('home/index.html.twig', [
            'shootings' => $shootings,
            'formSearch' => $formSearch->createView()
        ]);
    }
}
