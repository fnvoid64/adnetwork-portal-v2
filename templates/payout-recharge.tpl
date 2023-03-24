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
					<div class="panel-heading"><h3 class="panel-title">Recharge Payment Request</h3></div>
					<div class="panel-body">
						<form method="post">
							<div class="form-group">
								<label for="email">Mobile Number</label>
								<input type="text" name="number" class="form-control">
							</div>
							<div class="form-group">
								<label for="operator">Operator</label>
								<select name="operator" class="form-control">
									<option>-- Select Operator --</option>
									<option value="AIRCEL">AIRCEL</option>
									<option value="AIRTEL">AIRTEL</option>
									<option value="BSNL">BSNL</option>
									<option value="IDEA">IDEA</option>
									<option value="MTNL">MTNL</option>
									<option value="MTS">MTS</option>
									<option value="RELIANCE CDMA">RELIANCE CDMA</option>
									<option value="RELIANCE GSM">RELIANCE GSM</option>
									<option value="T24">T24</option>
									<option value="TATA DOCOMO">TATA DOCOMO</option>
									<option value="TATA INDICOM">TATA INDICOM</option>
									<option value="Uninor">Uninor</option>
									<option value="Videocon">Videocon</option>
									<option value="VIRGIN CDMA">VIRGIN CDMA</option>
									<option value="VIRGIN GSM">VIRGIN GSM</option>
									<option value="VODAFONE">VODAFONE</option>
								</select>
							</div>
							<div class="form-group">
								<label for="circle">Circle</label>
								<select name="circle" class="form-control">
									<option>-- Select Circle --</option>
									<option value="Andhra Pradesh">Andhra Pradesh</option>
									<option value="Assam">Assam</option>
									<option value="Bihar & Jharkhand">Bihar & Jharkhand</option>
									<option value="Chennai">Chennai</option>
									<option value="Delhi & NCR">Delhi & NCR</option>
									<option value="Gujarat">Gujarat</option>
									<option value="Haryana">Haryana</option>
									<option value="Himachal Pradesh">Himachal Pradesh</option>
									<option value="Jammu & Kashmir">Jammu & Kashmir</option>
									<option value="Karnataka">Karnataka</option>
									<option value="Kerala">Kerala</option>
									<option value="Kolkata">Kolkata</option>
									<option value="Maharashtra & Goa">Maharashtra & Goa</option>
									<option value="MP & Chattisgarh">MP & Chattisgarh</option>
									<option value="Mumbai">Mumbai</option>
									<option value="North East">North East</option>
									<option value="Orrisa">Odisha</option>
									<option value="Punjab">Punjab</option>
									<option value="Rajasthan">Rajasthan</option>
									<option value="Tamil Nadu">Tamil Nadu</option>
									<option value="Uttar Pradesh(E)">Uttar Pradesh(E)</option>
									<option value="Uttar Pradesh(W) & Uttarakhand">Uttar Pradesh(W) & Uttarakhand</option>
									<option value="West Bengal & Andaman Nicobar">West Bengal & Andaman Nicobar</option>
								</select>
							</div>
							<div class="form-group">
								<label for="amount">Request Amount</label>
								<div class="input-group">
									<span class="input-group-addon">RS.</span>
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
{/if}
{include file="footer-logged.tpl"}