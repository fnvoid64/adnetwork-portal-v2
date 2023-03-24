<?php
namespace Pranto;

class Register
{
    public function is_email($email)
    {
        global $db;
        $code = $db->select("users", "id", array("email" => $email));
        if ($code->num_rows == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function verify($email, $token)
    {
        global $db;
        $errors = array();

        if (!$this->is_email($email)) {
            $errors[] = "Email is not registered!";
        }
        $code = $db->select("verify", "email,token", array("email" => $email));
        $Token = $code->fetch_assoc();
        return ($Token["token"] == $token) ? true : false;
    }
}