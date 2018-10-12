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
		<title><?php echo $this->config->item('login_page_title'); ?> Login Panel</title>
		<meta name="description" content="">
		
		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Google Font
		================================================== -->
		
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
		<script type="text/javascript" src="<?php echo base_url('public/theme/katniss'); ?>/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url('public/theme/katniss'); ?>/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		var BASE_URL = "<?php echo base_url(); ?>";
		var SITE_URL = "<?php echo site_url(); ?>";
		</script>
	</head>
<?php //print_r($_SESSION); ?>
	<!-- Login Wrapper -->
<div id="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-wrapper">
			<h3><?php $sign = $this->config->item('login_page_title'); echo ($sign!=null)? $sign : ''; ?> Login Panel</h3>
		
			<div class="login-wrapper-content">
				<div class="container-fluid">
					<div id ="loading-image" align="center" style="display: none">
						<img src="<?php echo base_url('public/theme/katniss'); ?>/img/loading.gif">
					</div>

					<?php
						// print_r($captcha);

					?>
					<div id="login-form-compo">
						<?php echo form_open('authenticate',array('id'=>'login','method'=>'post')); ?>
						<!-- <form name="login" id="login" method="POST" action=""> -->
							<div class="alert alert-danger" style="display:none" id="login-information">
								<strong>Wrong Information</strong>
							</div>
							<?php
								if(!empty($_GET['q'])){
									if($_GET['q'] == '3'){
										?>
											<div class="alert alert-danger" style="" id="login-information">
												<strong>Wrong Captcha</strong>
											</div>
										<?php
									}
								}

								/*$cap = 1234;////create_captcha($vals);
								
								$this->session->set_userdata('captcha',$cap);
								$captcha = $this->session->userdata('captcha');*/
								//echo $this->custom_captcha->get_captcha_code($captcha);
								//echo $this->session->userdata('captcha');
							?>
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
							<div class="form-group" >
								<label for="captcha">Type Captcha Text</label>

								<div class="input-group margin-bottom-sm">
									<span class="input-group-addon" id="captchaImgHolder" style="padding: 0; width:120px;"><img id="captchaImg" style="width:100%;" src="<?php echo site_url('authenticate/get_captcha_image');?>"/></span>
									<input type="text" class="form-control" style="width:110px;height:43px;" id="captcha" name="captcha" placeholder="Type Here" required="required">

								</div>
								<small>(Case-Insensitive)</small>
								<a id="refreshCaptcha" class="btn pull-right" style="cursor:pointer;top: -36px;right: 105px;position: relative; color:blue;"><i class="fa fa-refresh"></i></a>
							</div>
							<div class="checkbox">
								<!-- <label>
									<input type="checkbox" name="remember"> Remember Me
								</label> -->
									<a class="text-primary" href="<?php echo site_url('forget-password');?>"> Forget Password</a>
								<label>
								</label>
							</div>
							
							<input type="hidden" id="hash" value=""/>
							<hr/>
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
		data:{username:$("#exampleInputUsername1").val(),password:md5($("#exampleInputPassword1").val()), captcha:$("#captcha").val()},
		success:function(e)
		{
			
			
			if(e == 400){
				window.location = "<?php echo site_url('access-denied'); ?>";
				return;
			}

			if(e == 1){

				window.location.reload();
			}else if(e==3){
				window.location = BASE_URL+"login?q=3";
			}else{
				$("#login-information").removeAttr("style");
				$("#loading-image").css({'display': 'none'});
				$("#login-form-compo").removeAttr("style");
			}
		}
	});

	return false;
});

$(document).on("click","a#refreshCaptcha",function(){
	$.ajax({
            url: SITE_URL+'/refresh-captcha',
            method:'post',
            cache: false,
            success:function(e){
                var url = $("#captchaImg").prop('src');
                $("#captchaImg").remove();
                var imgObj = '<img id="captchaImg" style="width:100%" src="'+url+'/'+new Date().getTime()+'"/>';
                $("#captchaImgHolder").append(imgObj);
            }
	});
});
</script>
<?php exit; ?>