<?php
/* Smarty version 3.1.30, created on 2017-03-16 12:28:45
  from "D:\www-root\adzdollar\html\display\dashboard.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58ca76edc9b0d8_94523352',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fddc1d18ecaaf1fbb5f063fd3d008dedde8a8161' => 
    array (
      0 => 'D:\\www-root\\adzdollar\\html\\display\\dashboard.tpl',
      1 => 1489663721,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_58ca76edc9b0d8_94523352 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Dashboard
            <small>Verify your email</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Publisher Balance</span>
                        <span class="info-box-number">1,410</span>
                        <span class="info-box-text">Pending Balance</span>
                        <span class="info-box-number">1,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Ads Balance</span>
                        <span class="info-box-number">1,410</span>
                        <span class="info-box-text">Pending Balance</span>
                        <span class="info-box-number">1,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Pub. Clicks Today</span>
                        <span class="info-box-number">1,410</span>
                        <span class="info-box-text">Pending Balance</span>
                        <span class="info-box-number">1,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Ad Clicks Today</span>
                        <span class="info-box-number">1,410</span>
                        <span class="info-box-text">Pending Balance</span>
                        <span class="info-box-number">1,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <!-- Chart -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Bar Chart</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <div class="chart">
                    <canvas id="barChart" style="height: 216px; width: 479px;" height="216px" width="479px"></canvas>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- Sites -->
        <div class="box box-info">
            <div class="box-header with-border" style="border-bottom: 0">
                <h3 class="box-title">Sites</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
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
                                        (<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/new_site.html?step=2&id=<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
">Verify Now</a>)
                                    <?php } else { ?>
                                        <label class="label label-danger">Blocked</label>
                                    <?php }?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete-<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
" tabindex="-1" role="dialog" aria-labelledby="deletesite">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="deletesite">Delete Site?</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete site <?php echo $_smarty_tpl->tpl_vars['site']->value['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['site']->value['url'];?>
)
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/delete_site.html?id=<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
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
                        <tr align="center"><p>You have not added any site yet!</p></tr>
                    <?php }?>
                </table>
            </div>
        </div>
    </section>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/chartjs/Chart.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    
    $(function () {
        var areaChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    label: "Electronics",
                    fillColor: "rgba(210, 214, 222, 1)",
                    strokeColor: "rgba(210, 214, 222, 1)",
                    pointColor: "rgba(210, 214, 222, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
                {
                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: "Electronics",
                    fillColor: "rgba(210, 214, 222, 1)",
                    strokeColor: "rgba(210, 214, 222, 1)",
                    pointColor: "rgba(210, 214, 222, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
                {
                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [28, 48, 40, 19, 86, 27, 90]
                }
            ]
        };
        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        barChartData.datasets[1].fillColor = "#00a65a";
        barChartData.datasets[1].strokeColor = "#00a65a";
        barChartData.datasets[1].pointColor = "#00a65a";
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
</body>
</html><?php }
}
