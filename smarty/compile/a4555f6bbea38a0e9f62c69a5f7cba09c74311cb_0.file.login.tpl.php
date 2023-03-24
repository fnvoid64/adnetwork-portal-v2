<?php
/* Smarty version 3.1.30, created on 2017-03-24 11:08:16
  from "D:\www-root\adz_new\templates\login.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d4f010ad51f0_18916044',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a4555f6bbea38a0e9f62c69a5f7cba09c74311cb' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\login.tpl',
      1 => 1490350084,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:alerts.tpl' => 1,
  ),
),false)) {
function content_58d4f010ad51f0_18916044 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>AdzDollar Login</title>
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
/assets/img/adzdollar.png" width="250px"/></a>
	</div>
	<div class="register-box-body">
		<p class="login-box-msg">Login to dashboard</p>
        <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<form method="post" id="login">
			<div class="form-group has-feedback">
				<input type="email" name="email" class="form-control" placeholder="Email" required>
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" name="password" class="form-control" placeholder="Password" required>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/register.html">Register New</a><br/>
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
/lost_password.html">Lost Password</a>
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-info btn-block btn-flat">Login</button>
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
                },
                password: {
                    required: true
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
                },
                password: {
                    required: "Please enter your password"
                }
            }
        });
    });
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
