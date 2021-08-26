<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function detail($id): Response
    {
        return $this->render('wish/detail.html.twig', [

        ]);
    }
}
