<?php
namespace App\LTI\Consumer;

class Consumer
{
    protected $key;
    protected $secret;
    protected $callback_url;

    public function __construct($key, $secret, $callbackUrl = NULL)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callback_url = $callbackUrl;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getSecret()
    {
        return $this->secret;
    }

//    function __toString() {
//        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
//    }

//    public function __get($name)
//    {
//        var_dump($name);
//        $debug = debug_backtrace();
//        dump($debug);
//        die();
//    }



}