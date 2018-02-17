<?php
namespace Vkolya\ocSDK\Modules;
use Vkolya\ocSDK\Base;

use Vkolya\ocSDK\Exceptions\InvalidDataException;

class Order extends Base {
    public function add($shipping_method = '',$payment_method = '', $comment = '', $affiliate_id = '', $order_status_id = '') {
        $postData = array(
            'shipping_method' => $shipping_method,
            'payment_method' => $payment_method,
            'comment' => $comment,
            'affiliate_id' => $affiliate_id,
            'order_status_id' => $order_status_id
        );
        $this->curl->setUrl($this->oc->getUrl('order/add'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function edit($order_id, $shipping_method = '', $comment = '', $affiliate_id = '', $order_status_id = '') {
        if (empty($order_id)) throw new InvalidDataException("Order ID cannot be empty for Order->edit()");
        $postData = array(
            'shipping_method' => $shipping_method,
            'comment' => $comment,
            'affiliate_id' => $affiliate_id,
            'order_status_id' => $order_status_id
        );
        $this->curl->setUrl($this->oc->getUrl('order/edit&order_id='.$order_id));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function delete($order_id) {
        if (empty($order_id)) throw new InvalidDataException("Order ID cannot be empty for Order->delete()");
        $this->curl->setUrl($this->oc->getUrl('order/delete&order_id='.$order_id));
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function history($order_id, $order_status_id = '', $notify = '', $append = '', $comment = '') {
        if (empty($order_id)) throw new InvalidDataException("Order ID cannot be empty for Order->edit()");
        $postData = array(
            'order_status_id' => $order_status_id,
            'notify' => $notify,
            'append' => $append,
            'comment' => $comment,
        );
        $this->curl->setUrl($this->oc->getUrl('order/history&order_id='.$order_id));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}