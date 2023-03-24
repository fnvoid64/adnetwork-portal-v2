<?php
include "init.php";

$act = isset($_GET["act"]) ? get("act") : "";

$prefered_account = $db->select("payment_account", "type", ["userid" => $user->id]);

if ($prefered_account->num_rows != 0) {
    $smarty->assign("prefered", $prefered_account->fetch_assoc());
}

switch ($act) {
    case 'skrill':
        $smarty->assign("title", "Skrill Account");

        $check = $db->select("payment_account", "id,name,email", ["userid" => $user->id, "type" => "skrill"]);

        $payment = $check->num_rows == 0 ? 0 : $check->fetch_assoc();

        if (isset($_POST["name"], $_POST["email"], $_POST["pin"])) {
            $name = post("name");
            $email = post("email");
            $pin = post("pin");

            $errors = [];

            if (empty($name) || empty($email) || empty($pin)) $errors[] = "All fields are required";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
            if ($user->data("pin") != $pin) $errors[] = "PIN is invalid!";
            $prefered = isset($_POST["prefered"]) && $_POST["prefered"] == 1 ? 1 : 0;

            $ch_pay = $db->countRow("payment_account", "id", ["userid" => $user->id]);
            if ($ch_pay == 0) $prefered = 1;

            if (empty($errors)) {
                if ($prefered == 1) {
                    $db->update("payment_account", ["prefered" => 0], []);
                }

                if ($payment == 0) {
                    if ($db->insert("payment_account", ["name" => $name, "email" => $email, "type" => "skrill", "prefered" => $prefered])) {
                        $smarty->assign("message", "Skrill account info updated successfully!");
                    } else {
                        $smarty->assign("errors", $errors);
                    }
                } else {
                    if ($db->update("payment_account", ["name" => $name, "email" => $email, "prefered" => $prefered], ["id" => $payment["id"]])) {
                        $smarty->assign("message", "Skrill account info updated successfully!");
                    } else {
                        $smarty->assign("errors", $errors);
                    }
                }
            }
        }

        $smarty->assign("payment", $payment);
        $smarty->display("payment_skrill.tpl");
        break;

    case 'bank':
        $smarty->assign("title", "Bank Account");

        $check = $db->select("payment_account", "id,name,email", ["userid" => $user->id, "type" => "skrill"]);

        $payment = $check->num_rows == 0 ? 0 : $check->fetch_assoc();

        if (isset($_POST["name"], $_POST["ac_number"], $_POST["bank_name"], $_POST["bank_address"], $_POST["ifsc"], $_POST["swift"], $_POST["tax"], $_POST["pin"])) {
            $name = post("name");
            $ac_number = post("ac_number");
            $bank_name = post("bank_name");
            $bank_address = post("bank_address");
            $ifsc = post("ifsc");
            $swift = post("swift");
            $tax = post("tax");
            $pin = post("pin");

            $errors = [];

            if (empty($name) || empty($ac_number) || empty($bank_name) || empty($bank_address) || empty($ifsc)) $errors[] = "All fields are required";
            if ($user->data("pin") != $pin) $errors[] = "PIN is invalid!";
            $prefered = isset($_POST["prefered"]) && $_POST["prefered"] == 1 ? 1 : 0;

            $ch_pay = $db->countRow("payment_account", "id", ["userid" => $user->id]);
            if ($ch_pay == 0) $prefered = 1;

            if (empty($errors)) {
                if ($prefered == 1) {
                    $db->update("payment_account", ["prefered" => 0], []);
                }

                if ($payment == 0) {
                    if ($db->insert("payment_account", ["name" => $name, "ac_number" => $ac_number, "type" => "bank", "prefered" => $prefered, "bank_name" => $bank_name, "bank_address" => $bank_address, "ifsc" => $ifsc, "swift" => $swift, "tax" => $tax])) {
                        $smarty->assign("message", "Bank account info updated successfully!");
                    } else {
                        $smarty->assign("errors", $errors);
                    }
                } else {
                    if ($db->update("payment_account", ["name" => $name, "ac_number" => $ac_number, "prefered" => $prefered, "bank_name" => $bank_name, "bank_address" => $bank_address, "ifsc" => $ifsc, "swift" => $swift, "tax" => $tax], ["id" => $payment["id"]])) {
                        $smarty->assign("message", "Bank account info updated successfully!");
                    } else {
                        $smarty->assign("errors", $errors);
                    }
                }
            }
        }

        $smarty->assign("payment", $payment);
        $smarty->display("payment_bank.tpl");
        break;

    case 'prefered':
        $id = get("id");
        $ch_pay = $db->countRow("payment_account", "id", ["id" => $id, "userid" => $user->id]);

        if ($ch_pay == 0) {
            echo "Payment Account Not Found!";
        } else {
            $db->update("payment_account", ["prefered" => 0], []);

            if ($db->update("payment_account", ["prefered" => 1], ["id" => $id])) {
                // Success
            } else {
                // Error
            }
        }
        break;

    default:
        $smarty->assign("title", "PayPal Account");

        $check = $db->select("payment_account", "id,name,email", ["userid" => $user->id, "type" => "paypal"]);

        $payment = $check->num_rows == 0 ? 0 : $check->fetch_assoc();

        if (isset($_POST["name"], $_POST["email"], $_POST["pin"])) {
            $name = post("name");
            $email = post("email");
            $pin = post("pin");

            $errors = [];

            if (empty($name) || empty($email) || empty($pin)) $errors[] = "All fields are required";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
            if ($user->data("pin") != $pin) $errors[] = "PIN is invalid!";
            $prefered = isset($_POST["prefered"]) && $_POST["prefered"] == 1 ? 1 : 0;

            $ch_pay = $db->countRow("payment_account", "id", ["userid" => $user->id]);
            if ($ch_pay == 0) $prefered = 1;

            if (empty($errors)) {
                if ($prefered == 1) {
                    $db->update("payment_account", ["prefered" => 0], []);
                }

                if ($payment == 0) {
                    if ($db->insert("payment_account", ["name" => $name, "email" => $email, "type" => "paypal", "prefered" => $prefered])) {
                        $smarty->assign("message", "PayPal account info updated successfully!");
                    } else {
                        $smarty->assign("errors", $errors);
                    }
                } else {
                    if ($db->update("payment_account", ["name" => $name, "email" => $email, "prefered" => $prefered], ["id" => $payment["id"]])) {
                        $smarty->assign("message", "PayPal account info updated successfully!");
                    } else {
                        $smarty->assign("errors", $errors);
                    }
                }
            }
        }

        $smarty->assign("payment", $payment);
        $smarty->display("payment_paypal.tpl");
        break;
}