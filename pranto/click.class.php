<?php
namespace Pranto;

class PRANTO_Click
{

    public $skey = "13f3dc0933e04bc9009373775d1f5587";

    public function ip()
    {

        return $_SERVER["REMOTE_ADDR"];
    }

    public function ua()
    {

        if (stristr($_SERVER['HTTP_USER_AGENT'], "Opera Mini")) {
            if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) {
                $browser = addslashes(strip_tags($_SERVER
                ['HTTP_X_OPERAMINI_PHONE_UA']));
            } else {
                $browser = addslashes(strip_tags($_SERVER
                ['HTTP_USER_AGENT']));
            }
        } else {
            $browser = addslashes(strip_tags($_SERVER
            ['HTTP_USER_AGENT']));
        }
        return $browser;
    }

    public function ip_exists()
    {

        global $db, $date;

        $code = $db->select("clicks", "id", array("date" => $date, "ip" => $this->ip()));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function imp_ip_exists()
    {

        global $db, $date;

        $code = $db->select("impressions", "id", array("date" => $date, "ip" => $this->ip()));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function cookie_exists()
    {


        if (isset($_COOKIE["click"])) {
            if (cookie("click") == 1) {
                return true;
            }
        } else {
            return false;
        }
    }

    public function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public function encode($value)
    {
        if (!$value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext));
    }

    public function decode($value)
    {
        if (!$value) {
            return false;
        }
        $crypttext = $this->safe_b64decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    public function ref()
    {
        return $_SERVER["HTTP_REFERER"];
    }
}

			
			
		
			