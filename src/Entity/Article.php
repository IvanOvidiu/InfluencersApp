<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Your article's title must be at least {{ limit }} characters long",
     *      maxMessage = "Your article's title cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 50,
     *      max = 500,
     *      minMessage = "Your article's description must be at least {{ limit }} characters long",
     *      maxMessage = "Your article's description cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 500,
     *      max = 10000,
     *      minMessage = "Your article's content must be at least {{ limit }} characters long",
     *      maxMessage = "Your article's content cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     *@Assert\DateTime
     * @var string A "d-m-Y H:i:s" formatted value
     */
    private $submission_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgPath;

    /**
     * @ORM\Column(type="integer")
     */
    private $no_reviews;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="articles")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TagRelation", mappedBy="article")
     */
    private $relations;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min = 500,
     *      max = 10000,
     *      minMessage = "Your article's content must be at least {{ limit }} characters long",
     *      maxMessage = "Your article's content cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false,
     *     groups={"edit"}
     * )
     */
    private $new_content;

    /**
     * @ORM\Column(type="float")
     */
    private $rating;

    /**
     * @ORM\Column(type="integer")
     */
    private $edit;

    /**
     * @return int
     */
    public function getEdit(): int
    {
        return $this->edit;
    }

    /**
     * @param int $edit
     */
    public function setEdit(int $edit): void
    {
        $this->edit = $edit;
    }

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->no_reviews = 0;
        $this->rating = 0;
        $this->edit = 1;
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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
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


    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param ArrayCollection $relations
     */
    public function setRelations(ArrayCollection $relations): void
    {
        $this->relations = $relations;
    }

    /**
     * @return mixed
     */
    public function getNewContent()
    {
        return $this->new_content;
    }

    /**
     * @param mixed $new_content
     */
    public function setNewContent($new_content): void
    {
        $this->new_content = $new_content;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }


}