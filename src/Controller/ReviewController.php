<?php
namespace App\Controller;

use App\LTI\CallLti;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController
{

    /**
     * show review page
     *
     * @Route("/review", name="app_review")
     * @param $deliveryUri
     * @param CallLti $callLti
     * @return mixed
     */
    public function showReview($deliveryUri, CallLti $callLti)
    {
        $ltiUrl = $callLti->ltiTry($deliveryUri);
        return $this->render('security/review.html.twig', [
            'ltiurl' => $ltiUrl
        ]);
    }
}