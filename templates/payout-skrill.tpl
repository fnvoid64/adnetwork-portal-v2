{include file="header-logged.tpl"}
<section class="content">
{if isset($errors)}
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{foreach from=$errors item=error}
		{$error}<br/>
		{/foreach}
	</div>
{/if}

{if isset($low_balance)}
	<div class="alert alert-danger">Your account balance is lower than our minimum payout term!</div>
{else}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">Paypal Payment Request</h3></div>
				<div class="panel-body">
					<form method="post">
						<div class="form-group">
							<label for="name">Skrill Name</label>
							<input type="text" name="name" class="form-control">
						</div>
						<div class="form-group">
							<label for="email">Skrill Email</label>
							<input type="text" name="email" class="form-control">
						</div>
						<div class="form-group">
							<label for="amount">Request Amount</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>
								<input type="text" name="amount" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="pin">Your PIN (<a href="/cp?sendPin=new&payment">Send New PIN</a>)</label>
							<input type="text" name="pin" class="form-control">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success">Request Payout</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{/if}
{include file="footer-logged.tpl"}