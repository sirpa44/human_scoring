<?php

namespace App\LTI;


use App\LTI\Consumer\Consumer;
use App\LTI\Oauth\LtiOauthToken;
use IMSGlobal\LTI\OAuth\OAuthConsumer;
use IMSGlobal\LTI\OAuth\OAuthRequest;
use IMSGlobal\LTI\OAuth\OAuthSignatureMethod_HMAC_SHA1;
use IMSGlobal\LTI\OAuth\OAuthToken;

class CallLti
{
    protected $consumer;
    protected $ltiOauthToken;
    protected $rootUrl;
    protected $pathUrl;
    protected $ltiParams;


    public function __construct($ltiData, Consumer $consumer, LtiOauthToken $ltiOauthToken)
    {
        $this->consumer = $consumer;
        $this->ltiOauthToken = $ltiOauthToken;
        $this->rootUrl = $ltiData['rooturl'];
        $this->pathUrl = $ltiData['pathurl'];
//        $this->delivery = 'http://taoproject/toto.rdf#i1547646939928581';
        $this->ltiParams = array(
            'lis_person_name_full' => 'toto',
            'user_id' => 123,
            'context_id' => 456,
            'roles' => $ltiData['role'],
            'custom_skip_thankyou' => 'true',
            'custom_proctored' => 'false',
            'context_label' => 'ods_error_fix',
            'lti_version' => 'LTI-1p0',
            'lti_message_type' => 'basic-lti-launch-request',
            'resource_link_id' => 42978522
        );
    }


    public function ltiTry($deliveryUri)
    {
        $test_consumer = new OAuthConsumer($this->consumer->getKey(), $this->consumer->getSecret());
        $test_token = new OauthToken($test_consumer, '');
//        $test_token = 'la tete a toto';
//        dump($test_token->key);
//        dump($this->consumer);die();
        $hmac_method = new OAuthSignatureMethod_HMAC_SHA1();


        $urlParams = [
            'delivery' => $deliveryUri
        ];
        $url = $this->rootUrl . '/'. $this->pathUrl . '?' . http_build_query($urlParams);

        $acc_req = OAuthRequest::from_consumer_and_token(
            $test_consumer,
            $test_token,
            'GET',
            $url,
            $this->ltiParams
        );

//        $acc_req = OAuthRequest::from_consumer_and_token(
//            $this->consumer,
//            $this->ltiOauthToken,
//            'GET',
//            $url,
//            $this->ltiParams
//        );
//        dump($acc_req);die();
        $acc_req->sign_request($hmac_method, $test_consumer, $test_token);
//        $acc_req->sign_request($hmac_method, $this->consumer, $this->ltiOauthToken);
        $finalUrl = $acc_req->to_url();

//        dump($finalUrl);die();
        return $finalUrl;
    }
}