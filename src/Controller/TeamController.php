<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Post;
use App\Entity\Team;
use App\Form\AddPlayerToTeamType;
use App\Form\PlayerType;
use App\Form\TeamType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        $posts = $em->getRepository(Post::class)->findAll();
        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'posts' => $posts
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
            $teamName = $request->get('name');
            $testTeam = $em->getRepository(Team::class)->findByName($teamName);

            if ($testTeam != null) {
                $this->addFlash("warning", "This Team name is already used");
            } else {
                $photo = $teamForm->get('logo')->getData();
                if ($photo) {
                    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                    try {
                        $photo->move(
                            $this->getParameter('logoTeam_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        }
                    $team->setLogo($newFilename);
                }
                $team->setName($teamName);
                $team->addPlayer($player);
                $player->setRoles(['ROLE_TEAM_ADMIN']);
                $em->persist($team);
                $em->flush();
                $this->addFlash("success", "Welcome to your Team !");
                return $this->redirectToRoute('teamDetail', array('id' => $team->getId()));
            }

        }
        return $this->render('team/addTeam.html.twig', [
            'teamForm' => $teamForm->createView(),

        ]);
    }

    /**
     * @Route("/updateTeam/{id}", name="updateTeam")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function updateTeam(Request $request, EntityManagerInterface $entityManager, $id)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you are not connected");
            return $this->redirectToRoute('main');
        }
//        $this->denyAccessUnlessGranted('ROLE_TEAM_ADMIN');

        $team = $entityManager->getRepository(Team::class)->find($id);

        $teamForm = $this->createForm(TeamType::class, $team);
        $teamForm->handleRequest($request);

        if ($teamForm->isSubmitted() && $teamForm->isValid()) {

            $photo = $teamForm->get('logo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // On inclut le nom du fichier à l'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // On enregistre le fichier dans le dossier demandé
                try {
                    $photo->move(
                        $this->getParameter('logoTeam_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $team->setLogo($newFilename);
            }

            $testName = $team->getName();
            $testTeam = $entityManager->getRepository(Team::class)->findOneBy(
                ['name' => $testName]
            );

            if ($testTeam->getId() == $team->getId()) {
                $testTeam = null;
            }

            if ($testTeam != null) {
                $this->addFlash("alert-danger", "This name is already used");
            } else {
                $team->setName($testName);
                $entityManager->flush();
                $this->addFlash("success", "Team updated !");

                return $this->redirectToRoute('teamDetail', ['id' => $team->getId()]);

            }

        }

        return $this->render('team/updateTeam.html.twig', [
            'teamForm' => $teamForm->createView(),
            'team' => $team
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
        $player = $this->getUser();
        $players = $team->getPlayers();

        $addPlayerToTeamForm = $this->createForm(AddPlayerToTeamType::class);
        $addPlayerToTeamForm->handleRequest($request);

        if ($addPlayerToTeamForm->isSubmitted() && $addPlayerToTeamForm->isValid()) {
            $selectedPlayer = $request->get('selectNicknames');
            $playerAddedToTeam = $em->getRepository(Player::class)->findOneBy(['nickname' => $selectedPlayer]);

            if (!in_array($playerAddedToTeam, $players->toArray())) {
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
            } else {
                $this->addFlash("warning", "The player is already in your Team");
            }
            return $this->redirectToRoute('teamDetail', array('id' => $team->getId()));

        }

        return $this->render("team/teamDetail.html.twig",
            [
                "team" => $team,
                'players' => $players,
                'player' => $player,
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
        $search = $request->get('search');
        $searchPlayer = $em->getRepository(Player::class)->searchPlayer($search);
        return $this->json($searchPlayer, 200, [], ['groups' => 'group1']);
    }


    /**
     * @Route("/deleteTeam/{id}", name="deleteTeam")
     */
    public function deleteTeam(EntityManagerInterface $em, $id)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }
        $this->denyAccessUnlessGranted('ROLE_TEAM_ADMIN');

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

    /**
     * @Route("/removePlayerFromTeam/{id}", name="removePlayerFromTeam")
     */
    public function removePlayerFromTeam(EntityManagerInterface $em, $id)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $player = $em->getRepository(Player::class)->find($id);
        $team = $player->getTeam();
        $team->removePlayer($player);
        $players = $team->getPlayers();
        $em->persist($team);
        $em->flush();
        $this->addFlash("success", "The player has been removed from your Team");

        return $this->render("team/teamDetail.html.twig",
            [
                'player' => $player,
                'players' => $players,
                'id' => $id,
                'team' => $team
            ]
        );
    }

}
