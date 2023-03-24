{include file="header-logged.tpl"}
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, {$name}
			<small>Campaign Details</small>
		</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-dashboard"></i> <a href="{$site_url}/dashboard/index.html">Dashboard</a></li>
			<li class="active"><i class="fa fa-bullhorn"></i> Campaign Details</li>
		</ol>
	</section>
	<section class="content">
	
{if isset($message)}
	<div class="alert alert-success text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{$message}
	</div>
{/if}

<div class="row">
<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="panel-title">Campaign Details</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-5">
					<ul class="list-group">
						<li class="list-group-item"><b>Name:</b> {$ads.name}</li>
						<li class="list-group-item"><b>URL:</b> http://{$ads.url}</li>
						<li class="list-group-item"><b>Adult:</b> {if $ads.adult eq 1}Yes{else}No{/if}</li>
						<li class="list-group-item"><b>Type:</b> {if $ads.type eq 1}Banner{else}Text{/if}</li>
						{if $ads.type eq 1}
						 <li class="list-group-item"><b>Banners:</b><br/>
							{if isset($banners)}
								{foreach from=$banners item=banner}
									<img src="{$site_url}/uploads/{$banner}.gif" alt="banner" width="130px" height="30px"/><br/>
								{/foreach}
							{else}
								<img src="{$site_url}/uploads/{$ads.banners}.gif" alt="banner" width="130px" height="30px"/><br/>
							{/if}
						 </li>
						{else}
						<li class="list-group-item">
						 <b>Titles:</b>
						 {$ads.titles}
						 </li>
						{/if}
						<li class="list-group-item"><b>Status:</b> 
						{if $ads.status eq 1}
							<span class="label label-success">Running</span>
						{elseif $ads.status eq 2}
							<span class="label label-warning">Pending</span>
						{elseif $ads.status eq 3}
							<span class="label label-danger">Paused</span>
						{elseif $ads.status eq 4}
							<span class="label label-danger">Rejected</span>
						{else}
							<span class="label label-danger">Blocked</span>
						{/if}
						</li>
						<li class="list-group-item"><b>Countrys:</b> {$ads.countrys}</li>
						<li class="list-group-item"><b>Devices:</b> {$ads.devices}</li>
						<li class="list-group-item text-center">
							<a href="{$site_url}/dashboard/campaigns/edit.html?id={$ads.id}" class="btn btn-app"><i class="fa fa-edit"></i> Edit Campaign</a>
							<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete Campaign</a>
							{if $ads.status eq 1}
							<a href="{$site_url}/dashboard/campaigns/change.html?id={$ads.id}" class="btn btn-app"><i class="fa fa-pause"></i> Pause Ad</a>
							{else}
								{if $ads.status eq 3}
								<a href="{$site_url}/dashboard/campaigns/change.html?id={$ads.id}" class="btn btn-app"><i class="fa fa-play"></i> Run Ad</a>
								{/if}
							{/if}
						</li>
					</ul>
					<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
					  	<div class="modal-dialog">
						    <div class="modal-content">
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        	<h4 class="modal-title">Delete Ad?</h4>
						      	</div>
						      	<div class="modal-body">
						        	<p>Delete Ad {$ads.name} (http://{$ads.url})</p>
						      	</div>
						      	<div class="modal-footer">
						        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
						        	<a href="{$site_url}/dashboard/campaigns/del.html?id={$ads.id}&confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
						      	</div>
						    </div>
					  	</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
					<a href="/stats?type=ad&ad_id={$ads.id}&date={$date}/{$date}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-aqua text-center">
					        <div class="inner">
					            <h3>{$today_clicks}</h3>
					            <p>Today Clicks</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=ad&ad_id={$ads.id}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-red text-center">
					        <div class="inner">
					            <h3>{$total_clicks}</h3>
					            <p>Total Clicks</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=ad&ad_id={$ads.id}&date={$date}/{$date}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-green text-center">
					        <div class="inner">
					            <h3>${$today_earn}</h3>
					            <p>Today Spent</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=ad&ad_id={$ads.id}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-yellow text-center">
					        <div class="inner">
					            <h3>${$total_earn}</h3>
					            <p>Total Spent</p>
					        </div>
					    </div>
					</div>
					</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
{include file="footer-logged.tpl"}