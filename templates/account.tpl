{include file="header-logged.tpl"}
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Welcome, {$name}
            <small>My Account</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> <a href="{$site_url}/dashboard/index.html">Dashboard</a></li>
            <li class="active"><i class="fa fa-bullhorn"></i> Campaigns</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="panel-title">Account Info</h3>
                    </div>
                    <div class="box-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#details">My Details</a></li>
                            <li><a href="{$site_url}/dashboard/change_password.html">Change Password</a></li>
                        </ul>
                        <div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
                            <div id="details" class="tab-pane fade in active">

                                {include file="alerts.tpl"}
                                {$form}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{include file="footer-logged.tpl"}
	