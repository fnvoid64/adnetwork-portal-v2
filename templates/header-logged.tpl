<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{$root}/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{$root}/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="{$root}/assets/plugins/select2/select2.css">
    <link rel="stylesheet" href="{$root}/assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{$root}/assets/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="{$root}/assets/plugins/wizard/wizard.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src="{$root}/assets/img/logo-fff.png" width="50px"/></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="{$root}/assets/img/logo-fff.png" width="190px"/></span>
        </a>
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
                            <!-- The user image in the navbar-->
                            <i class="fa fa-user"></i>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{$name} (${$pbalance})</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                    Alexander Pierce - Web Developer
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat">Sign out</a>
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
            <ul class="sidebar-menu">
                <li><a href="{$site_url}/dashboard/index.html"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li><a href="{$site_url}/dashboard/new_site.html"><i class="fa fa-plus"></i> <span>Add new site</span></a></li>
                <li><a href="{$site_url}/dashboard/campaigns.html"><i class="fa fa-bullhorn"></i> <span>Campaigns</span></a></li>
                <li><a href="{$site_url}/dashboard/reports.html"><i class="fa fa-line-chart"></i> <span>Reports</span></a></li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-cc-paypal"></i> <span>Payments</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{$site_url}/dashboard/withdraw.html"><i class="fa fa-download"></i> <span>Withdraw Money</span></a></li>
                        <li><a href="{$site_url}/dashboard/payment-history.html"><i class="fa fa-history"></i> <span>Payment History</span></a></li>
                        <li><a href="{$site_url}/dashboard/settings.html?setting=payment"><i class="fa fa-gear"></i> <span>Payment Settings</span></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-gears"></i> <span>My Account</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{$site_url}/dashboard/edit_profile.html"><i class="fa fa-user"></i> <span>Edit Profile</span></a></li>
                        <li><a href="{$site_url}/dashboard/settings.html?setting=payment"><i class="fa fa-paypal"></i> <span>Payment Settings</span></a></li>
                        <li><a href="{$site_url}/dashboard/change_password.html"><i class="fa fa-lock"></i> <span>Change Password</span></a></li>
                    </ul>
                </li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
