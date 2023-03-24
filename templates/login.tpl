<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>AdzDollar Login</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="{$root}/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="{$root}/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="{$root}/assets/css/AdminLTE.min.css">
</head>
<body class="hold-transition register-page" style="background: #454E59;">
<div class="register-box">
	<div class="register-logo">
		<a href="/"><img src="{$root}/assets/img/adzdollar.png" width="250px"/></a>
	</div>
	<div class="register-box-body">
		<p class="login-box-msg">Login to dashboard</p>
        {include file="alerts.tpl"}
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
					<a href="{$site_url}/register.html">Register New</a><br/>
					<a href="{$site_url}/lost_password.html">Lost Password</a>
				</div>
				<div class="col-xs-4">
					<button type="submit" class="btn btn-info btn-block btn-flat">Login</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="{$root}/assets/js/jquery.min.js"></script>
<script src="{$root}/assets/js/bootstrap.min.js"></script>
<script src="{$root}/assets/plugins/validator/jquery.validate.min.js"></script>
<script>
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
</script>
</body>
</html>