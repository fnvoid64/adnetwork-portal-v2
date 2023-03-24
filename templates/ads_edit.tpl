{include file="header-logged.tpl"}
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Welcome, {$name}
            <small>Edit Campaigns</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> <a href="{$site_url}/dashboard/index.html">Dashboard</a></li>
            <li class="active"><i class="fa fa-bullhorn"></i> Edit Campaigns</li>
        </ol>
    </section>
    <section class="content">
	
{if isset($ads_edit_error)}
	<div class="alert alert-danger text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{foreach from=$ads_edit_error item=error}{$error}<br/>{/foreach}
	</div>
{/if}
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="panel-title">Edit Advertisement</h3>
			</div>
			<div class="box-body">
			{$ads_edit_form}
			</div>
		</div>
	</div>
</div>	
	
{include file="footer-logged.tpl"}
<script src="{$root}/assets/plugins/select2/select2.full.js"></script>
<script type="text/javascript">
    $(".select2").select2();
</script>