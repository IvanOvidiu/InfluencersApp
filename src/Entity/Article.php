<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */

class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submission_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imgPath;

    /**
     * @ORM\Column(type="integer")
     */
    private $no_reviews;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="articles")
     */
    private $author_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TagRelation", mappedBy="article_id")
     */
    private $relations;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $new_content;

    /**
     * Article constructor.
     * @param $relations
     */
    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getSubmissionDate()
    {
        return $this->submission_date;
    }

    /**
     * @param mixed $submission_date
     */
    public function setSubmissionDate($submission_date): void
    {
        $this->submission_date = $submission_date;
    }

    /**
     * @return mixed
     */
    public function getNoReviews()
    {
        return $this->no_reviews;
    }

    /**
     * @param mixed $no_reviews
     */
    public function setNoReviews($no_reviews): void
    {
        $this->no_reviews = $no_reviews;
    }

    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * @param mixed $author_id
     */
    public function setAuthorId($author_id): void
    {
        $this->author_id = $author_id;
    }

    /**
     * @return mixed
     */
    public function getImgPath()
    {
        return $this->imgPath;
    }

    /**
     * @param mixed $imgPath
     */
    public function setImgPath($imgPath): void
    {
        $this->imgPath = $imgPath;
    }

    //Todo getter and setter
}