<?php
namespace Vkolya\ocSDK\Modules;
use Vkolya\ocSDK\Base;
class Payment extends Base {
    public function address($firstname = '', $lastname = '', $company = '', $address_1 = '', $address_2 = '', $postcode = '', $city = '', $zone_id = '', $country_id = '') {
        $postData = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'company' => $company,
            'address_1' => $address_1,
            'address_2' => $address_2,
            'postcode' => $postcode,
            'city' => $city,
            'zone_id' => $zone_id,
            'country_id' => $country_id
        );
        $this->curl->setUrl($this->oc->getUrl('payment/address'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function methods() {
        $this->curl->setUrl($this->oc->getUrl('payment/methods'));
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function method($payment_method) {
        if (empty($payment_method)) throw new InvalidDataException("Payment method cannot be empty for Payment->method()");
        $postData = array(
            'payment_method' => $payment_method
        );
        $this->curl->setUrl($this->oc->getUrl('payment/method'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}