<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Player;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/addComment/{id}", name="addComment")
     */
    public function addComment(EntityManagerInterface $em, Request $request, $id)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }
        $post = $em->getRepository(Post::class)->find($id);
        $comments = $post->getComments();
        $comment = new Comment();
        $user = $this->getUser();
        $player = $em->getRepository(Player::class)->find($user);
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $message = $request->get('message');
            $comment->setMessage($message);
            $comment->setPlayer($player);
            $comment->setPost($post);
            dd($comment);
            $em->persist($comment);
            $em->flush();
            $this->addFlash("success", "Your comment has been added !");
            return $this->redirectToRoute('postDetail', ['id' => $post->getId()]);
        }
        return $this->render('post/postDetail.html.twig', [
            "post" => $post,
            "comments" => $comments,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
