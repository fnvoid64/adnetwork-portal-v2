<?php
/* Smarty version 3.1.30, created on 2017-03-26 08:44:46
  from "D:\www-root\adz_new\templates\lp.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d7635e48e218_09655078',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '469f710d280f5ef3b086d4050cb6723bfebde202' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\lp.tpl',
      1 => 1490510684,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:footer-logged.tpl' => 1,
  ),
),false)) {
function content_58d7635e48e218_09655078 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<section class="content">
    <div class="row">
        <div class="register-box" style="margin-top: 3%">
            <div class="register-logo">
                <a href="/"><img src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/img/adzdollar.png" width="250px"/></a>
            </div>
            <div class="register-box-body">
                <p class="login-box-msg">Lost Password</p>
                <?php if (isset($_smarty_tpl->tpl_vars['message']->value)) {?>
                    <div class="alert alert-success text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php echo $_smarty_tpl->tpl_vars['message']->value;?>

                    </div>
                <?php }?>

                <?php if (isset($_smarty_tpl->tpl_vars['lp_error']->value)) {?>
                    <div class="alert alert-danger text-center">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lp_error']->value, 'error');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['error']->value) {
?>
                            <?php echo $_smarty_tpl->tpl_vars['error']->value;?>

                            <br/>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

                    </div>
                <?php }?>

                <?php echo $_smarty_tpl->tpl_vars['lp_form']->value;?>

            </div>
        </div>
    </div>
<?php $_smarty_tpl->_subTemplateRender("file:footer-logged.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
