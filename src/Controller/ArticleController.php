<?php


namespace App\Controller;

use App\Form\EditArticleType;
use App\Repository\AuthorRepository;
use App\Service\FileUploader;
use App\Entity\TagRelation;
use App\Form\ArticleType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManager;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{

    /**
     * @Route("/newArticle", name="new_article")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function createArticle(Request $request, FileUploader $fileUploader)
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Article $article */
            $article = $form->getData();
            $article->setNoReviews(0);
            $article->setSubmissionDate(new \DateTime('now'));

            $email = $form->get('author')->get('email')->getData();
            $name = $form->get('author')->get('name')->getData();
            $relations = $form->get('relations')->getData();
            $imageFile = $form->get('image')->getData();

            $article->setRelations(new ArrayCollection());

            if($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $article->setImgPath('uploads/images'.$imageFileName);
            }

            $author = $this->getDoctrine()->getRepository(Author::class)->checkAuthor($email);
            if($author == null)
            {
                $author = $this->getDoctrine()->getRepository(Author::class)->createAuthor($email, $name);
            }

            $article->setAuthor($author);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            foreach ($relations as $item)
            {
                $this->getDoctrine()->getRepository(TagRelation::class)->createRelation($article, $item);
            }

            return $this->redirectToRoute('read_article', array('id' => $article->getId()));
        }

        return $this->render('Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/articles", name="articles_route")
     */
    public function displayAll()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->getArticlesAsc();
        return $this->render('Article/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/{id}", name="read_article")
     * @param int $id
     * @return Response
     */
    public function read(int $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->getArticle($id);
        $related = $this->getDoctrine()->getRepository(Article::class)->relatedArticles($article);
        return $this->render('Article/read.html.twig', [
            'article' => $article,
            'related' => $related,
        ]);
    }

    /**
     * @Route("/articles/{id}", name="articles_byAuthor")
     * @param int $id
     * @return Response
     */
    public function byAuthor(int $id)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->getArticleByAuthor($id);

        return $this->render('Article/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/articles-tag/{name}", name="articles_byTag")
     * @param string $name
     * @return Response
     */
    public function byTag(string $name)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->getArticleByTag($name);

        return $this->render('Article/articles.html.twig', [
            'articles' => $articles
        ]);

    }

    /**
     * @Route("/article-vote/{id}", name="article-vote")
     * @param Request $request
     * @param int $id
     */
    public function vote(Request $request, int $id)
    {
        $response = new JsonResponse();
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if($request->isXmlHttpRequest())
        {
            if($request->cookies->get('voted'.$id))
            {
                $response->setContent(json_encode([
                    'data'=> "Already voted",
                ]));

                return $response;
            }
            else{
                $cookie = new Cookie('voted'.$id, 'true', time() + (86400 * 120), '/article-vote/'.$id);
                $response->headers->setCookie($cookie);
                $userRating = $request->get('rating');

                $article->setNoReviews($article->getNoReviews() + 1);
                $article->setRating($userRating / $article->getNoReviews());

                /** @var  Author $author */
                $author = $article->getAuthor();
                $this->getDoctrine()->getRepository(Author::class)->updateRating($userRating, $author);

                $entityManager->persist($article);
                $entityManager->flush();

                $rating = $article->getRating();

                $response->setContent(json_encode([
                    'data' => $rating,
                ]));

                return $response;
            }
        }
    }

    /**
     * @param Request $request
     * @param MailerInterface $mailer
     * @param int $id
     * @throws \Exception
     * @Route("/sendEmail/{id}", name="sendEmail")
     */
    public function sendEmail(Request $request, MailerInterface $mailer,int $id)
    {
        $response = new JsonResponse();
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);
        $author = $article->getAuthor();

        if($request->isXmlHttpRequest())
        {

            $article->setEdit(random_int(100, 2147483647));
            $entityManager->persist($article);
            $entityManager->flush();

            $link = 'http://127.0.0.1:8000/edit/' . $article->getEdit() . '/' . $article->getId();

            $email = (new TemplatedEmail())
                ->from('ovidiu.ivan98@gmail.com')
                ->to($author->getEmail())
                ->subject('Edit your article')
                ->htmlTemplate('Emails/edit.html.twig')
                ->context([
                    'article' => $article,
                    'editLink' => $link,
                ]);

            $response->setContent(json_encode([
                'data'=> "A link was sent to you"
            ]));

            return $response;
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/edit/{tocken}/{id}")
     */
    public function edit(Request $request,int $tocken, int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);

        if($tocken == $article->getEdit()){
            $form = $this->createForm(EditArticleType::class, $article);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $article = $form->getData();

                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('read_article', array('id' => $article->getId()));
            }

            return $this->render('Article/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        else{
            return $this->render('index.html.twig');
        }
    }
}