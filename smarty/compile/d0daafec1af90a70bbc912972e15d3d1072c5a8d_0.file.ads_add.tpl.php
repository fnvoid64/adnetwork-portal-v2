<?php
/* Smarty version 3.1.30, created on 2017-03-25 10:35:56
  from "D:\www-root\adz_new\templates\ads_add.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d639fc919856_16786211',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd0daafec1af90a70bbc912972e15d3d1072c5a8d' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\ads_add.tpl',
      1 => 1490434549,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header-logged.tpl' => 1,
    'file:footer-logged.tpl' => 1,
  ),
),false)) {
function content_58d639fc919856_16786211 (Smarty_Internal_Template $_smarty_tpl) {
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

	
<?php if (isset($_smarty_tpl->tpl_vars['ads_add_error']->value)) {?>
	<div class="alert alert-danger text-center">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ads_add_error']->value, 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
echo $_smarty_tpl->tpl_vars['error']->value;?>
<br/><?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

	</div>
<?php }?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="panel-title">Add <?php echo $_smarty_tpl->tpl_vars['type']->value;?>
 Campaign</h3>
			</div>
			<div class="box-body">
			<?php echo $_smarty_tpl->tpl_vars['ads_add_form']->value;?>

			</div>
		</div>
	</div>
</div>	
	
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/select2/select2.full.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
	$(".select2").select2();
<?php echo '</script'; ?>
><?php }
}
