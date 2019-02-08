<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
//        $entityManager = $this->getDoctrine()->getManager();

//        $scorerEntity = new ScorerEntity();
//        $scorerEntity->setUsername('toto');
//        $password = $passwordEncoder->encodePassword($scorerEntity,'toto');
//        $scorerEntity->setPassword($password);

//         tell Doctrine you want to (eventually) save the Product (no queries yet)
//        $entityManager->persist($scorerEntity);

        // actually executes the queries (i.e. the INSERT query)
//        $entityManager->flush();

//        return new Response('Saved new product with id '.$scorerEntity->getId());

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
