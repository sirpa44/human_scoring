<?php
namespace App\LTI\Oauth;

use App\LTI\Consumer\Consumer;
use IMSGlobal\LTI\OAuth\OAuthUtil;

class LtiOauthToken
{
    protected $key;
    protected $secret;

    public function __construct(Consumer $consumer)
    {
        $this->key = $consumer->getKey();
        $this->secret = $consumer->getSecret();
    }

    public function getToken()
    {
        return $this->makeToken();
    }

    protected function makeToken()
    {
        return 'oauth_token=' .
            OAuthUtil::urlencode_rfc3986($this->key) .
            '&oauth_token_secret=' .
            OAuthUtil::urlencode_rfc3986($this->secret);
    }

//    function __toString() {
//        return $this->makeToken();
//    }

}