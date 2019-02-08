<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/home", name="scorer.home")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();
        $role = $user->getRoles();
        return $this->render('page/home.html.twig',[
            'scorer' => $user->getUsername(),
            'role' => $role[0]
        ]);
    }
}