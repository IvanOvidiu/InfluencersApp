<?php


namespace App\Repository;


use App\Entity\TagRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TagRelationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagRelation::class);
    }

    public function createRelation($article, $tag)
    {
        $entityManager = $this->getEntityManager();

        $relation = new TagRelation();

        $relation->setArticle($article);
        $relation->setTag($tag);

        $entityManager->persist($relation);
        $entityManager->flush();
    }
}