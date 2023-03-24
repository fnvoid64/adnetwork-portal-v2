<?php
/* Smarty version 3.1.30, created on 2017-03-26 07:54:50
  from "D:\www-root\adz_new\templates\stats.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d757aa8551f6_42772707',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4510030a808944f0f72eaf27fe452ddb0464fb98' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\stats.tpl',
      1 => 1490507688,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
  ),
),false)) {
function content_58d757aa8551f6_42772707 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/daterangepicker/daterangepicker.css">
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

			<small>Reports</small>
		</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-dashboard"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/index.html">Dashboard</a></li>
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
					<?php if (isset($_smarty_tpl->tpl_vars['sites']->value)) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sites']->value, 'site');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['site']->value) {
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['site']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['site']->value['name'];?>
</option>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

					<?php }?>
					</select>
				</div>
				<div class="col-md-3">
					<select name="ad_id" onchange="changeCat('?type=ad&ad_id=' + this.value)" class="form-control">
						<option>Select Advertisement</option>
					<?php if (isset($_smarty_tpl->tpl_vars['ads']->value)) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ads']->value, 'ad');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ad']->value) {
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['ad']->value['name'];?>
</option>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

					<?php }?>
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
		<?php if (isset($_smarty_tpl->tpl_vars['no_stats']->value)) {?>
			<p class="text-center"><i>Ooops, Sorry! Looks like no statistics is available!</i></p>
		<?php } else { ?>
			<table class="table table-hover table-bordered table-striped table-condensed">
				<th class="text-center">Date</th>
				<th class="text-center">Clicks</th>
				<th class="text-center">Impressions</th>
				<?php if (isset($_smarty_tpl->tpl_vars['itsad']->value)) {?>
				<th class="text-center">Spent (Estimated)[$]</th>
				<?php } else { ?>
				<th class="text-center">Earnings ($)</th>
				<?php }?>
				<th class="text-center">CTR (%)</th>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['dates']->value, 'currdate', false, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['currdate']->value) {
?>
				<tr align="center">
					<td><?php echo $_smarty_tpl->tpl_vars['currdate']->value;?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['clicks']->value[$_smarty_tpl->tpl_vars['i']->value];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['impressions']->value[$_smarty_tpl->tpl_vars['i']->value];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['earnings']->value[$_smarty_tpl->tpl_vars['i']->value];?>
</td>
					<td><?php echo $_smarty_tpl->tpl_vars['ctr']->value[$_smarty_tpl->tpl_vars['i']->value];?>
</td>
				</tr>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

			</table>
		<?php }?>
		</div>
	</div>
	<?php if (isset($_smarty_tpl->tpl_vars['paging']->value)) {?>
	<?php echo $_smarty_tpl->tpl_vars['paging']->value;?>

	<?php }?>
</div>
</div>
</section>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/js/jquery.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/js/bootstrap.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/js/app.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/daterangepicker/moment.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/daterangepicker/daterangepicker.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
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
<?php echo '</script'; ?>
>
</body></html><?php }
}
