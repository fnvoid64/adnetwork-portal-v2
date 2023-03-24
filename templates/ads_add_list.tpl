{include file="header-logged.tpl"}
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, {$name}
			<small>Create Campaigns</small>
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
			<h3 class="panel-title">Create Campaign</h3>
		</div>
		<div class="box-body text-center">
			<a href="{$site_url}/dashboard/campaigns/add.html?type=text" class="btn btn-success btn-lg"><i class="fa fa-pencil fa-3x"></i><br/>Create Text Campaign</a>
			<a href="{$site_url}/dashboard/campaigns/add.html?type=banner" class="btn btn-info btn-lg"><i class="fa fa-photo fa-3x"></i><br/>Create Banner Campaign</a>
		</div>
	</div>
</div>
{include file="footer-logged.tpl"}