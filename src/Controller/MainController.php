<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(EntityManagerInterface $em, Request $request)
    {
        $search = $request->get('homeSearch');
        $searchPlayer = $em->getRepository(Player::class)->searchPlayer($search);
        return $this->render('main/index.html.twig', [
            'searchPlayer' => $searchPlayer

        ]);
    }
}
