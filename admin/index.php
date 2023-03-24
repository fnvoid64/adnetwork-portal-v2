<?php
include "init.php";

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

$admin->head("");
echo '<section class="content-header">';
echo '<h1>Welcome, ' . $admin->data("admin");

if ($admin->data("role") == 1) {
    echo '<small>(Head Admin)</small>';
} elseif ($admin->data("role") == 2) {
    echo '<small>(Second Admin)</small>';
} else {
    echo '<small>(Third Admin)</small>';
}

echo <<<HTML
</h1>
<ol class="breadcrumb">
    <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
</ol>
</section>
<section class="content">
HTML;

$tips = array();

$noti = array();
$sites = $db->select("sites", "id", array("status" => 2));

if ($sites->num_rows > 0) {
    $noti[] = '<a href="sites.php?type=pending" class="text-warning">' . $sites->num_rows . ' Sites Pending For Approval</a>';
}

$ads = $db->select("ads", "id", array("status" => 2));

if ($ads->num_rows > 0) {
    $noti[] = '<a href="ads.php?type=pending" class="text-warning">' . $ads->num_rows . ' Ads Pending For Approval</a>';
}

$invoices = $db->select("invoices", "id", array("status" => 2));

if ($invoices->num_rows > 0) {
    $noti[] = '<a href="invoices.php?type=pending" class="text-danger">' . $invoices->num_rows . ' Unpaid Invoices</a>';
}

$users = $db->select("users", "id", array());
$sites = $db->select("sites", "id", array());
$ads = $db->select("ads", "id", array());
$invoices = $db->select("invoices", "id", array());

$click_stats = $db->select("clicks", "vclicks", array("date" => $date));
$today_clicks = 0;

if ($click_stats->num_rows != 0) {
    while ($r = $click_stats->fetch_assoc()) {
        $today_clicks = $today_clicks + $r["vclicks"];
    }
}

$click_statst = $db->select("clicks", "vclicks", array());
$total_clicks = 0;

if ($click_statst->num_rows != 0) {
    while ($r = $click_statst->fetch_assoc()) {
        $total_clicks = $total_clicks + $r["vclicks"];
    }
}

$earn_stats = $db->select("earnings", "earnings", array("date" => $date));
$today_earnings = "0.00";

if ($earn_stats->num_rows != 0) {
    while ($r = $earn_stats->fetch_assoc()) {
        $today_earnings = $today_earnings + $r["earnings"];
    }
}

$earn_statst = $db->select("earnings", "earnings", array());
$total_earnings = "0.00";

if ($earn_statst->num_rows != 0) {
    while ($r = $earn_statst->fetch_assoc()) {
        $total_earnings = $total_earnings + $r["earnings"];
    }
}

echo <<<HTML
<div class="row">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
        <div class="inner">
            <h3>{$users->num_rows}</h3>
            <p>Total Users</p>
        </div>
        <div class="icon">
            <i class="fa fa-user"></i>
        </div>
        <a href="users.php" class="small-box-footer">More Users <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red">
        <div class="inner">
            <h3>{$sites->num_rows}</h3>
            <p>Total Sites</p>
        </div>
        <div class="icon">
            <i class="fa fa-list"></i>
        </div>
        <a href="sites.php" class="small-box-footer">More Sites <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
        <div class="inner">
            <h3>{$ads->num_rows}</h3>
            <p>Total Ads</p>
        </div>
        <div class="icon">
            <i class="fa fa-bullhorn"></i>
        </div>
        <a href="ads.php" class="small-box-footer">More Ads <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow">
        <div class="inner">
            <h3>{$invoices->num_rows}</h3>
            <p>Total Invoices</p>
        </div>
        <div class="icon">
            <i class="fa fa-file"></i>
        </div>
        <a href="invoices.php" class="small-box-footer">More Invoices <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-blue">
        <div class="inner">
            <h3>{$today_clicks}</h3>
            <p>Today Clicks</p>
        </div>
        <div class="icon">
            <i class="fa fa-hand-pointer-o"></i>
        </div>
        <a href="stats.php" class="small-box-footer">More Stats <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
        <div class="inner">
            <h3>\${$today_earnings}</h3>
            <p>User Earnings Today</p>
        </div>
        <div class="icon">
            <i class="fa fa-money"></i>
        </div>
        <a href="stats.php" class="small-box-footer">More Stats <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow">
        <div class="inner">
            <h3>{$total_clicks}</h3>
            <p>Total Clicks</p>
        </div>
        <div class="icon">
            <i class="fa fa-hand-pointer-o"></i>
        </div>
        <a href="stats.php" class="small-box-footer">More Stats <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red">
        <div class="inner">
            <h3>\${$total_earnings}</h3>
            <p>User Earnings Total</p>
        </div>
        <div class="icon">
            <i class="fa fa-money"></i>
        </div>
        <a href="stats.php" class="small-box-footer">More Stats <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
</div>
HTML;

// Chart 1
echo <<<HTML
<div class="row">
<div class="col-md-8">
    <!-- AREA CHART -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Last 7 day clicks</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="chart">
                <canvas height="250" width="100%" id="areaChart" style="height: 250px; width: 510px;"></canvas>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
<div class="col-md-4">
<div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Tips & Notifications</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
        <ul class="list-group">
HTML;

if (!empty($tips)) {
    foreach ($tips as $tip) {
        echo '<li class="list-group-item"><i class="fa fa-hand-o-right"></i> ' . $tip . '</li>';
    }
}

if (!empty($noti)) {
    foreach ($noti as $notify) {
        echo '<li class="list-group-item"><i class="fa fa-bell-o"></i> ' . $notify . '</li>';
    }
}

echo '</ul></div></div></div>
</div>
</div>
</div>';


for ($iDay = 6; $iDay >= 0; $iDay--) {
    $aDays[7 - $iDay] = $currDate = date('Y-m-d', strtotime("-" . $iDay . " day"));

    $click_data = $db->select("clicks", "vclicks", array("date" => $currDate));

    if ($click_data->num_rows == 0) {
        $clicks_stats[7 - $iDay] = 0;
    } else {
        $t_clicks = 0;

        while ($r = $click_data->fetch_assoc()) {
            $t_clicks = $t_clicks + $r["vclicks"];
        }

        $clicks_stats[7 - $iDay] = $t_clicks;
    }
}

$total_dates = implode("\",\"", $aDays);
$total_clicks = implode(",", $clicks_stats);

$footer = <<<HTML
<script src="js/Chart.min.js"></script>
<script>
      $(function () {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
          labels: ["{$total_dates}"],
          datasets: [
            {
              label: "Clicks",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [{$total_clicks}]
            }
          ]
        };

        var areaChartOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions);

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
          {
            value: 700,
            color: "#f56954",
            highlight: "#f56954",
            label: "Chrome"
          },
          {
            value: 500,
            color: "#00a65a",
            highlight: "#00a65a",
            label: "IE"
          },
          {
            value: 400,
            color: "#f39c12",
            highlight: "#f39c12",
            label: "FireFox"
          },
          {
            value: 600,
            color: "#00c0ef",
            highlight: "#00c0ef",
            label: "Safari"
          },
          {
            value: 300,
            color: "#3c8dbc",
            highlight: "#3c8dbc",
            label: "Opera"
          },
          {
            value: 100,
            color: "#d2d6de",
            highlight: "#d2d6de",
            label: "Navigator"
          }
        ];
        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);

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
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to make the chart responsive
          responsive: true,
          maintainAspectRatio: true
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
      });
    </script>
HTML;

$admin->foot($footer);
