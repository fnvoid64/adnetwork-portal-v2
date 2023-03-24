<?php
namespace Pranto;

class User
{

    public $id;

    /**
     * User constructor.
     */
    public function __construct()
    {

        if ($this->is_user()) {

            $this->id = $_SESSION['id'];

        }
    }

    /**
     * @return bool
     */
    public function is_user()
    {

        if (isset($_SESSION['id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $var
     * @return mixed
     */
    public function data($var)
    {
        global $db;
        $code = $db->select("users", $var, array("id" => $this->id));
        $fetch = $code->fetch_assoc();
        return $fetch[$var];
    }

    /**
     * @param $var
     * @param $VAR
     * @return mixed
     */
    public function gdata($var, $VAR)
    {
        global $db;
        $code = $db->select("users", $var, $VAR);
        $fetch = $code->fetch_assoc();
        return $fetch[$var];
    }

    /**
     * @return bool
     */
    public function pin()
    {
        global $db, $init, $date;
        $pin = rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        if ($db->update("users", array("pin" => $pin), array("id" => $this->id))) {

            $mailBody = $init->mail("pin");
            $mailBody = str_replace("%pin%", $pin, $mailBody);
            $mailBody = str_replace("%username%", $this->data("username"), $mailBody);
            $mailBody = str_replace("%date%", $date, $mailBody);
            $subject = "New Pin";
            $this->mail($subject, $mailBody);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function exists($id)
    {
        global $db;
        $code = $db->select("users", "id", array("id" => $id));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        global $db;

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {

            $login = $db->select("users", "email,password", array("email" => $username));
            $errors = array();

            if ($login->num_rows < 1) {

                $errors[] = "Email not found!";

            }

            $result = $login->fetch_assoc();
            if ($result["password"] != md5($password)) {
                $errors[] = "Email and Password are incorrect!";
            }

            if (empty($errors)) {
                return true;
            } else {
                return false;
            }
        } else {

            $login = $db->select("users", "username,password", array("username" => $username));
            $errors = array();

            if ($login->num_rows < 1) {

                $errors[] = "Username not found!";

            }

            $result = $login->fetch_assoc();
            if ($result["password"] != md5($password)) {
                $errors[] = "Username and Password are incorrect!";
            }

            if (empty($errors)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $subject
     * @param $body
     * @param string $email
     * @return bool
     */
    public function mail($subject, $body, $email = "")
    {
        global $init;
        include ROOT . "/pranto/phpmailer.class.php";
        $mail = new \PHPMailer();

        if (empty($email)) {
            $email = $this->data("email");
        }
        $body = htmlspecialchars_decode($body);
        $mail->setFrom($init->mail("noreply"), $init->mail("name"));
        $mail->addReplyTo($init->mail("noreply"), $init->mail("name"));
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->msgHTML($body);
        $mail->AltBody = $body;

        return !$mail->send() ? false : true;
    }


}
		