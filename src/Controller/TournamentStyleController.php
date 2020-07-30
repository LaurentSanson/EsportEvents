<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TournamentStyleController extends AbstractController
{
    /**
     * @Route("/tournament/type", name="tournament_style")
     */
    public function index()
    {
        return $this->render('tournament_style/index.html.twig', [
            'controller_name' => 'TournamentStyleController',
        ]);
    }
}
