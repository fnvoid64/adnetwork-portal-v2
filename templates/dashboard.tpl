{include file="header-logged.tpl"}
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Welcome, {$name}
            <small>Dashboard</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
        </ol>
    </section>
    <section class="content">

        {include file="alerts.tpl"}

        {if isset($notifications)}
            <div class="alert alert-info text-center">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {$notifications}
            </div>
        {/if}

        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Publisher Balance</span>
                        <span class="info-box-number">${$publisher_balance}</span>
                        <span class="info-box-text">Advertiser Balance</span>
                        <span class="info-box-number">${$advertiser_balance}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-mobile"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today Clicks</span>
                        <span class="info-box-number">{$today_clicks}</span>
                        <span class="info-box-text">Today Earnings</span>
                        <span class="info-box-number">${$today_earnings}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-bar-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Clicks</span>
                        <span class="info-box-number">{$total_clicks}</span>
                        <span class="info-box-text">Total Earnings</span>
                        <span class="info-box-number">${$total_earnings}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <!-- Chart -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Statistics</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body" style="display: block;">
                        <div class="chart">
                            <canvas id="barChart" style="height: 216px; width: 479px;" height="216px"
                                    width="479px"></canvas>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Recent News</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="list-group">
                            {if isset($news)}
                                {foreach from=$news item=n}
                                    <a class="list-group-item" href="{$siteurl}/news/{$n.id}"><b><i
                                                    class="fa fa-bullhorn"></i> {$n.title}
                                        </b><br/><i>Published: {$n.date}</i></a>
                                {/foreach}
                            {else}
                                <p class="text-center">No News Available!</p>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sites -->
        <div class="box box-info">
            <div class="box-header">
                <div class="row">
                    <div class="col-xs-9">
                        <h3 class="panel-title">Sites</h3>
                    </div>
                    <div class="col-xs-3 text-right">
                        <a href="{$site_url}/dashboard/new_site.html">
                            <button class="btn btn-success btn-sm">
                                <i class="fa fa-plus"></i>
                                Add Site
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="box-body" style="padding: 0;">
                <table class="table table-condensed table-bordered">
                    <th class="text-center">Name</th>
                    <th class="text-center">URL</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">SDK</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                    {if isset($sites)}
                        {foreach from=$sites item=site}
                            <tr align="center">
                                <td>{$site.name}</td>
                                <td>{$site.url}</td>
                                <td>{$site.category}</td>
                                <td>
                                    <a href="{$site_url}/new_site.html?step=3&id={$site.id}">
                                        <button class="btn btn-xs btn-info"><i class="fa fa-code"></i></button>
                                    </a>
                                </td>
                                <td>
                                    {if $site.status eq 1}
                                        <label class="label label-success">Active</label>
                                    {elseif $site.status eq 2}
                                        <label class="label label-warning">Unverified</label>
                                        (
                                        <a href="{$site_url}/dashboard/new_site.html?step=2&id={$site.id}">Verify
                                            Now</a>
                                        )
                                    {else}
                                        <label class="label label-danger">Blocked</label>
                                    {/if}
                                </td>
                                <td>
                                    <a href="{$site_url}/dashboard/reports.html?type=site&site_id={$site.id}">
                                        <button class="btn btn-default btn-xs">
                                            <i class="fa fa-bar-chart"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="modal"
                                            data-target="#delete-{$site.id}">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete-{$site.id}" tabindex="-1" role="dialog"
                                 aria-labelledby="deletesite">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="deletesite">Delete Site?</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete site {$site.name} ({$site.url})
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                            <a href="{$site_url}/dashboard/delete_site.html?id={$site.id}&c=y">
                                                <button type="button" class="btn btn-danger">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <tr class="text-center">
                            <td>You have not added any site yet!</td>
                        </tr>
                    {/if}
                </table>
            </div>
        </div>
        {include file="footer-logged.tpl"}
        <script src="{$root}/assets/plugins/chartjs/Chart.min.js"></script>
        <script>
            {literal}
            $(function () {
                var areaChartData = {
                    {/literal}labels: ["{$g_total_dates}"]{literal},
                    datasets: [
                        {
                            label: "Clicks",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            {/literal}data: [{$g_total_clicks}]{literal}
                        }
                    ]
                };
                //-------------
                //- BAR CHART -
                //-------------
                var barChartCanvas = $("#barChart").get(0).getContext("2d");
                var barChart = new Chart(barChartCanvas);
                var barChartData = areaChartData;
                barChartData.datasets[0].fillColor = "#00a65a";
                barChartData.datasets[0].strokeColor = "#00a65a";
                barChartData.datasets[0].pointColor = "#00a65a";
                var barChartOptions = {
                    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                    scaleBeginAtZero: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: true,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - If there is a stroke on each bar
                    barShowStroke: true,
                    //Number - Pixel width of the bar stroke
                    barStrokeWidth: 2,
                    //Number - Spacing between each of the X value sets
                    barValueSpacing: 5,
                    //Number - Spacing between data sets within X values
                    barDatasetSpacing: 1,
                    //String - A legend template
                    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                    //Boolean - whether to make the chart responsive
                    responsive: true,
                    maintainAspectRatio: true
                };

                barChartOptions.datasetFill = false;
                barChart.Bar(barChartData, barChartOptions);
            });
            {/literal}
        </script>
{include file="footer-logged.tpl"}
