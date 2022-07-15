<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Package;
use App\Entity\Shooting;
use App\Form\PackageType;
use App\Form\ShootingType;
use App\Entity\ShootingBook;
use App\Entity\ShootingLike;
use App\Entity\ShootingImages;
use App\Entity\ShootingComments;
use App\Form\ShootingCommentType;
use App\Form\ShootingBookFormType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ShootingLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ShootingController extends AbstractController
{
    
    /**
     * @Route("/shooting/add", name="add_shooting")
     * @Route("/shooting/{id}/edit", name="edit_shooting")
     */
    public function add(FlashyNotifier $flashy, ManagerRegistry $doctrine, Shooting $shooting = null, Request $request, SluggerInterface $slugger): Response
    {
        // dd($this->isGranted("ROLE_ADMIN"));
        $edit = true;
        if($shooting && $shooting->getOwner() == $this->getUser() || $this->isGranted("ROLE_ADMIN")) {
            
            $entityManager = $doctrine->getManager();
            if(!$shooting) {
                $shooting = new Shooting();
                $edit = false;
            }

            $form = $this->createForm(ShootingType::class, $shooting);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $images = $form->get('shootingImages')->getData();
                
                foreach($images as $image) {
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory'),
                        $fichier
                    );

                    $shootingImage = new ShootingImages();
                    $shootingImage->setUrl($fichier);
                    $shooting->addShootingImage($shootingImage);
                }

                $shooting->setOwner($this->getUser());
                $shooting->setCreatedAt(new \DateTime());
                $entityManager->persist($shooting);
                $entityManager->flush();

                $confirm = ($edit) ? "updated" : "added";
                $flashy->success("Shooting well ". $confirm. " !");

                return $this->redirectToRoute('show_shooting', ['id' => $shooting->getId()]);
            }
        } else {
            $flashy->error("Not allowed !");
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('shooting/add.html.twig', [
            'formAddShooting' => $form->createView(),
            'shooting' => $shooting,
            'edit' => $shooting->getId()
        ]);
    }

    /**
     * @Route("delete/image/{id}", name="delete_image_shooting")
     */
    public function deleteImage(FlashyNotifier $flashy, ManagerRegistry $doctrine, ShootingImages $shootingImage, Request $request) {
        
        if($shootingImage->getShooting()->getOwner() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        } else {
            $nom = $shootingImage->getUrl();
            unlink($this->getParameter('images_directory').'/'.$nom);
            $em = $doctrine->getManager();
            $em->remove($shootingImage);
            $em->flush();

            $flashy->success('Image deleted successfully !');
    
            return $this->redirectToRoute('show_shooting', ['id' => $shootingImage->getShooting()->getId()]);
        }
    }

    /**
     * @Route("add_package/{id}", name="add_package_shooting")
     * @Route("edit_package/{shooting_id}/{package_id}", name="edit_package_shooting")
     * @ParamConverter("package", options={"mapping": {"package_id": "id"}})
     * @ParamConverter("shooting", options={"mapping": {"shooting_id": "id"}})
     */
    public function addPackage(FlashyNotifier $flashy, ManagerRegistry $doctrine, Shooting $shooting, Package $package = null, Request $request) {

        if($shooting->getOwner() !== $this->getUser()) {
            $flashy->error('Unauthorized action !');
            return $this->redirectToRoute('app_home');
        } else {

            if(!$package){
                $package = new Package();
            }
            $form = $this->createForm(PackageType::class, $package);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $package = $form->getData();
                $package->setShooting($shooting);
                $em = $doctrine->getManager();
                $em->persist($package);
                $em->flush();

                return $this->redirectToRoute('show_shooting', ['id' => $shooting->getId()]);
            }

            return $this->render('shooting/add_package_shooting.html.twig', [
                "formAddPackageShooting" => $form->createView(),
                "shooting" => $shooting,
                "editMode" => $package->getId()
            ]);
        }
    }

    /**
     * @Route("delete_package/{id}", name="delete_package_shooting")
     */
    public function deletePackage(FlashyNotifier $flashy, ManagerRegistry $doctrine, Package $package) {

        if($package->getShooting()->getOwner() === $this->getUser()) {
            $shootingBooks = $doctrine->getRepository(ShootingBook::class)->findBy(['package' => $package]);
            $em = $doctrine->getManager();
            foreach($shootingBooks as $shootingBook) {
                $shootingBook->setPackage(null);
            }
            $em->remove($package);
            $em->flush();
    
            return $this->redirectToRoute('show_shooting', ['id' => $package->getShooting()->getId()]);
        } else {
            $flashy->error('Unauthorized action !');
            return $this->redirectToRoute('app_home');
        }
    }

    /**
     * @Route("shooting/comment/delete/{id}", name="delete_shooting_comment")
     */
    public function deleteComment(FlashyNotifier $flashy, ManagerRegistry $doctrine, ShootingComments $comment)
    {
        if($comment->getUserComment() === $this->getUser()) {
            $em = $doctrine->getManager();
            $em->remove($comment);
            $em->flush();
    
            return $this->redirectToRoute('show_shooting', ['id' => $comment->getShootingComment()->getId()]);
        } else {
            $flashy->error('Unauthorized action !');
            return $this->redirectToRoute('app_home');
        }
    }

    /**
     * @Route("/shooting/{id}", name="show_shooting")
     */
    public function show(ManagerRegistry $doctrine, Shooting $shooting, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        
        $shootingComment = new ShootingComments();
        $shootingBook = new ShootingBook();

        $form = $this->createForm(ShootingCommentType::class, $shootingComment);
        $formBooking = $this->createForm(ShootingBookFormType::class, $shootingBook, ['id' => $shooting->getId()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $shootingComment = $form->getData();
            $shootingComment->setCreatedAt(new \DateTime());
            $shootingComment->setUserComment($this->getUser());
            $shootingComment->setShootingComment($shooting);

            $entityManager->persist($shootingComment);
            $entityManager->flush();

            return $this->redirectToRoute('show_shooting', ['id' => $shooting->getId()]);
        }

        $formBooking->handleRequest($request);
        if($formBooking->isSubmitted() && $formBooking->isValid()) {
            $shootingBook = $formBooking->getData();
            $shootingBook->setCreatedAt(new \DateTime());
            $shootingBook->setBookUser($this->getUser());
            $shootingBook->setShooting($shooting);
            $shootingBook->setIsAccepted(false);

            $package = $formBooking->get('packages')->getData();
            $shootingBook->setPackage($package);

            $entityManager->persist($shootingBook);
            $entityManager->flush();

            return $this->redirectToRoute('show_shooting', ['id' => $shooting->getId()]);
        }
        
        return $this->render('shooting/show.html.twig', [
            'shooting' => $shooting,
            'formShootingComments' => $form->createView(),
            'formShootingBook' => $formBooking->createView(),
        ]);
    }

    /**
     * @Route("shooting/{id}/like", name="shooting_like")
     */
    public function like(FlashyNotifier $flashy, Shooting $shooting, ManagerRegistry $doctrine, ShootingLikeRepository $sr): Response
    {
        $em = $doctrine->getManager();
        $user = $this->getUser();

        if(!$user) return $this->json([
            'code' => 403,
            'message' => $flashy->error('You have to login !')
        ], 403);

        if($shooting->isLikedByUser($user)) {
            $like = $sr->findOneBy([
                'shooting' => $shooting,
                'user' => $user
            ]);

            $em->remove($like);
            $em->flush();


            return $this->json([
                'code' => 200,
                // 'message' => $flashy->success('Like well deleted !'),
                'message' => 'Like well deleted !',
                'likes' => $sr->count(['shooting' => $shooting])
            ], 200);
        }

        $like = new ShootingLike();
        $like->setShooting($shooting)
             ->setUser($user);
        
        $em->persist($like);
        $em->flush();

        return $this->json([
            'code' => 200, 
            // 'message' => $flashy->success('Like added successfully !'),
            'message' => 'Like added successfully !',
            'likes' => $sr->count(['shooting' => $shooting])
        ], 200);
    }

    /**
     * @Route("shooting/{id}/active", name="active_shooting")
     */
    public function active(Shooting $shooting, ManagerRegistry $doctrine, ShootingLikeRepository $sr): Response
    {
        if($shooting->isIsActive()) {
            $shooting->setIsActive(false);
        } else {
            $shooting->setIsActive(true);
        }
        $doctrine->getManager()->flush();
        
        return $this->json([
            'code' => 200,
            'message' => 'Shooting updated',
            'isActive' => $shooting->isIsActive()
        ], 200);
    }
}
