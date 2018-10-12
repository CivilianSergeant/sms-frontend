<!DOCTYPE html>
 <html class="no-js"> 
<head>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>License Missing</title>
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
			<div class="login-wrapper" style="width: 460px; height: auto">
			<h3>License Missing</h3>
		
			<div class="login-wrapper-content">
				<div class="container-fluid">
					<div id ="loading-image" align="center" style="display: none">
						<img src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>img/loading.gif">
					</div>
					<div id="login-form-compo">
						
							<div id="login-information">
								<strong class="text-danger">
									Sorry! System not find any valid License. Please Contact with System Administrator
									<?php 
									if(!empty($organization->administrator1))
									{
										echo 'Name : ' . $organization->administrator1;
									}
									if(!empty($organization->administrator2))
									{
										echo ' Or ' . $organization->administrator2; 
									}
									
									if($organization->is_show == 1 AND !empty($organization->phone1))
									 { 
									 	echo ', Phone : ' . $organization->phone1; 
									 } 

									 if($organization->is_show == 1 AND !empty($organization->phone2))
									 { 
									 	echo ' Or ' . $organization->phone2; 
									 }
									?>
									<br/><br/>
									<a href="<?php echo site_url('login'); ?>" class="btn btn-primary">Try Login</a>
								</strong>
							</div>
							
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

