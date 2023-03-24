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
	
{if isset($nonews)}
	<div class="alert alert-info" align="center">No News Available Right Now!</div>
{else}
	<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><span><b class="panel-title">{$newsTitle}</b><br/>Created: {$newsCreated} | Views: {$newsViews}</span></div>
		<div class="panel-body">
		<p>{$newsBody}</p>
		{if empty($replys)}
			<p class="text-center" style="border-top: 1px solid #ececec; margin-top: 20px; padding-top: 20px">
			<span class="text-muted text-muted"><i>No Reply Found!</i></span></p>
		{else}
			<p class="text-center" style="border-top: 1px solid #ececec; margin-top: 20px; padding-top: 20px">
			<table class="table table-striped">
				{foreach from=$replys key=n item=reply}
					<tr><td>{$reply}
					<br/>Reply By: {$replyBy.{$n}} at {$replyDate.{$n}}</td></tr>
				{/foreach}
			</table></p>
		{/if}
		{if isset($newsform)}
			{$newsform}
		{/if}
		</div></div></div></div>
{/if}
{include file="footer-logged.tpl"}	
</body></html>