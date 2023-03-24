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
	{if isset($unsupported_country)}
		<div class="alert alert-danger">We're sorry, but this payment method is not supported in your country!</div>
	{else}
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading"><h3 class="panel-title">Bank Payment Request</h3></div>
					<div class="panel-body">
						<form method="post">
							<div class="form-group">
								<label for="amount">Request Amount</label>
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" name="amount" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label for="bank_account">Bank Account</label>
								<select name="bank_account" class="form-control">
									<option value="{$bank_id}">{$bank_name} ({$bank_account})</option>
								</select>
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
{/if}
{include file="footer-logged.tpl"}