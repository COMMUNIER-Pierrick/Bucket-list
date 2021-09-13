<?php

namespace App\Controller;

use App\Censurator\Censurator;
use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    /**
     * @Route("/wish", name="wish_list")
     */
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findPublishedWishesWithCategories();
        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
        ]);
    }

    /**
     * @Route("/wish/detail/{id}", name="wish_detail")
     */
    public function detail($id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish
        ]);
    }

    /**
     * @Route("/wish/create", name="wish_create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator
    ): Response
    {
        $wish = new Wish();

        $currentUserUsername = $this->getUser()->getPseudo();
        $wish->setAuthor($currentUserUsername);

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

            $wish->setIsPublished(1);

            $purifiedDescription = $censurator->purify($wish->getDescription());
            $wish->setDescription($purifiedDescription);

            $purifiedDescription = $censurator->purify($wish->getDescription());
            $wish->setDescription($purifiedDescription);

            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Souhait ajouter!');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            "wishForm" => $wishForm->createView()
        ]);

    }
}
