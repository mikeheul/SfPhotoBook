<?php

namespace App\Controller;

use App\Entity\ShootingBook;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShootingBookController extends AbstractController
{
    /**
     * @Route("/shooting/book", name="app_shooting_book")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);

        $requests = [];
        foreach($user->getShootings() as $shooting) {
            foreach($shooting->getShootingBooks() as $shootingBook) {
                $requests[] = $shootingBook;
            }
        }
        usort($requests, function($a, $b) {
            if ($a->getCreatedAt() == $b->getCreatedAt())
                return (0);
            return (($a->getCreatedAt() < $b->getCreatedAt()) ? 1 : -1);
        });

        $rdvs = [];
        foreach($user->getShootings() as $shooting) {
            foreach($shooting->getShootingBooks() as $shootingBook) {
                if($shootingBook->isIsAccepted()) {
                    $rdvs[] = [
                        'id' => $shootingBook->getId(),
                        'title' => $shootingBook->getShooting()->getTitle(),
                        'start' => $shootingBook->getStartDate()->format('Y-m-d H:i'),
                        'end' => ($shootingBook->getEndDate() !== null) ? $shootingBook->getEndDate()->format('Y-m-d H:i'): "",
                        'message' => $shootingBook->getMessage(),
                        'allDay' => true
                    ];
                }
            }
        }

        array_push($rdvs, [
            'start' => '2022-07-15',
            'end' => '2022-07-20',
            'display' => 'background',
            'color' => 'red',
        ]);

        $data = json_encode($rdvs);
        return $this->render('shooting_book/index.html.twig', 
            compact('data', 'requests')
        );
    }

    /**
     * @Route("/shooting/book/accept/{id}", name="app_shooting_book_accept")
     */
    public function accept(ManagerRegistry $doctrine, ShootingBook $shootingBook): Response
    {
        $em = $doctrine->getManager();
        $shootingBook->setIsAccepted(true);
        $em->flush();

        return $this->redirectToRoute('app_shooting_book');
    }
}
