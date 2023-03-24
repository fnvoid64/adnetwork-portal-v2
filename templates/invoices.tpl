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
			<div class="row">
				<div class="col-xs-9">
					<h3 class="panel-title">Invoices</h3>
				</div>
				<div class="col-xs-3 text-right">
					<a href="/payout">
						<button class="btn btn-success btn-sm">
							<i class="fa fa-download"></i>
							 Withdraw Money
						</button>
					</a>
				</div>
			</div>
		</div>
		<div class="panel-body">
		{if empty($invoices)}
			<i class="text-center">Sorry, You haven't withdraw any money yet!</i>
		{else}
		<table class="table table-bordered table-hover table-striped">
		<tr>
			<th>ID #</th>
			<th>Invoice</th>
			<th>Amount</th>
			<th>Status</th>
			<th>Method</th>
		</tr>
		{foreach from=$invoices item=invoice}
			<tr>
				<td>{$invoice.id}</td>
				<td><a href="/invoices/details/id/{$invoice.id}">#{$invoice.invoice}</a></td>
				<td>
				{if $invoice.method eq "Mobile Recharge"}
				RS. {$invoice.amount}
				{else}
				${$invoice.amount}
				{/if}
				</td>
				<td>
				{if $invoice.status eq 1}
					<span class="label label-success">Paid</span>
				{elseif $invoice.status eq 2}
					<span class="label label-warning">Pending</span>
				{elseif $invoice.status eq 3}
					<span class="label label-default">Cancelled</span>
				{else}
					<span class="label label-danger">Rejected</span>
				{/if}
				</td>
				<td>{$invoice.method}</td>
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