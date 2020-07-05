<?php


namespace App\Controller;


use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * @Route("/ranking", name="authors-ranking")
     */
    public function ranking()
    {
        $authors = $this->getDoctrine()->getRepository(Author::class)->getRanking();

        return $this->render('Author/ranking.html.twig', [
            'authors' => $authors,
            ]);
    }
}