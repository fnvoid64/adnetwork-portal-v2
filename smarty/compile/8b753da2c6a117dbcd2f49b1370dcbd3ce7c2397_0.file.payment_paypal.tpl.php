<?php
/* Smarty version 3.1.30, created on 2017-04-09 14:09:57
  from "D:\www-root\adz_new\templates\payment_paypal.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58ea24955e7b37_01550329',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8b753da2c6a117dbcd2f49b1370dcbd3ce7c2397' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\payment_paypal.tpl',
      1 => 1491414541,
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
function content_58ea24955e7b37_01550329 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Welcome, <?php echo $_smarty_tpl->tpl_vars['name']->value;?>

            <small>Payment Info</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-dashboard"></i> <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/index.html">Dashboard</a></li>
            <li class="active"><i class="fa fa-bullhorn"></i> Payment Info</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="panel-title">Payment Info</h3>
                    </div>
                    <div class="box-body">
                        <?php if (isset($_smarty_tpl->tpl_vars['prefered']->value)) {?>
                            <p>Prefered payement method is set to: <b><?php echo $_smarty_tpl->tpl_vars['prefered']->value['type'];?>
</b>
                                <a href="?prefered">
                                    <button class="btn btn-xs btn-primary">
                                        Change prefered payment option
                                    </button>
                                </a>
                            </p>
                        <?php }?>
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#details">Paypal</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/payment.html?act=skrill">Skrill</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard/payment.html?act=bank">Bank</a></li>
                        </ul>
                        <div class="tab-content" style="border: 1px solid #ddd; border-top:0; padding: 10px">
                            <div id="details" class="tab-pane fade in active">

                                <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                                <form method="post">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Payee Name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="email" placeholder="PayPal Email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prefered">
                                            <input type="checkbox" name="prefered" value="1">
                                            Set as prefered payment method for future payments.
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="pin" placeholder="PIN" class="form-control" required>
                                    </div>
                                    <button class="btn btn-success" type="submit">Save</button>
                                </form>
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
