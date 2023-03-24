<?php
/* Smarty version 3.1.30, created on 2017-03-26 07:46:14
  from "D:\www-root\adz_new\templates\change_password.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d755a6b1c9a4_35440186',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '06deef6645413c424b45e92380d547c718d69584' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\change_password.tpl',
      1 => 1490507172,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
    'file:footer-logged.tpl' => 1,
  ),
),false)) {
function content_58d755a6b1c9a4_35440186 (Smarty_Internal_Template $_smarty_tpl) {
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
<div class="row">
<div class="col-md-12">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="panel-title">Change Password</h3>
		</div>
		<div class="box-body">
		<ul class="nav nav-tabs">
    		<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/edit_profile.html">My Details</a></li>
			<li class="active"><a data-toggle="tab" href="#cp">Change Password</a></li>
		</ul>
		<div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
			<div id="cp" class="tab-pane fade in active">
	
<?php if (isset($_smarty_tpl->tpl_vars['message']->value)) {?>
	<div class="alert alert-success text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php echo $_smarty_tpl->tpl_vars['message']->value;?>

	</div>
<?php }?>
	
<?php if (isset($_smarty_tpl->tpl_vars['change_pw_error']->value)) {?>
	<div class="alert alert-danger text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['change_pw_error']->value, 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
echo $_smarty_tpl->tpl_vars['error']->value;?>
<br/><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

	</div>
<?php }
echo $_smarty_tpl->tpl_vars['change_pw_form']->value;?>

</div></div>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<?php }
}
