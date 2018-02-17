<?php
namespace Vkolya\ocSDK\Modules;

use Vkolya\ocSDK\Base;

use Vkolya\ocSDK\Exceptions\InvalidProductException;
use Vkolya\ocSDK\Exceptions\InvalidDataException;

class Cart extends Base {
    
    
    public function add($product, $quantity = 1, $option = array()) {
        $postData = array();
        if (is_array($product)) {
            $postData['product'] = $product;
        } else if (is_numeric($product)) {
            $postData['product_id'] = $product;
            $postData['quantity'] = $quantity;
            $postData['option'] = $option;
        } else {
            throw new InvalidProductException('Invalid product information');
        }
        
        $this->curl->setUrl($this->oc->getUrl('cart/add'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function edit($key, $quantity) {
        if (empty($key) || empty($quantity)) throw new InvalidDataException('Key and quantity cannot be empty for Cart->edit()');
        $postData = array(
            'key' => $key,
            'quantity' => $quantity
        );
        $this->curl->setUrl($this->oc->getUrl('cart/edit'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function remove($key) {
        if (empty($key)) throw new InvalidDataException('Key cannot be empty for Cart->remove()');
        $postData = array(
            'key' => $key
        );
        $this->curl->setUrl($this->oc->getUrl('cart/remove'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function products() {
        $this->curl->setUrl($this->oc->getUrl('cart/products'));
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}

