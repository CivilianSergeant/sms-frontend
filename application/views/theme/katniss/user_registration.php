<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container">
	<h3 class="widgettitle">User Registration</h3>
	<?php if ($this->session->flashdata('success')) { ?>
	<div class="alert alert-success"> <?php echo $this->session->flashdata('success') ?> </div>
	<?php } ?>
	<div class="row">
		<div class="col-md-6" id="reg_form" style="display: none;">

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
					<label for="exampleInputPassword1">User Type</label>						
					<div class="input-group margin-bottom-sm">
						<span class="input-group-addon"><i class="fa fa-list"></i></span>
						<select class="form-control" name="user_type">
							<option value="">---Select User Type---</option>
							<option value="MSO">MSO</option>
							<option value="LCO">LCO</option>
							<option value="STAFF">STAFF</option>
							<option value="SUBSCRIBER">SUBSCRIBER</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-default"> Submit </button>
				</div>

			</form> 
		</div>
		<div class="col-md-12" id="add_button" style="text-align: right; padding-bottom: 10px;">
			<button type="button" 
			onclick="document.getElementById('reg_form').style.display = 'block'; document.getElementById('add_button').style.display = 'none';" class="btn btn-default">
			Add New User </button>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">

			<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').DataTable();
			} );
			</script>

			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>User Type</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Tiger Nixon</td>
						<td>System Architect</td>
						<td>Edinburgh</td>
						<td>$320,800</td>
					</tr>
				</tbody>
			</table>
			

			<script type="text/javascript">
	// For demo to fit into DataTables site builder...
	$('#example')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');
	</script>

</div>
</div>  

</div>
