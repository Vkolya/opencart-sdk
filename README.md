# OpenCart SDK library
As a basis was used this project https://github.com/iSenseLabs/OpenCartAPI/blob/master/opencart.php 
# Installation
Require this package in your composer.json and update composer. This will download the package 
```php
composer require vkolya/opencart-sdk
```
# Basic usage
```php
use Vkolya\ocSDK\OpenCart;
```
Second parameter is name of file in your file system where will be store opencart session data for next requests(if you're not going to save session beetwen request you may not pass this parameter)
```php
$oc = new OpenCart('http://opencartsite,com','oc_api.dat');
//login method on success returns array with token and apiVersion
$oc->login('OC_API_TOKEN');
```
if you want to work  with api, for instance , not only in one class(controller) . In this case , you can save token and apiVersion on session 
```php
$_SESSION['op_session_data'] = $oc->login('OC_API_TOKEN');
```
And later in another controller call
```php
$oc = new OpenCart('http://opencartsite,com','oc_api.dat',$_SESSION['op_session_data']);
//add product to cart
$oc->cart->add(1, 1);
```



