<?php
/* Smarty version 3.1.30, created on 2017-03-26 09:28:10
  from "D:\www-root\adz_new\templates\dashboard.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d76d8ab0dc25_20095783',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '48a030be14864813758043f80968c2afa1d2ac6b' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\dashboard.tpl',
      1 => 1490513289,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
    'file:alerts.tpl' => 1,
    'file:footer-logged.tpl' => 2,
  ),
),false)) {
function content_58d76d8ab0dc25_20095783 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Welcome, <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

            <small>Dashboard</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
        </ol>
    </section>
    <section class="content">

        <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


        <?php if (isset($_smarty_tpl->tpl_vars['notifications']->value)) {?>
            <div class="alert alert-info text-center">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $_smarty_tpl->tpl_vars['notifications']->value;?>

            </div>
        <?php }?>

        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Publisher Balance</span>
                        <span class="info-box-number">$<?php echo $_smarty_tpl->tpl_vars['publisher_balance']->value;?>
</span>
                        <span class="info-box-text">Advertiser Balance</span>
                        <span class="info-box-number">$<?php echo $_smarty_tpl->tpl_vars['advertiser_balance']->value;?>
</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-mobile"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today Clicks</span>
                        <span class="info-box-number"><?php echo $_smarty_tpl->tpl_vars['today_clicks']->value;?>
</span>
                        <span class="info-box-text">Today Earnings</span>
                        <span class="info-box-number">$<?php echo $_smarty_tpl->tpl_vars['today_earnings']->value;?>
</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-bar-chart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Clicks</span>
                        <span class="info-box-number"><?php echo $_smarty_tpl->tpl_vars['total_clicks']->value;?>
</span>
                        <span class="info-box-text">Total Earnings</span>
                        <span class="info-box-number">$<?php echo $_smarty_tpl->tpl_vars['total_earnings']->value;?>
</span>
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
                            <?php if (isset($_smarty_tpl->tpl_vars['news']->value)) {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['news']->value, 'n');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['n']->value) {
?>
                                    <a class="list-group-item" href="<?php echo $_smarty_tpl->tpl_vars['siteurl']->value;?>
/news/<?php echo $_smarty_tpl->tpl_vars['n']->value['id'];?>
"><b><i
                                                    class="fa fa-bullhorn"></i> <?php echo $_smarty_tpl->tpl_vars['n']->value['title'];?>

                                        </b><br/><i>Published: <?php echo $_smarty_tpl->tpl_vars['n']->value['date'];?>
</i></a>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                            <?php } else { ?>
                                <p class="text-center">No News Available!</p>
                            <?php }?>
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
                        <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/new_site.html">
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
                    <?php if (isset($_smarty_tpl->tpl_vars['sites']->value)) {?>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sites']->value, 'site');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['site']->value) {
?>
                            <tr align="center">
                                <td><?php echo $_smarty_tpl->tpl_vars['site']->value['name'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['site']->value['url'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['site']->value['category'];?>
</td>
                                <td>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/new_site.html?step=3&id=<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
">
                                        <button class="btn btn-xs btn-info"><i class="fa fa-code"></i></button>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($_smarty_tpl->tpl_vars['site']->value['status'] == 1) {?>
                                        <label class="label label-success">Active</label>
                                    <?php } elseif ($_smarty_tpl->tpl_vars['site']->value['status'] == 2) {?>
                                        <label class="label label-warning">Unverified</label>
                                        (
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/new_site.html?step=2&id=<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
">Verify
                                            Now</a>
                                        )
                                    <?php } else { ?>
                                        <label class="label label-danger">Blocked</label>
                                    <?php }?>
                                </td>
                                <td>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/reports.html?type=site&site_id=<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
">
                                        <button class="btn btn-default btn-xs">
                                            <i class="fa fa-bar-chart"></i>
                                        </button>
                                    </a>
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="modal"
                                            data-target="#delete-<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete-<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
" tabindex="-1" role="dialog"
                                 aria-labelledby="deletesite">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="deletesite">Delete Site?</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete site <?php echo $_smarty_tpl->tpl_vars['site']->value['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['site']->value['url'];?>
)
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/delete_site.html?id=<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
&c=y">
                                                <button type="button" class="btn btn-danger">Delete</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    <?php } else { ?>
                        <tr class="text-center">
                            <td>You have not added any site yet!</td>
                        </tr>
                    <?php }?>
                </table>
            </div>
        </div>
        <?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/chartjs/Chart.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
>
            
            $(function () {
                var areaChartData = {
                    labels: ["<?php echo $_smarty_tpl->tpl_vars['g_total_dates']->value;?>
"],
                    datasets: [
                        {
                            label: "Clicks",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [<?php echo $_smarty_tpl->tpl_vars['g_total_clicks']->value;?>
]
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
                    legendTemplate: "<ul class=\"<?php echo '<%'; ?>
=name.toLowerCase()<?php echo '%>'; ?>
-legend\"><?php echo '<%'; ?>
 for (var i=0; i<datasets.length; i++){<?php echo '%>'; ?>
<li><span style=\"background-color:<?php echo '<%'; ?>
=datasets[i].fillColor<?php echo '%>'; ?>
\"></span><?php echo '<%'; ?>
if(datasets[i].label){<?php echo '%>';
echo '<%'; ?>
=datasets[i].label<?php echo '%>';
echo '<%'; ?>
}<?php echo '%>'; ?>
</li><?php echo '<%'; ?>
}<?php echo '%>'; ?>
</ul>",
                    //Boolean - whether to make the chart responsive
                    responsive: true,
                    maintainAspectRatio: true
                };

                barChartOptions.datasetFill = false;
                barChart.Bar(barChartData, barChartOptions);
            });
            
        <?php echo '</script'; ?>
>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

<?php }
}
