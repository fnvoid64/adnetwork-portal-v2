<?php
namespace Pranto;

class Admin
{
    public $id;

    public function __construct()
    {
        if ($this->is_admin()) {
            $this->id = $_SESSION["adminid"];
        }
    }

    public function is_admin()
    {
        if (isset($_SESSION["adminid"])) {
            return true;
        } else {
            return false;
        }
    }

    public function login($admin, $password)
    {
        global $db;

        $login = $db->select("admins", "admin,password", array("admin" => $admin));
        $errors = array();

        if ($login->num_rows < 1) {
            $errors[] = "Admin Not Found!";
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

    public function data($var)
    {
        global $db;

        $code = $db->select("admins", $var, array("id" => $this->id));
        $fetch = $code->fetch_assoc();

        return $fetch[$var];
    }

    public function head($title, $select2 = false, $daterange = false)
    {
        if (empty($title)) $title = "prAdmin Beta";

        echo <<<HTML
<!DOCTYPE html>
<html>
	<head>
    	<title>{$title}</title>

    	<!-- Meta Tags -->
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    	<!-- CSS -->
    	<link rel="stylesheet" href="css/bootstrap.min.css">
    	<link rel="stylesheet" href="css/font-awesome.min.css">
HTML;
        if ($select2 === true) {
            echo '<link rel="stylesheet" href="css/select2.min.css">';
        }
        if ($daterange === true) {
            echo '<link rel="stylesheet" href="css/daterangepicker-bs3.css">';
        }
        echo <<<HTML
<link rel="stylesheet" href="css/AdminLTE.min.css">
<link rel="stylesheet" href="css/skin-black.min.css">
HTML;

        echo '</head>';


        if ($this->is_admin()) {
            $admin_username = $this->data("admin");
            $admin_email = $this->data("email");

            echo <<<HTML
<body class="hold-transition skin-black sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
			<a href="./" class="logo">
		        <!-- mini logo for sidebar mini 50x50 pixels -->
		        <span class="logo-mini">prAdmin</span>
		        <!-- logo for regular state and mobile devices -->
		        <span class="logo-lg"><i class="fa fa-modx"></i> prAdmin</span>
	        </a>

	        <!-- Header Navbar -->
        	<nav class="navbar navbar-static-top" role="navigation">
          		
          		<!-- Sidebar toggle button-->
          		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            		<span class="sr-only">Toggle navigation</span>
         		</a>
          		
          		<!-- Navbar Right Menu -->
          		<div class="navbar-custom-menu">
            		<ul class="nav navbar-nav">
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-12 text-center">
                      <b class="text-success"><i>{$admin_username}</i></b>
                      <br/><small>{$admin_email}</small>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="settings.php?module=changePassword" class="btn btn-default btn-flat"><i class="fa fa-cog"></i> Password</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat"><i class="fa fa-power-off"></i> Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">Admin Panel</li>
            <li><a href="./"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-user"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="users.php"><i class="fa fa-circle-o"></i> All Users</a></li>
                <li><a href="users.php?type=active"><i class="fa fa-circle-o"></i> Active Users</a></li>
                <li><a href="users.php?type=pending"><i class="fa fa-circle-o"></i> Pending Users</a></li>
                <li><a href="users.php?type=blocked"><i class="fa fa-circle-o"></i> Blocked Users</a></li>
                <li><a href="users.php?type=top_balance"><i class="fa fa-circle-o"></i> Top Balance Users</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-bars"></i> <span>Sites</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="sites.php"><i class="fa fa-circle-o"></i> All Sites</a></li>
                <li><a href="sites.php?type=active"><i class="fa fa-circle-o"></i> Active Sites</a></li>
                <li><a href="sites.php?type=pending"><i class="fa fa-circle-o"></i> Pending Sites</a></li>
                <li><a href="sites.php?type=blocked"><i class="fa fa-circle-o"></i> Blocked Sites</a></li>
                <li><a href="sites.php?type=rejected"><i class="fa fa-circle-o"></i> Rejected Sites</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-bullhorn"></i> <span>Advertises</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="ads.php?act=add"><i class="fa fa-plus"></i> Create New Ad</a></li>
                <li><a href="ads.php"><i class="fa fa-circle-o"></i> All Ads</a></li>
                <li><a href="ads.php?type=running"><i class="fa fa-circle-o"></i> Running Ads</a></li>
                <li><a href="ads.php?type=pending"><i class="fa fa-circle-o"></i> Pending Ads</a></li>
                <li><a href="ads.php?type=blocked"><i class="fa fa-circle-o"></i> Blocked Ads</a></li>
                <li><a href="ads.php?type=rejected"><i class="fa fa-circle-o"></i> Rejected Ads</a></li>
              </ul>
            </li>
            <li><a href="stats.php"><i class="fa fa-bar-chart"></i> <span>Statistics</span></a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-file"></i> <span>Invoices</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="invoices.php"><i class="fa fa-circle-o"></i> All Invoices</a></li>
                <li><a href="invoices.php?type=paid"><i class="fa fa-circle-o"></i> Paid Invoices</a></li>
                <li><a href="invoices.php?type=pending"><i class="fa fa-circle-o"></i> Pending Invoices</a></li>
                <li><a href="invoices.php?type=rejected"><i class="fa fa-circle-o"></i> Rejected Invoices</a></li>
                <li><a href="invoices.php?type=cancelled"><i class="fa fa-circle-o"></i> Cancelled Invoices</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-newspaper-o"></i> <span>News</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="news.php"><i class="fa fa-circle-o"></i> List News</a></li>
                <li><a href="news.php?act=add"><i class="fa fa-plus"></i> Add News</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-envelope-o"></i> <span>E-Mail</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="mail.php"><i class="fa fa-circle-o"></i> Send Mail to 1 User</a></li>
                <li><a href="mail.php?act=all"><i class="fa fa-circle-o"></i> Send Mail to All User</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-bell-o"></i> <span>Notifications</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="notifications.php"><i class="fa fa-circle-o"></i> Notify 1 User</a></li>
                <li><a href="notifications.php?act=all"><i class="fa fa-circle-o"></i> Notify All User</a></li>
              </ul>
            </li>
            <li class="treeview">
              <a href="#"><i class="fa fa-cog"></i> <span>Settings</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="settings.php?module=global"><i class="fa fa-circle-o"></i> Global Settings</a></li>
                <li><a href="settings.php?module=sites"><i class="fa fa-circle-o"></i> Sites Settings</a></li>
                <li><a href="settings.php?module=ads"><i class="fa fa-circle-o"></i> Ads Settings</a></li>
                <li><a href="settings.php?module=clicks"><i class="fa fa-circle-o"></i> Clicks Settings</a></li>
                <li><a href="settings.php?module=refer"><i class="fa fa-circle-o"></i> Referral Settings</a></li>
                <li><a href="settings.php?module=mail"><i class="fa fa-circle-o"></i> Mail Settings</a></li>
                <li><a href="settings.php?module=styles"><i class="fa fa-circle-o"></i> Template Settings</a></li>
                <li class="treeview">
	              <a href="#"><i class="fa fa-user"></i> <span>Admins</span> <i class="fa fa-angle-left pull-right"></i></a>
	              <ul class="treeview-menu">
	                <li><a href="settings.php?module=admins"><i class="fa fa-circle-o"></i> List Admins</a></li>
	                <li><a href="settings.php?module=admins&act=add"><i class="fa fa-plus"></i> Add Admin</a></li>
	              </ul>
	            </li>
                <li><a href="settings.php?module=changePassword"><i class="fa fa-circle-o"></i> Change Password</a></li>
              </ul>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">       
HTML;
        } else {
            echo '<body class="hold-transition skin-black sidebar-mini" style="background: #ECF0F5">';
        }
    }

    public function foot($add = "")
    {
        echo <<<HTML
</section>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/app.min.js"></script>
HTML;
        if (!empty($add)) {
            echo $add;
        }

        echo '</body></html>';
    }

    public function gdata($Var, $var)
    {
        global $db;

        $code = $db->select("admins", $var, $Var);
        $fetch = $code->fetch_assoc();

        return $fetch[$var];
    }

    public function error($errors)
    {
        echo '<div class="alert alert-danger text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>';

        foreach ($errors as $error) {
            echo $error . "<br/>";
        }

        echo '</div>';
    }

    public function message()
    {
        if (isset($_SESSION["message"])) {
            echo '<div class="alert alert-success text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>' . $_SESSION["message"] . '
			</div>';
            unset($_SESSION["message"]);
        }
    }

    public function payment_exists($id)
    {
        global $db;

        $code = $db->select("payout", "name", array("id" => $id));

        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function exists($id)
    {
        global $db;

        $code = $db->select("admins", "role", $id);

        if ($code->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }

    public function valid($var)
    {

        if (!preg_match('/([a-zA-Z0-9\-]+)/', $var)) {
            return false;
        } else {
            return true;
        }
    }

    public function email($email)
    {
        if (!preg_match('/([a-zA-Z0-9\-\.]+)\@([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $email)) {
            return false;
        } else {
            return true;
        }
    }

    public function template_exists($template)
    {

        if (!file_exists(ROOT . "/templates/$template/")) {
            return false;
        } else {
            return true;
        }

    }

}

?>
		
					