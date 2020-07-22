<?php

namespace App\Controller;

use App\Entity\Scrim;
use App\Form\ScrimType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ScrimController extends AbstractController
{
    /**
     * @Route("/scrim", name="scrim")
     */
    public function index(EntityManagerInterface $em)
    {
        $upcomingScrims = $em->getRepository(Scrim::class)->findBy(array(), array('scrimDate' => 'DESC'), 5);
        return $this->render('scrim/index.html.twig', [
            'upcomingScrims' => $upcomingScrims,
        ]);
    }

    /**
     * @Route("/scrim/addScrim", name="addScrim")
     */
    public function addScrim(EntityManagerInterface $em, Request $request)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $scrim = new Scrim();
        $addScrimForm = $this->createForm(ScrimType::class);
        $addScrimForm->handleRequest($request);

        if ($addScrimForm->isSubmitted() && $addScrimForm->isValid()){
            $em->persist($scrim);
            $em->flush();
            $this->addFlash("success", "Your scrim has been created");
            return $this->redirectToRoute('scrimDetail', array('id'=>$scrim->getId()));
        }
        return $this->render('scrim/addScrim.html.twig', [
            'addScrimForm' => $addScrimForm->createView()
        ]);
    }

    /**
     * @Route("/scrimDetail", name="scrimDetail")
     */
    public function scrimDetail(EntityManagerInterface $em)
    {

    }
}
