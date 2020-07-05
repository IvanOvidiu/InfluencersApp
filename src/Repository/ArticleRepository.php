<?php


namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\TagRelation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[]
     */
    public function getArticlesAsc(){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a FROM App\Entity\Article a ORDER BY a.submission_date DESC'
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @param int $id
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getArticle(int $id)
    {
        $entityManager = $this->getEntityManager();

        $qb = $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();

        /**
         * @var Article $article
         **/
        $article = $query->setMaxResults(1)->getOneOrNullResult();

        if($article->getNoReviews() != 0 && $article->getEdit() == 1)
        {
            $article->setEdit(0);
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $article;
    }

    /**
     * @param Article $article
     * @return int|mixed|string
     */
    public function relatedArticles(Article $article)
    {
        $entityManager = $this->getEntityManager();

        /** @var TagRelation[] $relations */
        $relations = $article->getRelations();
        $tagIds = new ArrayCollection();

        $qb1 = $this->createQueryBuilder('a')
            ->where('a.author = :author')
            ->setParameter('author', $article->getAuthor());

        $related = $qb1->getQuery()->execute();

        foreach ($relations as $relation) {
            $tagIds[] = $relation->getTag()->getId();
        }
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.relations', 'ar' )
            ->leftJoin('ar.tag', 'tag')
            ->andWhere('tag.id IN (:ids)')
            ->setParameter('ids', $tagIds)
            ->distinct();

        $related = array_merge($related,$qb->getQuery()->execute());

        if(count($related) > 15)
            return array_slice($related, 0, 15);
        else
            return $related;
    }

    /**
     * @param string $name
     * @return int|mixed|string
     */
    public function getArticleByTag(string $name)
    {
        $entityManager = $this->getEntityManager();

        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.relations', 'ar')
            ->leftJoin('ar.tag', 'tag')
            ->where('tag.name = :name')
            ->setParameter('name', $name)
            ->orderBy('a.submission_date', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $authorId
     * @return int|mixed|string
     */
    public function getArticleByAuthor(int $authorId)
    {
        $entityManager = $this->getEntityManager();

        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.author', 'at')
            ->where('at.id = :id')
            ->setParameter('id', $authorId)
            ->orderBy('a.submission_date', 'DESC');

        return $qb->getQuery()->getResult();
    }
}