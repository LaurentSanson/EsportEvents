<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\TeamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TeamController extends AbstractController
{
    /**
     * @Route("/team", name="team")
     */
    public function index(EntityManagerInterface $em, Request $request)
    {
        $teams = $em->getRepository(Team::class)->findAll();
        return $this->render('team/index.html.twig', [
            'teams' => $teams
        ]);
    }

    /**
     * @Route("/addTeam", name="addTeam")
     */
    public function addTeam(EntityManagerInterface $em, Request $request)
    {
        if (!$this->getUser()){
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $team = new Team();
        $user = $this->getUser();
        $player = $em->getRepository(Player::class)->find($user);

        $teamForm = $this->createForm(TeamType::class, $team);
        $teamForm->handleRequest($request);

        if ($teamForm->isSubmitted() && $teamForm->isValid()){
//            $teamName = $team->getName();
//            $testTeam = $em->getRepository(Team::class)->findByName($teamName);
//            if ($testTeam->getId() == $team->getId()){
//                $testTeam = null;
//            }
//            if ($testTeam != null) {
//                $this->addFlash("alert-danger", "This Team name is already used");
//            } else {
            $team->addPlayer($player);
                $em->persist($team);
                $em->flush();
                $this->addFlash("success", "Welcome to your Team !");
                return $this->redirectToRoute('teamDetail', array('id'=>$team->getId()));
//            }

        }
        return $this->render('team/addTeam.html.twig', [
            'teamForm' => $teamForm->createView(),

        ]);
    }

    /**
     * @Route("/teamDetail/{id}", name="teamDetail")
     */
    public function teamDetail(EntityManagerInterface $em, $id, Request $request)
    {
        if (!$this->getUser()){
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $team = $em->getRepository(Team::class)->find($id);
        $players = $team->getPlayers();

        return $this->render("team/teamDetail.html.twig",
            [
                "team" => $team,
                'players' => $players,

            ]
        );
    }

    /**
     * @Route("/searchPlayer", name="searchPlayer", methods="GET")
     */
    public function searchPlayer(EntityManagerInterface $em, Request $request)
    {
        $search = $request->get('searchPlayer');
        $searchPlayer = $em->getRepository(Player::class)->searchPlayer($search);
        return $this->json($searchPlayer, 200, [], ['groups'=>'group1']);
    }

    /**
     *
     * @Route("/deleteTeam/{id}", name="deleteTeam")
     */
    public function deleteTeam(EntityManagerInterface $em, $id)
    {
        if (!$this->getUser()){
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }
        $team = $em->getRepository(Team::class)->find($id);

        $em->remove($team);
        $em->flush();
        $this->addFlash("success", "You're team has been deleted !");
        $teams = $em->getRepository(Team::class)->findAll();


        return $this->render("team/index.html.twig",
            [
                "teams" => $teams
            ]
        );
    }

}
