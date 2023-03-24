{include file="header-logged.tpl"}
<link rel="stylesheet" href="{$root}/assets/plugins/daterangepicker/daterangepicker.css">
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, {$name}
			<small>Reports</small>
		</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-dashboard"></i> <a href="{$site_url}/dashboard/index.html">Dashboard</a></li>
			<li class="active"><i class="fa fa-bar-chart"></i> Reports</li>
		</ol>
	</section>
	<section class="content">
<div class="row">
<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-header">
			<div class="row">
				<div class="col-md-3">
					<h3 class="panel-title">Reports</h3>
				</div>
				<div class="col-md-3">
					<select name="site_id" onchange="changeCat('?type=site&site_id=' + this.value)" class="form-control">
						<option>Select Site</option>
					{if isset($sites)}
						{foreach from=$sites item=site}
						<option value="{$site.id}">{$site.name}</option>
						{/foreach}
					{/if}
					</select>
				</div>
				<div class="col-md-3">
					<select name="ad_id" onchange="changeCat('?type=ad&ad_id=' + this.value)" class="form-control">
						<option>Select Advertisement</option>
					{if isset($ads)}
						{foreach from=$ads item=ad}
						<option value="{$ad.id}">{$ad.name}</option>
						{/foreach}
					{/if}
					</select>
				</div>
				<div class="col-md-3">
					<button class="btn btn-default" id="daterange-btn">
                        <i class="fa fa-calendar"></i> Select Statistics Date 
                        <i class="fa fa-caret-down"></i>
                    </button>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding: 0">
		{if isset($no_stats)}
			<p class="text-center"><i>Ooops, Sorry! Looks like no statistics is available!</i></p>
		{else}
			<table class="table table-hover table-bordered table-striped table-condensed">
				<th class="text-center">Date</th>
				<th class="text-center">Clicks</th>
				<th class="text-center">Impressions</th>
				{if isset($itsad)}
				<th class="text-center">Spent (Estimated)[$]</th>
				{else}
				<th class="text-center">Earnings ($)</th>
				{/if}
				<th class="text-center">CTR (%)</th>
				{foreach from=$dates key=i item=currdate}
				<tr align="center">
					<td>{$currdate}</td>
					<td>{$clicks.{$i}}</td>
					<td>{$impressions.{$i}}</td>
					<td>{$earnings.{$i}}</td>
					<td>{$ctr.{$i}}</td>
				</tr>
				{/foreach}
			</table>
		{/if}
		</div>
	</div>
	{if isset($paging)}
	{$paging}
	{/if}
</div>
</div>
</section>
<script src="{$root}/assets/js/jquery.js"></script>
<script src="{$root}/assets/js/bootstrap.min.js"></script>
<script src="{$root}/assets/js/app.min.js"></script>
<script src="{$root}/assets/plugins/daterangepicker/moment.js"></script>
<script src="{$root}/assets/plugins/daterangepicker/daterangepicker.js"></script>
<script>
$('#daterange-btn').daterangepicker(
	{
	    ranges: {
	        'Today': [moment(), moment()],
	        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	        'This Month': [moment().startOf('month'), moment().endOf('month')],
	        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	    },
	    startDate: moment().subtract(29, 'days'),
	    endDate: moment(),
	    format: 'DD/MM/YYYY',
	    separator: ' to '
	},
	function (start, end) {
		location.href = URL_add_parameter(location.href, 'date', start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
	    //$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	}
);
function changeCat(value) {
	location.href = value;
}
function URL_add_parameter(url, param, value){
    var hash       = {};
    var parser     = document.createElement('a');

    parser.href    = url;

    var parameters = parser.search.split(/\?|&/);

    for(var i=0; i < parameters.length; i++) {
        if(!parameters[i])
            continue;

        var ary      = parameters[i].split('=');
        hash[ary[0]] = ary[1];
    }

    hash[param] = value;

    var list = [];  
    Object.keys(hash).forEach(function (key) {
        list.push(key + '=' + hash[key]);
    });

    parser.search = '?' + list.join('&');
    return parser.href;
}
</script>
</body></html>