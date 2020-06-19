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
        $allPlayers = $em->getRepository(Player::class)->findAll();
        $search = $request->get('homeSearchPlayer');
        $searchPlayer = $em->getRepository(Player::class)->searchPlayer($search);
        return $this->render('main/index.html.twig', [
            'searchPlayer' => $searchPlayer,
            'allPlayers' => $allPlayers,
        ]);
    }
}
