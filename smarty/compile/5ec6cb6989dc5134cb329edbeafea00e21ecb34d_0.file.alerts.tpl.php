<?php
/* Smarty version 3.1.30, created on 2017-02-24 09:28:33
  from "D:\www-root\adzdollar\html\display\alerts.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58afeeb143a320_38004534',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5ec6cb6989dc5134cb329edbeafea00e21ecb34d' => 
    array (
      0 => 'D:\\www-root\\adzdollar\\html\\display\\alerts.tpl',
      1 => 1487924820,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58afeeb143a320_38004534 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['message']->value)) {?>
    <div class="alert alert-success text-center"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</div>
<?php }
if (isset($_smarty_tpl->tpl_vars['errors']->value)) {?>
    <div class="alert alert-danger text-center">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['errors']->value, 'error');
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
<?php }
}
}
