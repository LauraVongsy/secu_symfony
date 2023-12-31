<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UtilsService;
use App\Service\MessagerieService;
class RegisterController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository $repo;
    private MessagerieService $messagerie;
   
    public function __construct(UserRepository $repo, EntityManagerInterface $em, MessagerieService $messagerie){
        $this->repo = $repo;
        $this->em = $em;
        $this->messagerie = $messagerie;
    }
    #[Route('/register', name: 'app_register')]
    public function addUser(UserPasswordHasherInterface $hash, Request $request): Response
    {
        $msg = "";
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);
        //test si le formulaire est submit
        if ($form->isSubmitted() && $form->isValid()) {
            //tester si le compte existe déja
            if($this->repo->findOneBy(["email"=> $form->get("email")->getData()])){
                $msg ="Le compte : ".$user->getEmail()." existe déja";
            }
            //test si le compte n'existe pas
            else{
                //nettoyage et enregistrement du mot de passe en clair
                $pass = UtilsService::cleanInput($request->request->all('register')['password']['first']);
                $hash = $hash->hashPassword($user, $pass);
                $user->setPassword($hash);
                $user->setActivated(false);
                $user->setRoles(["ROLE_USER"]);
                //nettoyage et set du mail
                $user->setEmail(UtilsService::cleanInput($request->request->all('register')['email']));
                $this->em->persist($user);
                $this->em->flush();
                $msg = "Le compte : ".$user->getEmail()." a été ajouté en BDD";
                $object = "Activation de votre compte";
                $content ="<h1>Pour activer le compte cliquer sur le lien ci-dessous :</h1>
                <a href='https://localhost:8000/register/activate/".$user->getId()."'>Activer</a>";
                $this->messagerie->sendMail($object,$content,$user->getEmail());
            }
        }
        return $this->render('register/index.html.twig', [
            'msg' => $msg,
            'form' =>$form->createView()
        ]);
    }
    #[Route('/register/activate/{id}', name: 'app_register_activate')]
    public function activateUser(?int $id){
        $recup = $this->repo->find(UtilsService::cleanInput($id));
        //test si le compte existe
        if($recup){
            $recup->setActivated(true);
            $this->em->flush();  
            return $this->redirectToRoute('app_login');
        }
        //test si le compte n'existe pas
        else{
            return $this->redirectToRoute('app_register');
        }
    }
    #[Route('/register/update/{id}', name: 'app_register_update')]
    public function updateUser(int $id, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $msg = "";
        $user = $this->repo->find(UtilsService::cleanInput($id));
        $form = $this->createForm(RegisterType::class,$user);
    
        if ($user) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pass = UtilsService::cleanInput($request->request->all('register')['password']['first']);
                $hash = $hash->hashPassword($user, $pass);
                $user->setPassword($hash);

                $user->setName(UtilsService::cleanInput($request->request->all('register')['name']['name']));
                $user->setFirstname(UtilsService::cleanInput($request->request->all('register')['firstname']['firstname']));
                $user->setEmail(UtilsService::cleanInput($request->request->all('register')['email']['email']));
    
                $this->em->flush();
                $msg = "Le compte : a été mis à jour en BDD";
            }
        } else {
            $msg = "Le compte n'existe pas";
        }
    
        return $this->render('register/index.html.twig', [
            'msg' => $msg,
            'form' => $form->createView(),
        ]);
    }
}