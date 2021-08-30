<?php

namespace App\Controller;

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
        $wishs = $wishRepository->findTheMostRecent();
        return $this->render('wish/list.html.twig', [
            "wishs" => $wishs
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
        EntityManagerInterface $entityManager
    ): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setIsPublished(true);

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

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
