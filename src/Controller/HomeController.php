<?php declare(strict_types = 1);
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


class HomeController extends AbstractController
{
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
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
        $user = $this->getUser();
        $role = $user->getRoles();

        return $this->render('page/home.html.twig', [
            'scorer' => $user->getUsername(),
            'role' => $role[0]
        ]);
    }
}