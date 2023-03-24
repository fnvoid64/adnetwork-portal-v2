<?php
include 'init.php';
if (!$user->is_user()) {
    redir($$site_url . "/dashboard/login.html");
    exit();
}

if ($user->data("status") == 2) {
    $smarty->assign("title", "Account Unverified " . $site_name);
    $smarty->display("user_unverified.tpl");
    exit();
}
if ($user->data("status") == 0) {
    $smarty->assign("title", "Account Blocked " . $site_name);
    $smarty->display("user_blocked.tpl");
    exit();
}

$step = isset($_GET["step"]) ? get("step") : 1;
$smarty->assign("step", $step);
$site = new \Pranto\Site();

switch ($step) {
    case 2:
        $smarty->assign("title", "Verify Ownership");
        if (isset($_GET["id"])) {
            $id = get("id");

            if ($site->own_site($id, $user->id)) {

                if ($site->details($id, "status") == 2) {
                    if (isset($_POST["verify"]) && $_POST["verify"] == "site") {
                        $ch = curl_init($site->details($id, "url"));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        $data = curl_exec($ch);
                        curl_close($ch);

                        if (stristr($data, $site->details($id, "code")) === true) {
                            if ($db->update("sites", array("status" => 1), array("id" => $id))) {
                                header('Location: ' . $site_url . '/dashboard/new_site.html?step=3&id=' . $id);
                            } else {
                                $smarty->assign("errors", "MySQL Error Occured!");
                            }
                        } else {
                            $smarty->assign("errors", "Verification failed! Please add our meta tag to your site!");
                        }
                    }

                    $smarty->assign("code", $site->details($id, "code"));
                } elseif ($site->details($id, "status") == 1) {
                    header('Location: ' . $site_url . '/dashboard/new_site.html?step=3&id=' . $id);
                } else {
                    header('Location: ' . $site_url . '/dashboard/new_site.html');
                }
            } else {
                $smarty->assign("errors", "Site not found!");
            }
        } else {
            header('Location: ' . $site_url . '/dashboard/new_site.html');
        }
        break;
    case 3:
        $smarty->assign("title", "Setup adcodes");
        if (isset($_GET["id"])) {
            $id = get("id");

            if ($site->own_site($id, $user->id)) {
                if ($site->details($id, "status") == 1) {
                    $smarty->assign("id", $id);
                } elseif ($site->details($id, "status") == 2) {
                    header('Location: ' . $site_url . '/dashboard/new_site.html?step=2&id=' . $id);
                } else {
                    header('Location: ' . $site_url . '/dashboard/new_site.html');
                }

                $smarty->assign("show", 1);
            } else {
                $smarty->assign("errors", "Site not found!");
            }
        } else {
            header('Location: ' . $site_url . '/dashboard/new_site.html');
        }
        break;
    case 4:
        $smarty->assign("title", "Complete!");
        break;
    default:
        $smarty->assign("title", "Add new site");
        if (isset($_POST["name"], $_POST["url"], $_POST["category"], $_POST["description"])) {
            $name = post("name");
            $url = post("url");
            $category = post("category");
            $description = post("description");

            $errors = array();

            if (empty($name) || empty($url) || empty($category)) {
                $errors[] = "Required fields left empty.";
            }

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                $errors[] = "Please enter a valid URL";
            }

            if ($site->is_site($url, $user->id)) {
                $errors[] = "Site is already registered!";
            }

            if (empty($errors)) {
                $data = array(
                    "userid" => $user->id,
                    "name" => $name,
                    "url" => $url,
                    "category" => $category,
                    "description" => $description,
                    "status" => 2,
                    "code" => md5(microtime())
                );

                if ($db->insert("sites", $data)) {
                    header('Location: ' . $site_url . '/dashboard/new_site.html?step=2&id=' . $db->getConnection()->insert_id);
                } else {
                    $smarty->assign("errors", "MySQL Error Occured!");
                }
            } else {
                $smarty->assign("errors", $errors);
            }
        }
        break;
}

$smarty->display("new_site.tpl");