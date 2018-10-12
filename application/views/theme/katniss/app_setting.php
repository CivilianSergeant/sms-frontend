<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container">
	<h3 class="widgettitle">User Registration</h3>
	<?php if ($this->session->flashdata('success')) { ?>
	<div class="alert alert-success"> <?php echo $this->session->flashdata('success') ?> </div>
	<?php } ?>
	<div class="row">
		<div class="col-md-6" id="reg_form">

			<form name="login" id="login" method="POST" action="<?php echo base_url('profile/get_user_data'); ?>">

				<div class="form-group">
					<label for="exampleInputEmail1">Username</label>						
					<div class="input-group margin-bottom-sm">
						<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
						<input type="text" class="form-control" id="exampleInputEmail1" name="username" value="" placeholder="Enter Username" required="required">
					</div>
				</div>

				<div class="form-group">
					<label for="exampleInputEmail1">Email</label>						
					<div class="input-group margin-bottom-sm">
						<span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
						<input type="email" class="form-control" id="exampleInputEmail1" name="email" value="" placeholder="Enter Your Email" required="required">
					</div>
				</div>

				<div class="form-group">
					<label for="exampleInputPassword1">Password</label>						
					<div class="input-group margin-bottom-sm">
						<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
						<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Enter Your Password" required="required">
					</div>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-default"> Submit </button>
				</div>

			</form> 
		</div>
	</div>
</div>
