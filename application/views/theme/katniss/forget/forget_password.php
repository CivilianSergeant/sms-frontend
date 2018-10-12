<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>FORGET PASSWORD</title>
		<meta name="description" content="">
		
		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Google Font
		================================================== -->
		<link href='http://fonts.googleapis.com/css?family=Lato:400,100,300,700,900italic,300italic,400italic' rel='stylesheet' type='text/css'>
		
		<!-- stylesheet
		================================================== -->
		<!-- Bootstrap -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/bootstrap.css">
		<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/bootstrap.min.css">
		<!-- Font Awesome-->
		<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/fonts/font-awesome/css/font-awesome.css">
		<!-- Base Styles-->
		<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/login.css">
		<link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/base.css">
		<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/md5.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>js/bootstrap.min.js"></script>
		<script type="text/javascript">
		var BASE_URL = "<?php echo base_url(); ?>";
		</script>
	</head>


	<!-- Login Wrapper -->
<div id="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-wrapper">
			<h3>FORGET PASSWORD</h3>
		
			<div class="login-wrapper-content">
				<div class="container-fluid">
					<div id ="loading-image" align="center" style="display: none">
						<img src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>img/loading.gif">
					</div>
					<div id="login-form-compo">
						<?php echo form_open('authenticate',array('id'=>'login','method'=>'post')); ?>
						<!-- <form name="login" id="login" method="POST" action=""> -->
							<div class="alert alert-danger" style="display:none" id="login-information">
								<strong>Wrong Information</strong>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Username</label>

								<div class="input-group margin-bottom-sm">
									<span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
									<input type="text" class="form-control" id="exampleInputUsername1" name="username" value="" placeholder="Enter Your Email Or Username" required="required">
								</div>
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Password</label>

								<div class="input-group margin-bottom-sm">
									<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
									<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Enter Your Password" required="required">
								</div>
							</div>
							<div class="checkbox">
								<label>
									<a href="<?php echo site_url('login');?>"><p>Click To Login</p>
								</label>
							</div>
							
							<input type="hidden" id="hash" value=""/>
							<button type="submit" class="btn btn-default">Login</button>
						</form> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- / -->
<script type="text/javascript">
$("#login").submit(function(){
	$("#loading-image").removeAttr("style");
	$("#login-form-compo").css({'display': 'none'});

	$.ajax({
		url: $(this).attr('action'),
		method:'post',
		data:{username:$("#exampleInputUsername1").val(),password:md5($("#exampleInputPassword1").val())},
		success:function(e)
		{
			if(e == 1){
				window.location.reload();
			}else{
				$("#login-information").removeAttr("style");
				$("#loading-image").css({'display': 'none'});
				$("#login-form-compo").removeAttr("style");
			}
		}
	});

	return false;
});
</script>

