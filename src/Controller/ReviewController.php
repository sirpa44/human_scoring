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
        try {
            $deliveryUri = 'http://taoproject/toto.rdf#i1547646939928581';
            $ltiUrl = $this->callLti->GetFinalUrl($deliveryUri);
            $signedData = $this->callLti->GetSignedData();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
//        dump($ltiUrl);
//        dump($signedData);
//        die();
        return $this->render('review/review.html.twig', [
            'ltiurl' => $ltiUrl,
            'signeddata' => $signedData
        ]);
    }
}