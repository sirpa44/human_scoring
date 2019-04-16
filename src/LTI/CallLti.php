<?php

namespace App\LTI;


use App\LTI\Consumer\Consumer;
use IMSGlobal\LTI\ToolProvider\ToolConsumer;

class CallLti
{
    protected $consumer;
    protected $rootUrl;
    protected $pathUrl;
    protected $ltiParams;
    protected $finalUrl;


    public function __construct($ltiData, Consumer $consumer)
    {
        $this->consumer = $consumer;
        $this->rootUrl = $ltiData['rooturl'];
        $this->pathUrl = $ltiData['pathurl'];
        $this->ltiParams = array(
            'user_id' => 123,
            'roles' => $ltiData['role'],
            'lti_version' => 'LTI-1p0',
            'lti_message_type' => 'basic-lti-launch-request',
            'resource_link_id' => 42978522
        );
    }


    public function GetFinalUrl($deliveryUri)
    {
            $this->finalUrl = $this->rootUrl . '/'. $this->pathUrl . '?delivery=' . urlencode($deliveryUri);
            return $this->finalUrl;
    }

    public function GetSignedData()
    {
        if (isset($this->finalUrl)) {
            $data = ToolConsumer::addSignature($this->finalUrl, $this->consumer->getKey(), $this->consumer->getSecret(), $this->ltiParams);
            return $data;
        }
        throw new \Exception('final url isn\'t set');
    }
}