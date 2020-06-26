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

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/addPost", name="addPost")
     */
    public function addPost(EntityManagerInterface $em, Request $request)
    {
        if (!$this->getUser()) {
            $this->addFlash('danger', "You cannot access to this page if you're not connected");
            return $this->redirectToRoute('main');
        }

        $post = new Post();
        $user = $this->getUser();
        $player = $em->getRepository(Player::class)->find($user);
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post->setPlayer($player);
            $em->persist($post);
            $em->flush();
            $this->addFlash("success", "Your post has been created !");
            return $this->redirectToRoute('postDetail', ['id' => $post->getId()]);
        }
        return $this->render('post/addPost.html.twig', [
            'postForm' => $postForm->createView(),
        ]);
    }

    /**
     * @Route("/postDetail/{id}", name="postDetail")
     */
    public function detail($id, EntityManagerInterface $em, Request $request)
    {
        if (!$this->getUser()){
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
            $comment->setPlayer($player);
            $comment->setPost($post);

            $em->persist($comment);
            $em->flush();
            $this->addFlash("success", "Your comment has been added !");
            return $this->redirectToRoute('postDetail', ['id' => $post->getId()]);
        }

        return $this->render("post/postDetail.html.twig",
            [
                "post" => $post,
                "comments" => $comments,
                'commentForm' => $commentForm->createView(),
            ]
        );
    }
}
