<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @Route("/profile/{id}", name="profile")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager, $id = null)
    {
        if (!$this->getUser()){
            $this->addFlash('danger', "You cannot access to this page if you are not connected");
            return $this->redirectToRoute('main');
        }
        $player = $entityManager->getRepository(Player::class)->find($id);

        return $this->render('player/profile.html.twig', [
            'player' => $player,
        ]);
    }

    /**
     * @Route("/updateProfile", name="updateProfile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function updateProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser()){
            $this->addFlash('danger', "You cannot access to this page if you are not connected");
            return $this->redirectToRoute('main');
        }
        $this->denyAccessUnlessGranted('ROLE_USER');

        $player = $this->getUser();

        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getViewData();
            $password2 = $form->get('password2')->getViewData();
            $photo = $form->get('avatar')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // On inclut le nom du fichier à l'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();

                // On enregistre le fichier dans le dossier demandé
                try {
                    $photo->move(
                        $this->getParameter('avatarPlayer_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $player->setAvatar($newFilename);
            }

            $testPseudo = $player->getNickname();

            //recherche si pseudo identique dans la BD
            $testPlayer = $entityManager->getRepository(Player::class)->findOneBy(
                ['nickname' => $testPseudo]
            );

            if ($testPlayer->getId() == $player->getId()) {
                $testPlayer = null;
            }

            if ($testPlayer != null) {
                $this->addFlash("alert-danger", "Nickname already used");
            } else {
                if ($password == $password2) {

                    $player->setPassword($password);

                    $hash = $passwordEncoder->encodePassword($player, $player->getPassword());

                    $player->setPassword($hash);

                    $entityManager->flush();

                    $this->addFlash("success", "Profile updated !");

                    return $this->redirectToRoute('profile', ['id' => $player->getId()]);
                } else {
                    $this->addFlash("alert-danger", "Passwords are not the same !");
                }
            }

        }

        return $this->render('player/updateProfile.html.twig', [
            'updateForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/deleteAccount/{id}", name = "deleteAccount")
     * @param $id
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteAccount($id, EntityManagerInterface $em)
    {
        if (!$this->getUser()){
            $this->addFlash('danger', "You cannot access to this page if you are not connected");
            return $this->redirectToRoute('main');
        }
        $this->denyAccessUnlessGranted('ROLE_USER');

        $player = $em->getRepository(Player::class)->find($id);

        if ($player->getEvents()->count() == 0 && $player->getTeam()->count() == 0) {
            $em->remove($player);
            $em->flush();
            $this->addFlash('success', "You're account has been deleted");
            return $this->redirectToRoute('main');
        } else {
            $this->addFlash('warning', "You still have some events coming, please unsubscribe from them before delete your account.");
        }

        return $this->redirectToRoute('main');

    }
}
