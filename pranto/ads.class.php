<?php
namespace Pranto;

class Ads
{

    public function details($id, $var)
    {

        global $db;
        $code = $db->select("ads", $var, array("id" => $id));
        $fetch = $code->fetch_assoc();
        return $fetch[$var];

    }

    public function exists($id)
    {

        global $db, $user;
        $code = $db->select("ads", "name", array("id" => $id, "userid" => $user->id));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function ad_exists($id)
    {

        global $db;
        $code = $db->select("ads", "name", array("id" => $id));
        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

}
			
		