<?php

session_start();

require ('vendor/autoload.php');   
use Gregwar\Captcha\CaptchaBuilder;

$captcha = new CaptchaBuilder;
$_SESSION['phrase'] = $captcha->getPhrase();

header('Content-Type: image/jpeg');

$captcha
    ->build()
    ->output()
;