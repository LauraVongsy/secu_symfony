<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $entitymanager, SerializerInterface $serializer){
        $this->articleRepository = $articleRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/api/article', name: 'app_api_article')]
    public function index(): Response
    {
        return $this->render('api_article/index.html.twig', [
            'controller_name' => 'ApiArticleController',
        ]);
    }
    #[Route('/api/article/all', name:'app_api_article_all')]
    public function getAllArticle(): Response{
        $articles = $this->articleRepository->findAll();
        return $this->json($articles,200, ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*'], ['groups'=>'articles']);
    }
    
    #[Route('/api/article/id/{id}', name:'app_api_article_api')]
    public function getArticleById(int $id): Response{
        $article = $this->articleRepository->find($id);
        //test si l'article existe
        if($article){
            return $this->json($article,200, ['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=>'*'], ['groups'=>'articles']);
        }
        //test si l'article n'existe pas
        else{
            return $this->json(['error : '=>'Aucun article'],206, ['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=>'*']);
        }
    }

    #[Route('/api/article/add', name:'app_api_article_add', methods:'POST')]
    public function addArticle(Request $request, UserRepository $userRepository): Response{
        $json = $request->getContent();
        $data = $this->serializerInterface->decode($json, 'json');
        $article = new Article();
        $article->setTitle(UtilsService::cleanInput($data['title'])); 
        $article->setContent(UtilsService::cleanInput($data['content'])); 
        $article->setDate(new \DateTimeImmutable(UtilsService::cleanInput($data['date'])));
        $article->setAuthor($userRepository->findOneBy(['email'=> UtilsService::cleanInput($data['author']['email'])]));
        $this->em->persist($article);
        $this->em->flush();
        return $this->json(['error'=>'L\'article a été ajouté en BDD'],200,
        ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*']);
    }

}