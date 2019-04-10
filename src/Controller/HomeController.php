<?php declare(strict_types = 1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Controller;

use App\LTI\CallLti;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class HomeController extends AbstractController
{
    private $urlGenerator;
    private $callLti;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param CallLti $callLti
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, CallLti $callLti)
    {
        $this->urlGenerator = $urlGenerator;
        $this->callLti = $callLti;
    }

    /**
     * render the home page with username and role
     *
     * @Route("/", name="scorer.home")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $role = $user->getRoles();
        return $this->render('page/home.html.twig', [
            'scorer' => $user->getUsername(),
            'role' => $role[0]
        ]);
    }
}