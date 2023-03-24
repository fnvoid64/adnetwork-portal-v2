<?php
/* Smarty version 3.1.30, created on 2017-03-24 11:07:16
  from "D:\www-root\adz_new\templates\register.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_58d4efd4d22327_70177768',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9f7b1e4b3c0d26c7b91dd05b6dc7ef87e2605c1c' => 
    array (
      0 => 'D:\\www-root\\adz_new\\templates\\register.tpl',
      1 => 1490350035,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:alerts.tpl' => 1,
  ),
),false)) {
function content_58d4efd4d22327_70177768 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>AdzDollar Sign Up</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/select2/select2.css">
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/css/AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition register-page" style="background: #454E59;">
<div class="register-box" style="margin-top: 3%">
	<div class="register-logo">
		<a href="/"><img src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/img/adzdollar.png" width="250px"/></a>
	</div>
	<div class="register-box-body">
		<p class="login-box-msg">Register adzdollar account</p>
        <?php $_smarty_tpl->_subTemplateRender("file:alerts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<form method="post" id="register">
			<div class="form-group has-feedback">
				<input type="text" name="fullname" class="form-control" placeholder="Full name">
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="email" name="email" class="form-control" placeholder="Email">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<select name="country" class="select-country form-control">
					<?php echo $_smarty_tpl->tpl_vars['country_options']->value;?>

				</select>
				<span class="glyphicon glyphicon-world form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" name="password1" id="password1" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" name="password2" class="form-control" placeholder="Retype password">
				<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" name="mobile" class="form-control" placeholder="Contact No.">
				<span class="fa fa-phone form-control-feedback"></span>
			</div>
			<div class="form-group" align="center">
                <?php echo $_smarty_tpl->tpl_vars['captcha']->value;?>

			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" name="terms"> I agree to the <a href="#">terms</a>
						</label>
					</div>
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-info btn-block btn-flat">Register</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php echo '<script'; ?>
 src='https://www.google.com/recaptcha/api.js'><?php echo '</script'; ?>
>
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
/assets/plugins/iCheck/icheck.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/select2/select2.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
/assets/plugins/validator/jquery.validate.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    });

    $(document).ready(function() {
        $(".select-country").select2({
            placeholder: "Country"
        });
        $("#register").validate({
            rules: {
                fullname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true
                },
                country: {
                    required: true
                },
                password1: {
                    required: true
                },
                password2: {
                    required: true,
                    equalTo: "#password1"
                },
                terms: {
                    required: true
                }
            },
            errorPlacement: function(error, element) {
                element.attr("placeholder", error.text());
                if(element.attr("name") == "country") {
                    $('.select-country').select2({
                        placeholder: "Please select country"
                    });
                }
                if(element.attr("name") == "terms") {
                    alert("Accept Terms Please!");
                }
            },
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            messages: {
                fullname: {
                    required: "Please enter your full name",
                },
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email"
                },
                mobile: {
                    required: "Please enter your mobile number"
                },
                password1: {
                    required: "Please enter your password"
                },
                password2: {
                    required: "Please re-type password",
                    equalTo: "Passwords does not match"
                },
                terms : {
                    required: "Please accept terms and condition"
                },
                country : {
                    required: "Please select a country"
                }
            }

        });
    });
<?php echo '</script'; ?>
>
</body>
</html><?php }
}
