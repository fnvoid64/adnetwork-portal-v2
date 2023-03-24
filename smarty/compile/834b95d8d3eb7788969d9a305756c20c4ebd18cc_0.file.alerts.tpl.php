<?php
/* Smarty version 3.1.30, created on 2017-03-24 11:29:31
  from "D:\www-root\adz_new\templates\alerts.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d4f50beb79e5_61040082',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '834b95d8d3eb7788969d9a305756c20c4ebd18cc' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\alerts.tpl',
      1 => 1490351369,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58d4f50beb79e5_61040082 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['message']->value)) {?>
    <div class="alert alert-success text-center">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo $_smarty_tpl->tpl_vars['message']->value;?>

    </div>
<?php }
if (isset($_smarty_tpl->tpl_vars['errors']->value)) {?>
    <div class="alert alert-danger text-center">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
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
