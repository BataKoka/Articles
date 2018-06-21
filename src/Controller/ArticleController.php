<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/", name="article_index", methods="GET")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', ['articles' => $articleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="article_new", methods="GET|POST")
     */
    public function new(Request $request, UserInterface $user): Response
    {
        $article = new Article();
        $article->setUser($user);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', '<strong>Success!</strong> Item has been added successfully');

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods="GET")
     */
    public function show(Article $article, CommentRepository $commentRepository): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $commentRepository->findAllCommentsForArticle($article),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods="GET|POST")
     */
    public function edit(Request $request, Article $article, UserInterface $user): Response
    {
//        if ($user === $article->getUser()) {
//            echo 'dap, IDENTICNI su!';
//        }

        if ( $request->isMethod('get') && $article->getUser() !== $user ) {
            throw new AccessDeniedException('Unable to access this page!');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', '<strong>Success!</strong> Item has been edited successfully');

            return $this->redirectToRoute('article_edit', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article, UserInterface $user): Response
    {
        if ( $article->getUser() !== $user ) {
            throw new AccessDeniedException('Unable to access this page!');
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            $this->addFlash('success', '<strong>Success!</strong> Item has been deleted successfully');
        }

        return $this->redirectToRoute('article_index');
    }
}
