<?php
include "init.php";
	
if (!$admin->is_admin()) {
	redir("login.php");
	exit();
}
	
$act = get("act");
	
if ($act == "search" && isset($_GET["q"])) {
	$admin->head("Search Invoices");
	echo '<section class="content-header">';
	$q = get("q");
		
	if(empty($q) OR strlen($q)<1){
		echo "QueryStringIsEmpty!";
		exit();
	}

	$page = get("page");
	
	if(empty($page)) 	$page = 1;
		
	$start = ($page-1) * 30;
	$end = 30;
	$search = $db->link->query("SELECT id,invoice,amount,userid,status,method FROM invoices WHERE invoice LIKE '%$q%' LIMIT $start,$end");
		
	if($search->num_rows>0){
		echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Search Result For '.$q.'</h3>
		</div><div class="panel-body">';
		echo '<table class="table table-striped table-hover table-bordered">';
		echo '<th class="text-center">ID #</th><th class="text-center">Invoice #</th><th class="text-center">Status</th><th class="text-center">Amount</th><th class="text-center">Owner</th>';
		
		while ($result=$search->fetch_assoc()) {
			if($result["status"]==1){
				$status = '<font color="green">Paid</font>';
			}
			elseif($result["status"]==2){
				$status = '<font color="#ff6600">Pending</font>';
			}
			elseif($result["status"]==3){
				$status = '<font color="grey">Cancelled</font>';
			}
			else {
				$status = '<font color="red">Rejected</font>';
			}

			$amount = $result["method"] == "Mobile Recharge" ? "RS. " : "\$";
			$amount .= $result["amount"];

			echo '<tr class="text-center">
			<td>'.$result["id"].'</td>
			<td><a href="invoices.php?act=details&id='.$result["id"].'">#'.$result["invoice"].'</a></td>
			<td>'.$status.'</td>
			<td><span class="text-success text-bold">'.$amount.'</td>
			<td><a href="users.php?act=details&id='.$result["userid"].'">'.$user->gdata("username", array("id" => $result["userid"])).'</a></td>
			</tr>';
		}

		echo '</table></div></div>';
		$init->paging_admin($page, $search->num_rows, 30);
	} else {
			echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
			<div class="panel-heading">
			<h3 class="panel-title">Search Result For '.$q.'</h3>
			</div><div class="panel-body">No Invoices Found For "'.$q.'"</div></div>';
	}
	
	echo '</div></div>';		
} elseif($act == "edit" && isset($_GET["id"])) {
		$admin->head("Edit Invoice");
		echo '<section class="content-header">';

		$id = get("id");
		if(!invoice_exists($id)){
			echo "InvoiceNotFound!";
			exit;
		}
		
		echo '<div class="row">
		<div class="col-md-12">
		<div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Edit Invoice</h3>
		</div>
		<div class="panel-body">';
		
		if(isset($_POST["via"],$_POST["amount"])){
			
			$via = post("via");
			$amount = post("amount");
			
			$errors = array();
			if(empty($via) OR strlen($via)<1) 	$errors[] = "Payment Via is Empty!";
			if(empty($amount) OR strlen($amount)<1 OR !is_numeric($amount)) 	$errors[] = "Invalid Amount!";
			
			if(empty($errors)){
				if($db->update("invoices",array("via"=>$via,"amount"=>$amount),array("id"=>$id))){
					$_SESSION["message"] = "Invoice Updated Successfully!";
					redir("invoices.php?act=details&id=".$id);
				}
				else {
					echo 'Unknown Error';
				}
			}
			else {
				$admin->error($errors);
			}
		}
		
		echo '<form method="post">';
		echo '
		<div class="form-group">
		<label for="invoice">Invoice ID</label>
		<input type="text" value="#'.invoice_info($id,"invoice").'" readonly="readonly" class="form-control"/>
		</div>
		<div class="form-group">
		<label for="method">Payment Method</label>
		<input type="text" value="'.invoice_info($id,"method").'" readonly="readonly" class="form-control"/>
		</div>';
		echo '
		<div class="form-group">
		<label for="via">Via</label>';
		if (invoice_info($id,"method") == "Bank") {
			$bank_data = $db->select("bank_accounts", "account_number", array("id" => invoice_info($id,"via")));
			$bank = $bank_data->fetch_assoc();
			echo '<input type="text" name="via" value="'.$bank["account_number"].'" class="form-control" readonly="readonly"/>';
		} else {
			echo '<input type="text" name="via" value="'.invoice_info($id,"via").'" class="form-control"/>';
		}
		echo '
		</div>
		<div class="form-group">
		<label for="amount">Amount</label>
		<div class="input-group">
		<span class="input-group-addon">$</span>
		<input type="text" name="via" value="'.invoice_info($id,"amount").'" class="form-control"/>
		</div></div>
		<div class="form-group">
		<button type="submit" class="btn btn-success">Update</button>
		</div></form></div></div></div>';
		
				
} elseif ($act == "del" && isset($_GET["id"])) {
		$admin->head("Delete Invoice");
		$id = get("id");
		if(!invoice_exists($id)){
			echo "InvoiceNotFound!";
			exit;
		}
		
		if($_GET["confirm"]=="yes"){
		
			$delete = $db->link->query("DELETE FROM invoices WHERE id='$id'");
			if($delete){
				$_SESSION["message"]="INVOICE #".invoice_info($id,"invoice")." deleted successfully!";
				redir("invoices.php");
			}
			else {
				echo "UnknownError~DbError";
			}
		}
		else {
			
			echo '<div class="row"><div class="col-md-12"><div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">Delete INVOICE #'.invoice_info($id,"invoice").'?</h4></div>';
			echo '<p align="center">Are you Sure ??<br/><a href="invoices.php?act=del&id='.$id.'&confirm=yes">Yes, Delete</a> - <a href="invoices.php?act=details&id='.$id.'">No, Go Back</a></p></div></div></div>';
			
		}
} elseif ($act == "reject" && isset($_GET["id"])) {
		
		$id = get("id");
		if(!invoice_exists($id)){
			echo "InvoiceNotFound!";
			exit;
		}
		
		$db->update("invoices",array("status"=>0),array("id"=>$id));
		$_SESSION["message"]=".INVOICE #".invoice_info($id,"invoice")." Rejected Successfully!";
		redir("invoices.php?act=details&id=".$id);
} elseif ($act == "pay" && isset($_GET["id"])) {
		
		$id = get("id");
		if(!invoice_exists($id)){
			echo "InvoiceNotFound!";
			exit;
		}
		
		$db->update("invoices",array("status"=>1,"pdate"=>$date),array("id"=>$id));
		$_SESSION["message"]=".INVOICE #".invoice_info($id,"invoice")." Paid Successfully!";
		$mailBody = $init->mail("invoicep");
		$mailBody = str_replace("%date%",$date,$mailBody);
		$mailBody = str_replace("%username%",$user->gdata("username",array("id"=>invoice_info($id,"userid"))),$mailBody);
		$mailBody = str_replace("%invoice%",invoice_info($id,"invoice"),$mailBody);
		$subject = "Invoice Paid";
		$user->mail($subject,$mailBody,$user->gdata("email",array("id"=>invoice_info($id,"userid"))));
		redir("invoices.php?act=details&id=".$id);
} elseif ($act == "details" && isset($_GET["id"])) {
		$id = get("id");
		$admin->head('INVOICE #'.invoice_info($id,"invoice"));

		echo '<section class="content-header">
          <h1>
            Invoice
            <small>#'.invoice_info($id,"invoice").'</small>
          </h1>
        </section>';

		if(!invoice_exists($id)){
			echo "InvoiceNotFound!";
			exit;
		}
		
		$admin->message();
		
		echo '<section class="invoice">';
		echo '<div class="row">
            <div class="col-xs-12">
              <h2 class="page-header">
                <i class="fa fa-globe"></i> '.$setting["sitename"].', Inc.
                <small class="pull-right">Date: '.invoice_info($id,"date").'</small>
              </h2>
            </div><!-- /.col -->
          </div>';

        if(invoice_info($id,"status")==1){
			$status = '<font color="green">Paid</font><br/>Paid On: '.invoice_info($id,"pdate").'<br/>';
		}
		elseif(invoice_info($id,"status")==2){
			$status = '<font color="#ff6600">Pending</font><br/>';
		}
		elseif(invoice_info($id,"status")==3){
			$status = '<font color="grey">Cancelled</font><br/>';
		}
		else {
			$status = '<font color="red">Rejected</font><br/>';
		}

		$amount = invoice_info($id,"method") == "Mobile Recharge" ? "RS. " : "\$";
		$amount .= invoice_info($id,"amount");

         echo '<div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
              From
              <address>
                <strong>'.$setting["sitename"].', Inc.</strong><br>
                '.$setting["siteurl"].'<br>
                Email: info@'.$setting["sitename"].'
              </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
              To
              <address>
              	<strong>'.$user->gdata("name",array("id"=>invoice_info($id,"userid"))).'</strong><br/>
                <strong><a href="users.php?act=details&id='.invoice_info($id,"userid").'">'.$user->gdata("username",array("id"=>invoice_info($id,"userid"))).'</a></strong><br>
                Email: '.$user->gdata("email",array("id"=>invoice_info($id,"userid"))).'
              </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
              <b>Invoice #'.invoice_info($id,"invoice").'</b><br>
              <br>
              <b>Created:</b> '.invoice_info($id,"date").'<br>
              <b>Amount:</b> <span class="label label-success">'.$amount.'</span>
            </div><!-- /.col -->
          </div><!-- /.row -->';
         echo '<div class="row">
            <div class="col-xs-12 table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Invoice #</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Payment Via</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>'.invoice_info($id,"invoice").'</td>
                    <td>'.$status.'</td>
                    <td>'.invoice_info($id,"method").'</td>';
        if (invoice_info($id, "method") == "Mobile Recharge") {
        	echo '<td>'.invoice_info($id, "via").'<br/>Operator: '.invoice_info($id, "operator").' | Circle: '.invoice_info($id, "circle").'</td>';
        } elseif (invoice_info($id, "method") == "Bank") {
        	$bank_data = $db->select("bank_accounts", "bank_name,account_number", array("id" => invoice_info($id, "via")));
        	$bank = $bank_data->fetch_assoc();
        	echo '<td><a href="?bank='.invoice_info($id, "via").'">'.$bank["bank_name"].' ('.$bank["account_number"].')</a></td>';
        } else {
        	echo '<td>'.invoice_info($id, "via").'</td>';
        }

        echo '<td>'.$amount.'</td>
                  </tr></tr>
                </tbody>
              </table>
            </div><!-- /.col -->
          </div><!-- /.row -->';	
		echo '<div class="row">
            <div class="col-xs-12">
              <div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    Options <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu">
			    <li><a href="invoices.php?act=edit&id='.$id.'">Edit</a></li>
			    <li><a href="?act=del&id='.$id.'">Delete</a></li>
			  </ul>
			</div>';
			if (invoice_info($id,"status") != 2) {
				if (invoice_info($id,"status") == 1) {
					echo '<h3 class="text-success pull-right"><i class="fa fa-check"></i> Paid</h3>';
				} elseif (invoice_info($id,"status") == 3) {
					echo '<h3 class="text-muted pull-right"><i class="fa fa-exclamation-circle"></i> Cancelled</h3>';
				} else {
					echo '<h3 class="text-danger pull-right"><i class="fa fa-ban"></i> Rejected</h3>';
				}
			} else {

            echo '<a href="invoices.php?act=pay&id='.$id.'"><button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Mark as Paid</button></a>
              <a href="invoices.php?act=reject&id='.$id.'"><button class="btn btn-danger pull-right" style="margin-right: 5px;"><i class="fa fa-ban"></i> Reject Invoice</button></a>
            ';
        }
            echo '</div>
          </div>';
		
		
} elseif (isset($_GET["bank"])) {
	$bank_id = get("bank");
	$bank_data = $db->select("bank_accounts", "", array("id" => $bank_id));
	$data = $bank_data->fetch_assoc();

	$admin->head("Bank Account Details");

	echo '<div class="row"><div class="col-md-12">
	<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">Bank Account Details</h4></div>
	<div class="panel-body">
	<strong>Account Holder:</strong> '.$data["name"].'<br/>
	<strong>Account Number:</strong> <b>'.$data["account_number"].'</b><br/>
	<strong>Bank Name:</strong> '.$data["bank_name"].'<br/>
	<strong>Bank Address:</strong><br/> '.str_replace('\r\n', "<br/>", $data["bank_address"]).'<br/>
	<strong>NEFT/IFSC:</strong> '.$data["neft_ifsc"].'<br/>
	<strong>Swift Code:</strong> '.$data["swift_code"].'<br/>
	<strong>PAN/TAX Number:</strong> '.$data["pan_tax"].'</div></div></div></div>';
} else {
	$type = get("type");
	$page = get("page");
			
	if(empty($page)) 	$page=1;
			
	$start = ($page-1)*30;
	$end = 30;

	if (isset($_GET["uid"])) {
		$uid = get("uid");
		
		if (!$user->exists($uid)) {
			echo "UserNotFound~!";
			exit();
		}

		if ($type == "active") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 1, "userid" => $uid), "id DESC", "$start,$end");
		} elseif ($type == "blocked") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 0, "userid" => $uid), "id DESC", "$start,$end");
		} elseif ($type == "pending") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 2, "userid" => $uid), "id DESC", "$start,$end");
		} elseif ($type == "rejected") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 3, "userid" => $uid), "id DESC", "$start,$end");
		} else {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("userid" => $uid), "id DESC", "$start,$end");
		}
	} else {
		if ($type == "paid") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 1), "id DESC", "$start,$end");
		} elseif ($type == "cancelled") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 3), "id DESC", "$start,$end");
		} elseif ($type == "pending") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 2), "id DESC", "$start,$end");
		} elseif ($type == "rejected") {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", array("status" => 0), "id DESC", "$start,$end");
		} else {
			$invoices = $db->select("invoices", "id,invoice,amount,userid,status,method", "", "id DESC", "$start,$end");
		}
	}			
	
	$admin->head("Invoices");
	echo '<section class="content-header">';
	echo '<div class="row">
	<div class="col-lg-12">
	<div class="panel panel-default">
	<div class="panel-heading">
	<div class="row">
	<div class="col-md-4">
	<h2 class="panel-title">'.ucwords(str_replace("_", " ", $type)).' Invoices</h2>
	</div>
	<div class="col-md-4">
	<select class="form-control" onchange="changeCat(this.value);">
	<option selected="selected">Select Type</option>
	<option value="paid">Paid Invoices</option>
	<option value="pending">Pending Invoices</option>
	<option value="cancelled">Cancelled Invoices</option>
	<option value="rejected">Rejected Invoices</option>
	</select>
	</div>
	<div class="col-md-4">
	<form method="get">
	<input type="hidden" name="act" value="search"/>
	<div class="input-group">
	<input type="text" name="q" placeholder="Search Invoices" class="form-control"/>
	<div class="input-group-btn">
	<button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
	</div></div></form>
	</div></div></div>
	<div class="panel-body">';

	if ($invoices->num_rows == 0) {
		echo 'No Invoice Found in Database!';
	} else {
		echo '<table class="table table-striped table-hover table-bordered">';
		echo '<th class="text-center">ID #</th><th class="text-center">Invoice #</th><th class="text-center">Status</th><th class="text-center">Amount</th><th class="text-center">Owner</th>';
		
		while ($result = $invoices->fetch_assoc()) {
			if($result["status"]==1){
				$status = '<font color="green">Paid</font>';
			}
			elseif($result["status"]==2){
				$status = '<font color="#ff6600">Pending</font>';
			}
			elseif($result["status"]==3){
				$status = '<font color="grey">Cancelled</font>';
			}
			else {
				$status = '<font color="red">Rejected</font>';
			}

			$amount = $result["method"] == "Mobile Recharge" ? "RS. " : "\$";
			$amount .= $result["amount"];

			echo '<tr class="text-center">
			<td>'.$result["id"].'</td>
			<td><a href="invoices.php?act=details&id='.$result["id"].'">#'.$result["invoice"].'</a></td>
			<td>'.$status.'</td>
			<td><span class="label label-success">'.$amount.'</span></td>
			<td><a href="users.php?act=details&id='.$result["userid"].'">'.$user->gdata("username", array("id" => $result["userid"])).'</a></td>
			</tr>';
		}

		echo '</table></div></div>';
		$init->paging_admin($page, $invoices->num_rows, 30);
	}
}
$script = <<<HTML
<script>
function changeCat(cat)
{
	window.location.href = "?type=" + cat;
}
</script>
HTML;
	
function invoice_exists($id)
{
	global $db;
	
	$code = $db->select("invoices","amount",array("id" => $id));
		
	if ($code->num_rows < 1) {
		return false;
	} else {
		return true;
	}
}
	
function invoice_info($id, $var)
{
	global $db;
		
	$code = $db->select("invoices", $var, array("id" => $id));
	$fetch = $code->fetch_assoc();
	
	return $fetch[$var];	
}
	
$admin->foot($script);	
?>	