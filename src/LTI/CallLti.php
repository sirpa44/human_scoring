<?php

namespace App\LTI;


use IMSGlobal\LTI\OAuth\OAuthConsumer;
use IMSGlobal\LTI\OAuth\OAuthRequest;
use IMSGlobal\LTI\OAuth\OAuthSignatureMethod_HMAC_SHA1;
use IMSGlobal\LTI\OAuth\OAuthToken;

class CallLti
{
    private $key;
    private $secret;
    private $delivery;
    private $ltiParams;
    private $url;


    public function __construct($ltiData)
    {
        $this->key = $ltiData['key'];
        $this->secret = $ltiData['secret'];
        $this->delivery = 'http://taoproject/toto.rdf#i1547646939928581';
        $urlParams = [
            'delivery' => $this->delivery
        ];
        $this->url = $ltiData['rooturl'] . '/'. $ltiData['pathurl'] . '?' . http_build_query($urlParams);
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

    public function ltiTry()
    {
        $test_consumer = new OAuthConsumer($this->key, $this->secret);
        $test_token = new OAuthToken($test_consumer, '');
        $hmac_method = new OAuthSignatureMethod_HMAC_SHA1();

        $acc_req = OAuthRequest::from_consumer_and_token($test_consumer, $test_token, 'GET', $this->url,
            $this->ltiParams);
        $acc_req->sign_request($hmac_method, $test_consumer, $test_token);
        $finalUrl = $acc_req->to_url();

        return $finalUrl;


//        echo '<iframe src="' .  $finalUrl . '" width="1000px" height="1000">';
//        echo '</iframe>';
    }
}