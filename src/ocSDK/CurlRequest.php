<?php

namespace Vkolya\ocSDK;

class CurlRequest {
    private $url;
    private $postData = array();
    private $cookies = array();
    private $response = '';
    private $handle;
    private $sessionFile;
    
    private function getCookies() {
        $cookies = array();
        
        foreach ($this->cookies as $name=>$value) {
            $cookies[] = $name . '=' . $value;
        }
        return implode('; ', $cookies);
    }
    private function saveSession() {
        if (empty($this->sessionFile)) return;
        if (!file_exists(dirname($this->sessionFile))) {
            mkdir(dirname($this->sessionFile), 0755, true);
        }
        file_put_contents($this->sessionFile, json_encode($this->cookies));
    }
    private function restoreSession() {
        if (!empty($this->sessionFile) && file_exists($this->sessionFile)) {
           
            $this->cookies = json_decode(file_get_contents($this->sessionFile), true);
         
        }
    }
    public function __construct($sessionFile) {
        $this->sessionFile = $sessionFile;
        $this->restoreSession();
    }
    public function makeRequest() {
     
        $this->handle = curl_init($this->url);
        curl_setopt($this->handle, CURLOPT_HEADER, true);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_POST, true);
        
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, http_build_query($this->postData));
        if (!empty($this->cookies)) {
            curl_setopt($this->handle, CURLOPT_COOKIE, $this->getCookies());
        }
      
        $this->response = curl_exec($this->handle);
        $header_size = curl_getinfo($this->handle, CURLINFO_HEADER_SIZE);
        $headers = substr($this->response, 0, $header_size);
        $this->response = substr($this->response, $header_size);
         
        //Save cookies
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $matches);
        $cookies = $matches[1];
        
        foreach ($cookies as $cookie) {
            
            $parts = explode('=', $cookie);
            $name = array_shift($parts);
            $value = implode('=', $parts);
            $this->cookies[$name] = $value;
        }
        $this->saveSession();
         
        curl_close($this->handle);
        
    }
    public function setUrl($url) {
        $this->url = $url;
    }
    public function setData($postData) {
        $this->postData = $postData;
    }
    public function getResponse() { return json_decode($this->response, true); }
    public function getRawResponse() { return $this->response; }
}
