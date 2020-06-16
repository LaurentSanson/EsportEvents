<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    /**
     * @Route("/platform", name="platform")
     */
    public function index()
    {
        return $this->render('platform/index.html.twig', [
            'controller_name' => 'PlatformController',
        ]);
    }
}
