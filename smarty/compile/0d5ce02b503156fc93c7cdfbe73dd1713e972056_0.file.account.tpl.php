<?php
/* Smarty version 3.1.30, created on 2017-03-26 08:22:16
  from "D:\www-root\adz_new\templates\account.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d75e181b7fd7_50329215',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0d5ce02b503156fc93c7cdfbe73dd1713e972056' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\account.tpl',
      1 => 1490509332,
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
function content_58d75e181b7fd7_50329215 (Smarty_Internal_Template $_smarty_tpl) {
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
                        <h3 class="panel-title">Account Info</h3>
                    </div>
                    <div class="box-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#details">My Details</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/change_password.html">Change Password</a></li>
                        </ul>
                        <div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
                            <div id="details" class="tab-pane fade in active">

                                <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                                <?php echo $_smarty_tpl->tpl_vars['form']->value;?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<?php }
}
