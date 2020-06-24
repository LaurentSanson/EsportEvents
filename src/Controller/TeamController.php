<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Form\AddPlayerToTeamType;
use App\Form\TeamType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class TeamController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

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
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $team = new Team();
        $user = $this->getUser();
        $player = $em->getRepository(Player::class)->find($user);

        $teamForm = $this->createForm(TeamType::class, $team);
        $teamForm->handleRequest($request);

        if ($teamForm->isSubmitted() && $teamForm->isValid()) {
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
            return $this->redirectToRoute('teamDetail', array('id' => $team->getId()));
//            }

        }
        return $this->render('team/addTeam.html.twig', [
            'teamForm' => $teamForm->createView(),

        ]);
    }

    /**
     * @Route("/teamDetail/{id}", name="teamDetail")
     */
    public function teamDetail(EntityManagerInterface $em, $id, Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $team = $em->getRepository(Team::class)->find($id);
        $players = $team->getPlayers();

        $addPlayerToTeamForm = $this->createForm(AddPlayerToTeamType::class);
        $addPlayerToTeamForm->handleRequest($request);

        if ($addPlayerToTeamForm->isSubmitted() && $addPlayerToTeamForm->isValid()) {
            $selectedPlayer = $request->get('selectNicknames');
            $playerAddedToTeam = $em->getRepository(Player::class)->findOneBy(['nickname' => $selectedPlayer]);

            $token = $tokenGenerator->generateToken();
            try {
                $playerAddedToTeam->setResetToken($token);
                $em->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('teamDetail', array('id' => $team->getId()));
            }
            $url = $this->generateUrl('playerConfirmJoinTeam', array('token' => $token, 'id' => $id), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('A Team wants to recruit you !'))
                ->setFrom('teamRecruitment@esportevents.com')
                ->setTo($playerAddedToTeam->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/teamRecruitment.html.twig',
                        ['playerAddedToTeam' => $playerAddedToTeam,
                            'team' => $team,
                            'url' => $url]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            $this->addFlash("success", "The player has been asked to join your Team");
            return $this->redirectToRoute('teamDetail', array('id' => $team->getId()));

        }

        return $this->render("team/teamDetail.html.twig",
            [
                "team" => $team,
                'players' => $players,
                'addPlayerToTeamForm' => $addPlayerToTeamForm->createView()

            ]
        );
    }

    /**
     * @Route("/playerConfirmJoinTeam/{token}/{id}", name="playerConfirmJoinTeam")
     */
    public function playerConfirmJoinTeam(EntityManagerInterface $em, string $token, $id)
    {
        $team = $em->getRepository(Team::class)->find($id);
        $user = $em->getRepository(Player::class)->findOneByResetToken($token);

        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('main');
        }

        $user->setResetToken(null);
        $user->setTeam($team);
        $em->persist($user);
        $em->flush();

        return $this->render('team/newPlayerWelcome.html.twig', [
            'user' => $user,
            'team' => $team,
            'id' => $id
        ]);


    }

    /**
     * @Route("/searchPlayer", name="searchPlayer")
     */
    public function searchPlayer(EntityManagerInterface $em, Request $request)
    {
        $search = $request->get('add_player_to_team_nickname');
        $searchPlayer = $em->getRepository(Player::class)->searchPlayer($search);
        return $this->json($searchPlayer, 200, [], ['groups' => 'group1']);
    }


    /**
     *
     * @Route("/deleteTeam/{id}", name="deleteTeam")
     */
    public function deleteTeam(EntityManagerInterface $em, $id)
    {
        if (!$this->getUser()) {
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
