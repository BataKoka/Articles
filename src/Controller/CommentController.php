<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class CommentController extends Controller
{
    /**
     * @Route("/article/{articleId}/comment/new", name="comment_new", methods="GET|POST")
     */
    public function new(Request $request, $articleId, UserInterface $user): Response
    {
        $comment = new Comment();
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($articleId);
        $comment->setArticle($article);
        $comment->setUser($user);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', '<strong>Success!</strong> Item has been added successfully');

            return $this->redirectToRoute('article_show', ['id' => $articleId]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'articleId' => $articleId,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{id}/edit", name="comment_edit", methods="GET|POST")
     */
    public function edit(Request $request, Comment $comment, UserInterface $user): Response
    {
        if ( $request->isMethod('get') && $comment->getUser() !== $user ) {
            throw new AccessDeniedException('Unable to access this page!');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', '<strong>Success!</strong> Item has been edited successfully');

            return $this->redirectToRoute('comment_edit', ['id' => $comment->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
            'articleId' => $comment->getArticle()->getId(),
        ]);
    }

    /**
     * @Route("/comment/{id}", name="comment_delete", methods="DELETE")
     */
    public function delete(Request $request, Comment $comment, UserInterface $user): Response
    {
        if ( $comment->getUser() !== $user ) {
            throw new AccessDeniedException('Unable to access this page!');
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', '<strong>Success!</strong> Item has been deleted successfully');
        }

        return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
    }
}
