{include file="header-logged.tpl"}
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, {$name}
			<small>Password</small>
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
			<h3 class="panel-title">Change Password</h3>
		</div>
		<div class="box-body">
		<ul class="nav nav-tabs">
    		<li><a href="{$site_url}/dashboard/edit_profile.html">My Details</a></li>
			<li class="active"><a data-toggle="tab" href="#cp">Change Password</a></li>
		</ul>
		<div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
			<div id="cp" class="tab-pane fade in active">
	
{if isset($message)}
	<div class="alert alert-success text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{$message}
	</div>
{/if}
	
{if isset($change_pw_error)}
	<div class="alert alert-danger text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{foreach from=$change_pw_error item=error}{$error}<br/>{/foreach}
	</div>
{/if}
{$change_pw_form}
</div></div>
{include file="footer-logged.tpl"}
	