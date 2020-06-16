<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScrimController extends AbstractController
{
    /**
     * @Route("/scrim", name="scrim")
     */
    public function index()
    {
        return $this->render('scrim/index.html.twig', [
            'controller_name' => 'ScrimController',
        ]);
    }
}
