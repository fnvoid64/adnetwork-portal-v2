{include file="header-logged.tpl"}
<section class="content">
<div class="row">
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Notifications</h3>
		</div>
		<div class="panel-body">
		{if empty($notify)}
			<i class="text-center">Sorry, You have no notifications!</i>
		{else}
		<table class="table table-bordered table-hover table-striped">
		<tr>
			<th>ID #</th>
			<th>Subject</th>
			<th>Date</th>
			<th>Status</th>
		</tr>
		{foreach from=$notify item=noti}
			<tr>
				<td>{$noti.id}</td>
				<td><a href="/notifications/{$noti.id}">{$noti.subject}</a></td>
				<td>{$noti.date}</td>
				<td>
				{if $noti.status eq 2}
					<span class="label label-success">Read</span>
				{else}
					<span class="label label-danger">Unread</span>
				{/if}
				</td>
			</tr>
		{/foreach}
		</table>
	</div>
{/if}
</div>
{if isset($paging)}
{$paging}
{/if}
</div>
</div>
{include file="footer-logged.tpl"}