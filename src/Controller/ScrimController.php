<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Scrim;
use App\Entity\Team;
use App\Form\ScrimType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $user = $this->getUser();
        $player = $em->getRepository(Player::class)->find($user);
        $team = $player->getTeam();
        $addScrimForm = $this->createForm(ScrimType::class, $scrim);
        $addScrimForm->handleRequest($request);

        if ($addScrimForm->isSubmitted() && $addScrimForm->isValid()) {
            $photo = $addScrimForm->get('logo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                try {
                    $photo->move(
                        $this->getParameter('logoScrim_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $scrim->setLogo($newFilename);
            }

            $today = new \DateTime('now');
            if ($scrim->getScrimDate() < $today) {
                $this->addFlash('danger', 'Your Scrim can\'t be before today');
            } elseif ($scrim->getScrimlimitRegistrationDate() > $scrim->getScrimDate() && $scrim->getScrimlimitRegistrationDate() < $today) {
                $this->addFlash('danger', 'Your Scrim limit registration date can\'t be after the scrim date');
            } else {
                $scrim->setNbMaxTeams(2);
                $scrim->addPlayer($player);
                $scrim->addTeam($team);
                $em->persist($scrim);
                $em->flush();
                $this->addFlash("success", "Your scrim has been created");
                return $this->redirectToRoute('scrimDetail', array('id' => $scrim->getId()));
            }
        }
        return $this->render('scrim/addScrim.html.twig', [
            'addScrimForm' => $addScrimForm->createView()
        ]);
    }


    /**
     * @Route("/scrimDetail/{id}", name="scrimDetail")
     */
    public function scrimDetail(EntityManagerInterface $em, $id)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you are not connected");
            return $this->redirectToRoute('main');
        }
        $scrim = $em->getRepository(Scrim::class)->find($id);
        $teams = $scrim->getTeams();

        return $this->render('scrim/scrimDetail.html.twig', [
            'scrim' => $scrim,
            'teams' => $teams
        ]);
    }

    /**
     * @Route("scrim/register/{id}", name="scrimRegister")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function scrimRegister($id, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You can't acces this page if you're not connected !");
            return $this->redirectToRoute('main');
        }
        $user = $this->getUser();
        $team = $entityManager->getRepository(Player::class)->find($user)->getTeam();
        $scrim = $entityManager->getRepository(Scrim::class)->find($id);
        $teams = $scrim->getTeams()->toArray();
        $numberOfTeamsRegistered = $scrim->getTeams()->count();
        if ($numberOfTeamsRegistered == $scrim->getNbMaxTeams()) {
            $this->addFlash('danger', "There are already two teams registered for this Scrim");
            return $this->redirectToRoute('scrimDetail', ['id' => $id]);
        } elseif (in_array($team, $teams)) {
            $this->addFlash('danger', "You are already registered for this Scrim");
            return $this->redirectToRoute('scrimDetail', ['id' => $id]);
        } else {
            $scrim->addTeam($team);
            $entityManager->flush();
        }
        $this->addFlash("success", "You are registered for this Scrim");
        return $this->redirectToRoute('scrimDetail', ['id' => $id]);
    }
}
