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
		<div class="panel-heading"><h3 class="panel-title">Request Payout</h3></div>
		<div class="panel-body text-center">
			{foreach from=$methods item=method}
			{if $method.visible eq 1}
			<a href="/payout?type={$method.url}" class="btn btn-default"><img src="{$TemplateDir}img/{$method.url}.png" width="90px" height="50px"/><br/>Minimum 
			{if $method.url eq "recharge"}
			RS. {$method.minimum}
			{else}
			${$method.minimum}
			{/if}
			</a>
			{/if}
			{/foreach}
		</div>
	</div>
</div>
</div>
{include file="footer-logged.tpl"}