<?php
namespace Vkolya\ocSDK\Modules;
use Vkolya\ocSDK\Base;
class Voucher extends Base {
    public function apply($voucher) {
        if (empty($voucher)) throw new InvalidDataException("Voucher cannot be empty for Voucher->apply()");
        $postData = array(
            'voucher' => $voucher
        );
        $this->curl->setUrl($this->oc->getUrl('voucher'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
    public function add($voucher_from_name = '', $from_email = '', $to_name = '', $to_email = '', $voucher_theme_id = '', $message = '', $amount = '') {
        if (is_array($voucher_from_name)) {
            $postData = array(
                'voucher' => $voucher_from_name
            );
        } else {
            $postData = array(
				'from_name' => $voucher_from_name,
				'from_email' => $from_email,
				'to_name' => $to_name,
				'to_email' => $to_email,
				'voucher_theme_id' => $voucher_theme_id,
				'message' => $message,
				'amount' => $amount
            );
        }
        $this->curl->setUrl($this->oc->getUrl('voucher/add'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}


