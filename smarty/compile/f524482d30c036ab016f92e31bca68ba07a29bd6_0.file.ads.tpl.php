<?php
/* Smarty version 3.1.30, created on 2017-03-26 07:32:06
  from "D:\www-root\adz_new\templates\ads.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d75256196e27_78214951',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f524482d30c036ab016f92e31bca68ba07a29bd6' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\ads.tpl',
      1 => 1490506323,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
    'file:alerts.tpl' => 1,
    'file:footer-logged.tpl' => 1,
  ),
),false)) {
function content_58d75256196e27_78214951 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

			<small>Campaigns</small>
		</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-dashboard"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/index.html">Dashboard</a></li>
			<li class="active"><i class="fa fa-bullhorn"></i> Campaigns</li>
		</ol>
	</section>
	<section class="content">

        <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<div class="row">
<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-header">
			<div class="row">
				<div class="col-xs-9">
					<h3 class="panel-title">Campaigns</h3>
				</div>
				<div class="col-xs-3 text-right">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/add.html">
						<button class="btn btn-success btn-sm">
							<i class="fa fa-plus"></i>
							 Create Campaign
						</button>
					</a>
				</div>
			</div>
		</div>
		<div class="box-body text-center" style="padding: 0;">
		<?php if (empty($_smarty_tpl->tpl_vars['adslist']->value)) {?>
			<i>Sorry, You haven't created any campaign yet!</i>
		<?php } else { ?>
		<table class="table table-condensed table-bordered">
		<tr>
			<th class="text-center">Name</th>
			<th class="text-center">URL</th>
			<th class="text-center">Type</th>
			<th class="text-center">Status</th>
            <th class="text-center">CPC ($)</th>
			<th class="text-center">Actions</th>
		</tr>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['adslist']->value, 'ad');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ad']->value) {
?>
			<tr>
				<td><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/details.html?id=<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['ad']->value['name'];?>
</a></td>
				<td>http://<?php echo $_smarty_tpl->tpl_vars['ad']->value['url'];?>
</td>
                <td>
                    <?php if ($_smarty_tpl->tpl_vars['ad']->value['type'] == 1) {?>
                        Banner
                    <?php } else { ?>
                    Text
                    <?php }?>
                </td>
				<td>
				<?php if ($_smarty_tpl->tpl_vars['ad']->value['status'] == 1) {?>
					<span class="label label-success">Running</span>
				<?php } elseif ($_smarty_tpl->tpl_vars['ad']->value['status'] == 2) {?>
					<span class="label label-warning">Pending</span>
				<?php } elseif ($_smarty_tpl->tpl_vars['ad']->value['status'] == 3) {?>
					<span class="label label-default">Paused</span>
				<?php } else { ?>
					<span class="label label-danger">Rejected</span>
				<?php }?>
				</td>
                <td><?php echo $_smarty_tpl->tpl_vars['ad']->value['amount'];?>
</td>
				<td>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/edit.html?id=<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
">
                        <button class="btn btn-default btn-xs"><i class="fa fa-edit"></i></button>
                    </a>
                    <?php if ($_smarty_tpl->tpl_vars['ad']->value['status'] == 1) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/change.html?id=<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
">
                            <button class="btn btn-default btn-xs"><i class="fa fa-pause"></i></button>
                        </a>
                    <?php } else { ?>
                        <?php if ($_smarty_tpl->tpl_vars['ad']->value['status'] == 3) {?>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/change.html?id=<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
">
                                <button class="btn btn-default btn-xs"><i class="fa fa-play"></i></button>
                            </a>
                        <?php }?>
                    <?php }?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/reports.html?type=ad&ad_id=<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
">
                        <button class="btn btn-default btn-xs">
                            <i class="fa fa-bar-chart"></i>
                        </button>
                    </a>
                    <a href="#" data-toggle="modal" data-target="#delete-<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
">
                        <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                    </a>
                </td>
			</tr>
            <div class="modal fade" id="delete-<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
" tabindex="-1" role="dialog" aria-labelledby="deletead">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deletead">Delete Campaign?</h4>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete campaign <?php echo $_smarty_tpl->tpl_vars['ad']->value['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['ad']->value['url'];?>
)
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/del.html?id=<?php echo $_smarty_tpl->tpl_vars['ad']->value['id'];?>
&confirm=yes">
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

		</table>
	</div>
<?php }?>
</div>
<?php if (isset($_smarty_tpl->tpl_vars['paging']->value)) {
echo $_smarty_tpl->tpl_vars['paging']->value;?>

<?php }?>
</div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
