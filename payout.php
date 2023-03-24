<?php
include "init.php";
	
if (!$user->is_user()) {
	redir($setting["siteurl"]."/login");
	exit();
}
	
if ($user->data("status") == 2) {
	$smarty->assign("title","Account Unverified ".$setting["sitename"]);
	template("user_unverified.tpl");
	exit();
}
	
if ($user->data("status") == 0) {
	$smarty->assign("title","Account Blocked ".$setting["sitename"]);
	template("user_blocked.tpl");
	exit();
}

// Payments Type
$type = get("type");

// Message
if (isset($_SESSION["message"])) {
	$smarty->assign("message", $_SESSION["message"]);
	unset($_SESSION["message"]);
}

// Route Through
switch ($type) {
	case 'paypal':
		// Title
		$smarty->assign("title", "PayPal Payment Request");

		// Check Balance
		if ($user->data("pbal") < 5) {
			$smarty->assign("low_balance", "yes");
		} else {
			if ($user->data("country") == "IN") {
				$smarty->assign("unsupported_country", "yes");
			} else {
				if (isset($_POST["name"], $_POST["email"], $_POST["amount"])) {
					$name = post("name");
					$email = post("email");
					$amount = post("amount");
					$pin = post("pin");

					// Errors
					$errors = array();

					if (empty($name) || empty($email) || empty($amount))		$errors[] = "Please fill up all the fields!";
					if (!filter_var($email, FILTER_VALIDATE_EMAIL))				$errors[] = "Email is Invaild!";
					if (!is_numeric($amount) || $amount > $user->data("pbal") || $amount < 5)	$errors[] = "Invalid Amount!";
					if (!is_numeric($pin) || strlen($pin) != 6 || $user->data("pin") != $pin)	$errors[] = "Wrong PIN!";

					// No Errors
					if (empty($errors)) {
						// Invoice ID
						$invoice = strtoupper(substr(md5(microtime()),0,7));

						// Jobs
						$job_1 = $db->update("users", array("pbal" => ($user->data("pbal") - $amount)), array("id" => $user->id));
						$job_2 = $db->insert("invoices", array("userid" => $user->id, "amount" => $amount, "date" => $date, "invoice" => $invoice, "method" => "PayPal", "via" => $email, "payee_name" => $name, "status" => 2), "");

						// Job Complete Boss
						if ($job_1 && $job_2) {
							// Success Message
							$_SESSION["message"] = "Withdrawl Request Recieved! Invoice #$invoice Successfully Created!";
							
							// Email
							$mailBody = $init->mail("invoicec");
							$mailBody = str_replace("%date%", $date, $mailBody);
							$mailBody = str_replace("%username%", $user->data("username"), $mailBody);
							$mailBody = str_replace("%invoice%", $invoice, $mailBody);
							$subject = "Invoice #$invoice Created";

							// Send Email
							$user->mail($subject,$mailBody);

							// Redirect
							redir($setting["siteurl"] . "/dashboard");
						} else {
							echo "<i>System Error: [MySQL]</i>";
							exit();
						}
					} else {
						$smarty->assign("errors", $errors);
					}
				}
			}
		}

		// Load Template
		template("payout-paypal.tpl");
		break;
	case 'skrill':
		// Title
		$smarty->assign("title", "Skrill Payment Request");

		// Check Balance
		if ($user->data("pbal") < 5) {
			$smarty->assign("low_balance", "yes");
		} else {
			if (isset($_POST["name"], $_POST["email"], $_POST["amount"])) {
				$name = post("name");
				$email = post("email");
				$amount = post("amount");
				$pin = post("pin");

				// Errors
				$errors = array();

				if (empty($name) || empty($email) || empty($amount))		$errors[] = "Please fill up all the fields!";
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))				$errors[] = "Email is Invaild!";
				if (!is_numeric($amount) || $amount > $user->data("pbal") || $amount < 5)	$errors[] = "Invalid Amount!";
				if (!is_numeric($pin) || strlen($pin) != 6 || $user->data("pin") != $pin)	$errors[] = "Wrong PIN!";

				// No Errors
				if (empty($errors)) {
					// Invoice ID
					$invoice = strtoupper(substr(md5(microtime()),0,7));

					// Jobs
					$job_1 = $db->update("users", array("pbal" => ($user->data("pbal") - $amount)), array("id" => $user->id));
					$job_2 = $db->insert("invoices", array("userid" => $user->id, "amount" => $amount, "date" => $date, "invoice" => $invoice, "method" => "Skrill", "via" => $email, "payee_name" => $name, "status" => 2), "");

					// Job Complete Boss
					if ($job_1 && $job_2) {
						// Success Message
						$_SESSION["message"] = "Withdrawl Request Recieved! Invoice #$invoice Successfully Created!";
						
						// Email
						$mailBody = $init->mail("invoicec");
						$mailBody = str_replace("%date%", $date, $mailBody);
						$mailBody = str_replace("%username%", $user->data("username"), $mailBody);
						$mailBody = str_replace("%invoice%", $invoice, $mailBody);
						$subject = "Invoice #$invoice Created";

						// Send Email
						$user->mail($subject,$mailBody);

						// Redirect
						redir($setting["siteurl"] . "/dashboard");
					} else {
						echo "<i>System Error: [MySQL]</i>";
						exit();
					}
				} else {
					$smarty->assign("errors", $errors);
				}
			}
		}

		// Load Template
		template("payout-skrill.tpl");
		break;
	case 'airtel_money':
		// Title
		$smarty->assign("title", "Airtel Money Payment Request");

		// Check Balance
		if ($user->data("pbal") < 2) {
			$smarty->assign("low_balance", "yes");
		} else {
			if ($user->data("country") != "IN") {
				$smarty->assign("unsupported_country", "yes");
			} else {
				if (isset($_POST["number"], $_POST["amount"])) {
					$number = post("number");
					$amount = post("amount");
					$pin = post("pin");

					// Errors
					$errors = array();

					if (empty($number) || empty($amount))		$errors[] = "Please fill up all the fields!";
					if (!is_numeric($amount) || $amount > $user->data("pbal") || $amount < 2)	$errors[] = "Invalid Amount!";
					if (!is_numeric($pin) || strlen($pin) != 6 || $user->data("pin") != $pin)	$errors[] = "Wrong PIN!";

					// No Errors
					if (empty($errors)) {
						// Invoice ID
						$invoice = strtoupper(substr(md5(microtime()),0,7));

						// Jobs
						$job_1 = $db->update("users", array("pbal" => ($user->data("pbal") - $amount)), array("id" => $user->id));
						$job_2 = $db->insert("invoices", array("userid" => $user->id, "amount" => $amount, "date" => $date, "invoice" => $invoice, "method" => "Airtel Money", "via" => $number, "payee_name" => "N/A", "status" => 2), "");

						// Job Complete Boss
						if ($job_1 && $job_2) {
							// Success Message
							$_SESSION["message"] = "Withdrawl Request Recieved! Invoice #$invoice Successfully Created!";
							
							// Email
							$mailBody = $init->mail("invoicec");
							$mailBody = str_replace("%date%", $date, $mailBody);
							$mailBody = str_replace("%username%", $user->data("username"), $mailBody);
							$mailBody = str_replace("%invoice%", $invoice, $mailBody);
							$subject = "Invoice #$invoice Created";

							// Send Email
							$user->mail($subject,$mailBody);

							// Redirect
							redir($setting["siteurl"] . "/dashboard");
						} else {
							echo "<i>System Error: [MySQL]</i>";
							exit();
						}
					} else {
						$smarty->assign("errors", $errors);
					}
				}
			}
		}

		// Load Template
		template("payout-airtel_money.tpl");
		break;
	case 'recharge':
		// Title
		$smarty->assign("title", "Mobile Recharge Payment Request");

		// INR Balance
		$balance = round($user->data("pbal") * 60, 2);
		// Check Balance
		if ($balance < 10) {
			$smarty->assign("low_balance", "yes");
		} else {
			if ($user->data("country") != "IN") {
				$smarty->assign("unsupported_country", "yes");
			} else {
				if (isset($_POST["number"], $_POST["operator"], $_POST["circle"], $_POST["amount"])) {
					$number = post("number");
					$number = str_replace("+", null, $number);
					$operator = post("operator");
					$circle = post("circle");
					$amount = post("amount");
					$pin = post("pin");

					// Errors
					$errors = array();

					if (empty($number) || empty($operator) || empty($circle))	$errors[] = "Please fill in all the fields!";
					if (!is_numeric($number))									$errors[] = "Mobile number is invalid!";
					if (!is_numeric($amount) || $amount > $balance || $amount < 10)				$errors[] = "Invalid Amount!";
					if (!is_numeric($pin) || strlen($pin) != 6 || $user->data("pin") != $pin)	$errors[] = "Wrong PIN!";

					// No Errors, Go Time!
					if (empty($errors)) {
						// Invoice ID
						$invoice = strtoupper(substr(md5(microtime()),0,7));

						// Jobs
						$job_1 = $db->update("users", array("pbal" => ($user->data("pbal") - ($amount/60))), array("id" => $user->id));
						$job_2 = $db->insert("invoices", array("userid" => $user->id, "amount" => $amount, "date" => $date, "invoice" => $invoice, "method" => "Mobile Recharge", "via" => $number, "payee_name" => "N/A", "operator" => $operator, "circle" => $circle, "status" => 2), "");

						// Job Complete Boss
						if ($job_1 && $job_2) {
							// Success Message
							$_SESSION["message"] = "Withdrawl Request Recieved! Invoice #$invoice Successfully Created!";
							
							// Email
							$mailBody = $init->mail("invoicec");
							$mailBody = str_replace("%date%", $date, $mailBody);
							$mailBody = str_replace("%username%", $user->data("username"), $mailBody);
							$mailBody = str_replace("%invoice%", $invoice, $mailBody);
							$subject = "Invoice #$invoice Created";

							// Send Email
							$user->mail($subject, $mailBody);

							// Redirect
							redir($setting["siteurl"] . "/dashboard");
						} else {
							echo "<i>System Error: [MySQL]</i>";
							exit();
						}
					} else {
						$smarty->assign("errors", $errors);
					}
				}
			}
		}

		// Load Template
		template("payout-recharge.tpl");
		break;
	case 'bank':
		// Title
		$smarty->assign("title", "Bank Payment Request");

		// Check Balance
		if ($user->data("pbal") < 5) {
			$smarty->assign("low_balance", "yes");
		} else {
			if ($user->data("country") != "IN") {
				$smarty->assign("unsupported_country", "yes");
			} else {
				$check_account = $db->select("bank_accounts", "id,bank_name,account_number", array("userid" => $user->id));

				if ($check_account->num_rows == 0) {
					if (isset($_POST["name"], $_POST["number"], $_POST["bank_name"], $_POST["bank_address"], $_POST["neft_ifsc"], $_POST["swift"], $_POST["pan"])) {
						$name = post("name");
						$number = post("number");
						$bank_name = post("bank_name");
						$bank_address = post("bank_address");
						$neft_ifsc = post("neft_ifsc");
						$swift = post("swift");
						$pan = post("pan");

						// Here comes the Errors
						$errors = array();

						if (empty($name) || empty($number) || empty($bank_name) || empty($bank_address) || empty($neft_ifsc) || empty($swift)) {
							$errors[] = "Please fill in  all the fields!";
						}

						$bank_account_check = $db->select("bank_accounts", "id", array("id" => $number));

						if ($bank_account_check->num_rows != 0)		$errors[] = "Bank Account is already registered!";
						$bank_account_check = null;

						if (!is_numeric($number))	$errors[] = "Account number is invalid!";

						// No Errors? Let's go babe!
						if (empty($errors)) {
							$job = $db->insert("bank_accounts", array("userid" => $user->id, "name" => $name, "account_number" => $number, "bank_name" => $bank_name, "bank_address" => $bank_address, "neft_ifsc" => $neft_ifsc, "swift_code" => $swift, "pan_tax" => $pan), "");

							if ($job) {
								redir("/payout?type=bank");
							}
						} else {
							$smarty->assign("errors", $errors);
						}
					}

					// Template
					template("add-bank.tpl");
					exit();
				} else {
					if (isset($_POST["bank_account"], $_POST["amount"])) {
						$bank_account = post("bank_account");
						$amount = post("amount");
						$pin = post("pin");

						// Errors
						$errors = array();

						if (empty($bank_account) || empty($amount))		$errors[] = "Please fill up all the fields!";
						
						// Check Account
						$bank_account_check = $db->select("bank_accounts", "id", array("id" => $bank_account, "userid" => $user->id));

						if ($bank_account_check->num_rows == 0)		$errors[] = "Bank account not found!";
						$bank_account_check = null;

						if (!is_numeric($amount) || $amount > $user->data("pbal") || $amount < 5)	$errors[] = "Invalid Amount!";
						if (!is_numeric($pin) || strlen($pin) != 6 || $user->data("pin") != $pin)	$errors[] = "Wrong PIN!";

						// No Errors
						if (empty($errors)) {
							// Invoice ID
							$invoice = strtoupper(substr(md5(microtime()),0,7));

							// Bank Data
							$bank_data = $check_account->fetch_object();

							// Jobs
							$job_1 = $db->update("users", array("pbal" => ($user->data("pbal") - $amount)), array("id" => $user->id));
							$job_2 = $db->insert("invoices", array("userid" => $user->id, "amount" => $amount, "date" => $date, "invoice" => $invoice, "method" => "Bank", "via" => $bank_data->id, "payee_name" => $bank_data->name, "status" => 2), "");

							// Job Complete Boss
							if ($job_1 && $job_2) {
								// Success Message
								$_SESSION["message"] = "Withdrawl Request Recieved! Invoice #$invoice Successfully Created!";
								
								// Email
								$mailBody = $init->mail("invoicec");
								$mailBody = str_replace("%date%", $date, $mailBody);
								$mailBody = str_replace("%username%", $user->data("username"), $mailBody);
								$mailBody = str_replace("%invoice%", $invoice, $mailBody);
								$subject = "Invoice #$invoice Created";

								// Send Email
								$user->mail($subject,$mailBody);

								// Redirect
								redir($setting["siteurl"] . "/dashboard");
							} else {
								echo "<i>System Error: [MySQL]</i>";
								exit();
							}
						} else {
							$smarty->assign("errors", $errors);
						}
					}

					// Bank Data
					$bank_data = $check_account->fetch_assoc();

					// Bank Details
					$smarty->assign("bank_name", $bank_data["bank_name"]);
					$smarty->assign("bank_account", $bank_data["account_number"]);
					$smarty->assign("bank_id", $bank_data["id"]);
				}
			}
		}

		// Load Template
		template("payout-bank.tpl");
		break;
	default:
		// Title
		$smarty->assign("title", "Request Payout");

		$visible = array();

		if ($user->data("country") != "IN") {
			$visible["paypal"] = 1;
		} elseif ($user->data("country") == "IN") {
			$visible["bank"] = 1;
			$visible["airtel_money"] = 1;
			$visible["recharge"] = 1;
			$visible["paypal"] = null;
		} else {
			$visible["skrill"] = 1;
		}

		$visible["skrill"] = 1;

		$payment_methods = array(
			"paypal" => array(
				"url" => "paypal",
				"name" => "PayPal",
				"minimum" => 5,
				"visible" => $visible["paypal"]
			),
			"skrill" => array(
				"url" => "skrill",
				"name" => "Skrill",
				"minimum" => 5,
				"visible" => $visible["skrill"]
			),
			"airtel_money" => array(
				"url" => "airtel_money",
				"name" => "Airtel Money",
				"minimum" => 2,
				"visible" => $visible["airtel_money"]
			),
			"recharge" => array(
				"url" => "recharge",
				"name" => "Mobile Recharge",
				"minimum" => 10,
				"visible" => $visible["recharge"]
			),
			"bank" => array(
				"url" => "bank",
				"name" => "Bank Transfer",
				"minimum" => 5,
				"visible" => $visible["bank"]
			)
		);

		// Payments
		$smarty->assign("methods", $payment_methods);

		// Template
		template("payout.tpl");
		break;
}

?>