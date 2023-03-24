<?php
/* Smarty version 3.1.30, created on 2017-03-26 06:59:42
  from "D:\www-root\adz_new\templates\ads_details.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d74abedbad99_81093273',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '86282c525fe9c52c6105b7770b7d9bb23d6633ff' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\ads_details.tpl',
      1 => 1490504381,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
    'file:footer-logged.tpl' => 1,
  ),
),false)) {
function content_58d74abedbad99_81093273 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

			<small>Campaign Details</small>
		</h1>
		<ol class="breadcrumb">
			<li><i class="fa fa-dashboard"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/index.html">Dashboard</a></li>
			<li class="active"><i class="fa fa-bullhorn"></i> Campaign Details</li>
		</ol>
	</section>
	<section class="content">
	
<?php if (isset($_smarty_tpl->tpl_vars['message']->value)) {?>
	<div class="alert alert-success text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php echo $_smarty_tpl->tpl_vars['message']->value;?>

	</div>
<?php }?>

<div class="row">
<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="panel-title">Campaign Details</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-5">
					<ul class="list-group">
						<li class="list-group-item"><b>Name:</b> <?php echo $_smarty_tpl->tpl_vars['ads']->value['name'];?>
</li>
						<li class="list-group-item"><b>URL:</b> http://<?php echo $_smarty_tpl->tpl_vars['ads']->value['url'];?>
</li>
						<li class="list-group-item"><b>Adult:</b> <?php if ($_smarty_tpl->tpl_vars['ads']->value['adult'] == 1) {?>Yes<?php } else { ?>No<?php }?></li>
						<li class="list-group-item"><b>Type:</b> <?php if ($_smarty_tpl->tpl_vars['ads']->value['type'] == 1) {?>Banner<?php } else { ?>Text<?php }?></li>
						<?php if ($_smarty_tpl->tpl_vars['ads']->value['type'] == 1) {?>
						 <li class="list-group-item"><b>Banners:</b><br/>
							<?php if (isset($_smarty_tpl->tpl_vars['banners']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['banners']->value, 'banner');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['banner']->value) {
?>
									<img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/uploads/<?php echo $_smarty_tpl->tpl_vars['banner']->value;?>
.gif" alt="banner" width="130px" height="30px"/><br/>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

							<?php } else { ?>
								<img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/uploads/<?php echo $_smarty_tpl->tpl_vars['ads']->value['banners'];?>
.gif" alt="banner" width="130px" height="30px"/><br/>
							<?php }?>
						 </li>
						<?php } else { ?>
						<li class="list-group-item">
						 <b>Titles:</b>
						 <?php echo $_smarty_tpl->tpl_vars['ads']->value['titles'];?>

						 </li>
						<?php }?>
						<li class="list-group-item"><b>Status:</b> 
						<?php if ($_smarty_tpl->tpl_vars['ads']->value['status'] == 1) {?>
							<span class="label label-success">Running</span>
						<?php } elseif ($_smarty_tpl->tpl_vars['ads']->value['status'] == 2) {?>
							<span class="label label-warning">Pending</span>
						<?php } elseif ($_smarty_tpl->tpl_vars['ads']->value['status'] == 3) {?>
							<span class="label label-danger">Paused</span>
						<?php } elseif ($_smarty_tpl->tpl_vars['ads']->value['status'] == 4) {?>
							<span class="label label-danger">Rejected</span>
						<?php } else { ?>
							<span class="label label-danger">Blocked</span>
						<?php }?>
						</li>
						<li class="list-group-item"><b>Countrys:</b> <?php echo $_smarty_tpl->tpl_vars['ads']->value['countrys'];?>
</li>
						<li class="list-group-item"><b>Devices:</b> <?php echo $_smarty_tpl->tpl_vars['ads']->value['devices'];?>
</li>
						<li class="list-group-item text-center">
							<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/edit.html?id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
" class="btn btn-app"><i class="fa fa-edit"></i> Edit Campaign</a>
							<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete Campaign</a>
							<?php if ($_smarty_tpl->tpl_vars['ads']->value['status'] == 1) {?>
							<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/change.html?id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
" class="btn btn-app"><i class="fa fa-pause"></i> Pause Ad</a>
							<?php } else { ?>
								<?php if ($_smarty_tpl->tpl_vars['ads']->value['status'] == 3) {?>
								<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/change.html?id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
" class="btn btn-app"><i class="fa fa-play"></i> Run Ad</a>
								<?php }?>
							<?php }?>
						</li>
					</ul>
					<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
					  	<div class="modal-dialog">
						    <div class="modal-content">
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        	<h4 class="modal-title">Delete Ad?</h4>
						      	</div>
						      	<div class="modal-body">
						        	<p>Delete Ad <?php echo $_smarty_tpl->tpl_vars['ads']->value['name'];?>
 (http://<?php echo $_smarty_tpl->tpl_vars['ads']->value['url'];?>
)</p>
						      	</div>
						      	<div class="modal-footer">
						        	<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
						        	<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/del.html?id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
&confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
						      	</div>
						    </div>
					  	</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="row">
					<a href="/stats?type=ad&ad_id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
&date=<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-aqua text-center">
					        <div class="inner">
					            <h3><?php echo $_smarty_tpl->tpl_vars['today_clicks']->value;?>
</h3>
					            <p>Today Clicks</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=ad&ad_id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-red text-center">
					        <div class="inner">
					            <h3><?php echo $_smarty_tpl->tpl_vars['total_clicks']->value;?>
</h3>
					            <p>Total Clicks</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=ad&ad_id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
&date=<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-green text-center">
					        <div class="inner">
					            <h3>$<?php echo $_smarty_tpl->tpl_vars['today_earn']->value;?>
</h3>
					            <p>Today Spent</p>
					        </div>
					    </div>
					</div>
					</a>
					<a href="/stats?type=ad&ad_id=<?php echo $_smarty_tpl->tpl_vars['ads']->value['id'];?>
">
					<div class="col-lg-3 col-xs-6">
					    <div class="small-box bg-yellow text-center">
					        <div class="inner">
					            <h3>$<?php echo $_smarty_tpl->tpl_vars['total_earn']->value;?>
</h3>
					            <p>Total Spent</p>
					        </div>
					    </div>
					</div>
					</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
