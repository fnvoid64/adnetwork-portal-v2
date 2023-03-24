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
	{if isset($unsupported_country)}
		<div class="alert alert-danger">We're sorry, but this payment method is not supported in your country!</div>
	{else}
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading"><h3 class="panel-title">Add New Bank Account</h3></div>
					<div class="panel-body">
						<form method="post">
							<div class="form-group">
								<label for="name">Beneficiary Name *</label>
								<input type="text" name="name" class="form-control">
							</div>
							<div class="form-group">
								<label for="number">Account Number *</label>
								<input type="text" name="number" class="form-control">
							</div>
							<div class="form-group">
								<label for="bank_name">Bank Name *</label>
								<input type="text" name="bank_name" class="form-control">
							</div>
							<div class="form-group">
								<label for="bank_address">Bank Address *</label>
								<textarea name="bank_address" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label for="neft_ifsc">Neft/IFSC *</label>
								<input type="text" name="neft_ifsc" class="form-control">
							</div>
							<div class="form-group">
								<label for="swift">Swift Code *</label>
								<input type="text" name="swift" class="form-control">
							</div>
							<div class="form-group">
								<label for="pan">PAN or TAX Number</label>
								<input type="text" name="pan" class="form-control">
							</div>
							<div class="alert alert-warning">Please fill correct infos. Once submitted cannot be changed!</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success">Add Bank</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	{/if}
{/if}