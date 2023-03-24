<?php
namespace Pranto;

class Captcha
{
    private static $secretKey, $siteKey;

    public function __construct($siteKey, $secretKey)
    {
        self::$siteKey = $siteKey;
        self::$secretKey = $secretKey;
    }

    public function show()
    {
        return '<div class="g-recaptcha" data-sitekey="' .self::$siteKey. '"></div>';
    }

    public function verify($post)
    {
        if ($post) {
            $ch = curl_init("https://www.google.com/recaptcha/api/siteverify?secret=" .self::$secretKey. "&response=" .$post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $response = json_decode(curl_exec($ch));
            curl_close($ch);

            return $response->success === true ? true : false;
        } else {
            return false;
        }
    }
}