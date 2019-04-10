<?php
namespace App\Controller;

use App\LTI\CallLti;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    private $callLti;

    public function __construct(CallLti $callLti)
    {
        $this->callLti = $callLti;
    }

    /**
     * show review page
     *
     * @Route("/review", name="app_review")
     * @param $deliveryUri
     * @return mixed
     */
    public function showReview()
    {
        $deliveryUri = 'http://taoproject/toto.rdf#i1547646939928581';
        $ltiUrl = $this->callLti->ltiTry($deliveryUri);
//        dump($ltiUrl);
        return $this->render('review/review.html.twig', [
            'ltiurl' => $ltiUrl
        ]);
    }
}