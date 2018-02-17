<?php
namespace Vkolya\ocSDK\Modules;
use Vkolya\ocSDK\Base;
class Shipping extends Base {
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
        $this->curl->setUrl($this->oc->getUrl('shipping/address'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function methods() {
        $this->curl->setUrl($this->oc->getUrl('shipping/methods'));
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function method($shipping_method) {
        if (empty($shipping_method)) throw new InvalidDataException("Shipping method cannot be empty for Shipping->method()");
        $postData = array(
            'shipping_method' => $shipping_method
        );
        $this->curl->setUrl($this->oc->getUrl('shipping/method'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}
