<?php
/* Smarty version 3.1.30, created on 2017-03-25 10:29:16
  from "D:\www-root\adz_new\templates\ads_add_list.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d6386ccf2149_06512178',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b36dd6f4e21ef2a309e9fc1f17ba3acaa24d0edd' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\ads_add_list.tpl',
      1 => 1490434155,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
    'file:footer-logged.tpl' => 1,
  ),
),false)) {
function content_58d6386ccf2149_06512178 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Welcome, <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

			<small>Create Campaigns</small>
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
			<h3 class="panel-title">Create Campaign</h3>
		</div>
		<div class="box-body text-center">
			<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/add.html?type=text" class="btn btn-success btn-lg"><i class="fa fa-pencil fa-3x"></i><br/>Create Text Campaign</a>
			<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/campaigns/add.html?type=banner" class="btn btn-info btn-lg"><i class="fa fa-photo fa-3x"></i><br/>Create Banner Campaign</a>
		</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
