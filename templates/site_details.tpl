{include file="header-logged.tpl"}
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
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Site Details</h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-5">
					<ul class="list-group">
						<li class="list-group-item"><b>Name:</b> {$site.name}</li>
						<li class="list-group-item"><b>URL:</b> http://{$site.url}</li>
						<li class="list-group-item"><b>Type:</b> {if $site.adult eq 1}Adult{else}Non-Adult{/if}</li>
						<li class="list-group-item"><b>Category:</b> {$site.category}</li>
						<li class="list-group-item text-center">
							<a href="/sites/edit/id/{$site.id}" class="btn btn-app"><i class="fa fa-edit"></i> Edit Site</a>
							<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete Site</a>
							<a href="/adcodes/{$site.id}" class="btn btn-app"><i class="fa fa-cog"></i> Adcodes</a>
						</li>
					</ul>
					<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
					  	<div class="modal-dialog">
						    <div class="modal-content">
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        	<h4 class="modal-title">Delete Site?</h4>
						      	</div>
						      	<div class="modal-body">
						        	<p>Delete Site {$site.name} (http://{$site.url})</p>
						      	</div>
						      	<div class="modal-footer">
						        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
						        	<a href="/sites/del/id/{$site.id}?confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
						      	</div>
						    </div>
					  	</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
					<a href="/stats?type=site&site_id={$site.id}&date={$date}/{$date}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-aqua text-center">
					        <div class="inner">
					            <h3>{$today_clicks}</h3>
					            <p>Today Clicks</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=site&site_id={$site.id}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-red text-center">
					        <div class="inner">
					            <h3>{$total_clicks}</h3>
					            <p>Total Clicks</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=site&site_id={$site.id}&date={$date}/{$date}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-green text-center">
					        <div class="inner">
					            <h3>${$today_earn}</h3>
					            <p>Today Earns</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=site&site_id={$site.id}">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-yellow text-center">
					        <div class="inner">
					            <h3>${$total_earn}</h3>
					            <p>Total Earn</p>
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