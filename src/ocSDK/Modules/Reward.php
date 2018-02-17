<?php
namespace Vkolya\ocSDK\Modules;

use Vkolya\ocSDK\Base;
class Reward extends Base {
    public function add($reward) {
        if (empty($reward)) throw new InvalidDataException("Reward cannot be empty for Reward->add()");
        $postData = array(
            'reward' => $reward
        );
        $this->curl->setUrl($this->oc->getUrl('reward'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function maximum() {
        $this->curl->setUrl($this->oc->getUrl('reward/maximum'));
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function available() {
        $this->curl->setUrl($this->oc->getUrl('reward/available'));
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}

