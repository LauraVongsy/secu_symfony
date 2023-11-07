<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TestController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $em;
    public function __construct( EntityManagerInterface $entitymanager, SerializerInterface $serializer){
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    #[Route('/test', name: 'app_test')]
    public function validationTests(Request $request): Response
    {
        $json = $request->getContent();
        $data = $this->serializerInterface->decode($json, 'json');
        $test= new Test();
        $test->setTitle($data['title']);
        $test->setDate($data['date']);
        $test->setStatus($data['status']);
        $this->em->persist($test);
        $this->em->flush();
        return $this->json(['error'=>'Le test a été ajouté en BDD'],200,
        ['Content-Type'=>'application/json', 'Access-Control-Allow-Origin'=>'*']);
    }

}
