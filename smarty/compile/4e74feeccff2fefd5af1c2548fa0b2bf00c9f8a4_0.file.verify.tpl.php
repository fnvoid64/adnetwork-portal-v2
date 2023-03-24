<?php
/* Smarty version 3.1.30, created on 2017-02-24 13:40:22
  from "D:\www-root\adzdollar\html\display\verify.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58b029b696e9f9_33401978',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4e74feeccff2fefd5af1c2548fa0b2bf00c9f8a4' => 
    array (
      0 => 'D:\\www-root\\adzdollar\\html\\display\\verify.tpl',
      1 => 1487940020,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:alerts.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_58b029b696e9f9_33401978 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Email Verification
            <small>Verify your email</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/dashboard.html"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Email verification</li>
        </ol>
    </section>
    <section class="content">
        <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <div class="alert alert-info">It seems you have not verified your email yet! In order to use our services you first need to verify your email account.
        Please check your inbox of email <b><?php echo $_smarty_tpl->tpl_vars['email']->value;?>
</b>. If you did not receive our email, Please <b><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/verify.html?resend">
            Click here
        </a></b> to resend the verification email.</div>
    </section>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html><?php }
}
