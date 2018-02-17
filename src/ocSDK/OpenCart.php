<?php
namespace Vkolya\ocSDK;


use Vkolya\ocSDK\CurlRequest;
use Vkolya\ocSDK\Modules\Cart;
use Vkolya\ocSDK\Modules\Order;
use Vkolya\ocSDK\Modules\Payment;
use Vkolya\ocSDK\Modules\Reward;
use Vkolya\ocSDK\Modules\Shipping;
use Vkolya\ocSDK\Modules\Voucher;


use Vkolya\ocSDK\Exceptions\UnknownOpenCartVersionException;
use Vkolya\ocSDK\Exceptions\InvalidCredentialsException;
use Vkolya\ocSDK\Exceptions\InvalidDataException;

class OpenCart {

    const API_VERSION_AUTO = 0;
    const API_VERSION_1 = 1;
    const API_VERSION_2 = 2;
    const API_VERSION_3 = 3;
    
    private $url;
    public $apiVersion;
    public $curl;
    public $cart;
    public $order;
    public $payment;
    public $reward;
    public $shipping;
    public $voucher;
    /**
     * Initialize opencart session
     *
     * @param  string   $site Address of opencart site
     * @param  string   $sessionFile File name where stored session data of opencart
     * @param  array    $restore_data array contains token and API version or empty array for first login
     */
    public function __construct($site,$sessionFile = '', $restore_data = []) {
       
        $this->url = (!preg_match('/^https?\:\/\//', $site) ? 'http://' : '') . rtrim($site, '/') . '/index.php?';
        $this->apiVersion = $restore_data['apiVersion'] ?? OpenCart::API_VERSION_AUTO;
        $this->token = $restore_data['token'] ?? '';
        $this->curl = new CurlRequest($sessionFile);
        $this->cart = new Cart($this);
        $this->order = new Order($this);
        $this->payment = new Payment($this);
        $this->reward = new Reward($this);
        $this->shipping = new Shipping($this);
        $this->voucher = new Voucher($this);
    }
   
    public function __get($name) {
        $voidProp = new Base($this);
        return $voidProp->{$name};
    }

    public function getUrl($method) {
        
        switch ($this->apiVersion) {
            case OpenCart::API_VERSION_AUTO:
                return $this->url . 'api_token=' . $this->token . '&route=api/' . $method;
                break;
            case OpenCart::API_VERSION_1:
                return $this->url . 'route=api/' . $method;
                break;
            case OpenCart::API_VERSION_2:
                return $this->url . 'token=' . $this->token . '&route=api/' . $method;
                break;
            case OpenCart::API_VERSION_3:
                return $this->url . 'api_token=' . $this->token . '&route=api/' . $method;
                break;
            default:
                
                throw new UnknownOpenCartVersionException("Unknown OpenCart Version");
                break;
        }
    }
    /**
     * Login user thought API
     *
     * @return array|string  Token and API version or error message
    */    
    public function login() {
        
        $args = func_get_args();
        $argsCount = count($args);
       
        $this->curl->setUrl($this->getUrl('login'));
        switch ($argsCount) {
            case 0:
                throw new InvalidCredentialsException("Login called with no parameters! Please provide either an API key, or username and password for OpenCart versions older than 2.0.3.1");
                break;
            case 1:
                $apiKey = $args[0];
                if (empty($apiKey))
                    throw new InvalidCredentialsException("API key cannot be empty");
                $this->curl->setData(array(
                    'key' => $apiKey,
                ));
                break;
            case 2:
                list($username, $password) = $args;
                if (empty($username) || empty($password))
                    throw new InvalidCredentialsException("Username and password cannot be empty");
                $this->curl->setData(array(
                    'username' => $username,
                    'password' => $password,
                    'key' => $password
                ));
                break;
            default:
                throw new InvalidCredentialsException("Login called with invalid number of parameters! Please provide either an API key, or username and password for OpenCart versions older than 2.0.3.1");
                break;
        }
        $this->curl->makeRequest();
        $response = $this->curl->getResponse();
        
        $output_data = [];
        
        if (isset($response['success'])) {
            if (isset($response['cookie'])) {
                $this->apiVersion = OpenCart::API_VERSION_1;
                $this->token = $response['cookie'];
            } else if (isset($response['token'])) {
                $this->apiVersion = OpenCart::API_VERSION_2;
                $this->token = $response['token'];
            } else if (isset($response['api_token'])) {
                $this->apiVersion = OpenCart::API_VERSION_3;
                $this->token = $response['api_token'];
            }
            
            return $output_data = [
                'apiVersion' => $this->apiVersion,
                'token'      => $this->token
            ];
            
        } else if (isset($response['error'])) {
            return $response['error'];
        }
     
    }

    public function coupon($coupon) {
        if (empty($coupon))
            throw new InvalidDataException('Coupon cannot be empty for OpenCart->coupon()');
        $postData = array(
            'coupon' => $coupon
        );
        $this->curl->setUrl($this->getUrl('coupon'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }

    public function customer($customer_id = 0, $customer_group_id = 0, $firstname = '', $lastname = '', $email = '', $telephone = '', $fax = '', $extra = array()) {
        $postData = array(
            'customer_id' => $customer_id,
            'customer_group_id' => $customer_group_id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'telephone' => $telephone,
            'fax' => $fax,
                ) + $extra;

        $this->curl->setUrl($this->getUrl('customer'));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }

}

