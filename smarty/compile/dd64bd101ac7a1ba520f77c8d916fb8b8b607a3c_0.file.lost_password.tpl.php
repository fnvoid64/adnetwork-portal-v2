<?php
/* Smarty version 3.1.30, created on 2017-02-25 09:13:23
  from "D:\www-root\adzdollar\html\display\lost_password.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58b13ca3339e24_66838923',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dd64bd101ac7a1ba520f77c8d916fb8b8b607a3c' => 
    array (
      0 => 'D:\\www-root\\adzdollar\\html\\display\\lost_password.tpl',
      1 => 1488010400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:alerts.tpl' => 1,
  ),
),false)) {
function content_58b13ca3339e24_66838923 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdzDollar Lost password</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/css/AdminLTE.min.css">
</head>
<body class="hold-transition register-page" style="background: #454E59;">
<div class="register-box">
    <div class="register-logo">
        <a href="/"><img src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/img/logo.png" width="250px"/></a>
    </div>
    <div class="register-box-body">
        <p class="login-box-msg">Reset your password</p>
        <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <form method="post" id="login">
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8" style="padding-top: 5px;">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/login.html">Login now</a>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-info btn-block btn-flat">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/js/jquery.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/js/bootstrap.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/validator/jquery.validate.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(document).ready(function() {
        $("#login").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            errorPlacement: function(error, element) {
                element.attr("placeholder", error.text());
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            messages: {
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email"
                }
            }
        });
    });
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
