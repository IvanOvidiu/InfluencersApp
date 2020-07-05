<?php


namespace App\Repository;

use App\Entity\Article;
use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function createAuthor($email, $name)
    {
        $entityManager = $this->getEntityManager();

        $author = new Author();

        $author->setEmail($email);
        $author->setName($name);
        $author->setRating(0);

        $entityManager->persist($author);
        $entityManager->flush();

        return $author;
    }

    public function checkAuthor($email)
    {
        $entityManager = $this->getEntityManager();

        $qb = $this->createQueryBuilder('a')
            ->where('a.email = :email')
            ->setParameter('email', $email);

        return $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    public function updateRating(int $rating, $author)
    {
        $entityManager = $this->getEntityManager();

        $author->setRating($author->getRating() + $rating);

        $entityManager->persist($author);
        $entityManager->flush();
    }

    public function getRanking()
    {
        $entityManager = $this->getEntityManager();

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.rating', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->execute();
    }
}