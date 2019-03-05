<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;


class HomeController extends AbstractController
{

    private $security;
    private $urlGenerator;

    /**
     * @param Security $security
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * render the home page with username and role
     *
     * @Route("/", name="scorer.home")
     */
    public function index(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
        $user = $this->security->getUser();
        $role = $user->getRoles();

        return $this->render('page/home.html.twig', [
            'scorer' => $user->getUsername(),
            'role' => $role[0]
        ]);
    }
}