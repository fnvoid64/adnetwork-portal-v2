{include file="header-logged.tpl"}
<section class="content">
	
{if isset($site_add_error)}
	<div class="alert alert-danger text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{foreach from=$site_add_error item=error}{$error}<br/>{/foreach}
	</div>
{/if}
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Add New Site</h3>
			</div>
			<div class="panel-body">
			{$site_add_form}
			</div>
		</div>
	</div>
</div>	