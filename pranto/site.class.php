<?php
namespace Pranto;

class Site
{

    public function details($id, $var)
    {
        global $db;
        $code = $db->select("sites", $var, array("id" => $id));
        $details = $code->fetch_assoc();
        return $details[$var];
    }

    public function valid_site($url)
    {
        $url = str_replace("http://", null, $url);
        return preg_match('/([a-zA-Z0-9\-\.])\.([a-zA-Z0-9\-\.])/', $url) ? true : false;
    }

    public function is_site($url, $userid)
    {
        $url = str_replace("http://", null, $url);
        global $db;
        $code = $db->select("sites", "url", array("url" => $url, "userid" => $userid));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function own_site($id, $userid)
    {
        global $db;
        $code = $db->select("sites", "url", array("id" => $id, "userid" => $userid));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function exists($id)
    {
        global $db;
        $code = $db->select("sites", "name", array("id" => $id));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

}