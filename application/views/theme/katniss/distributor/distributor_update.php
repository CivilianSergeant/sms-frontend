<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container">
	<?php if ($this->session->flashdata('success')) { ?>

	<div class="alert alert-success"> 
		<button class="close" aria-label="close" data-dismiss="alert">×</button>
		<p><?php echo $this->session->flashdata('success') ?></p>
	</div>

	<?php } ?>


	<div class="panel panel-default" >

		<div class="row">

			<div class="col-md-12">
				<div class="panel-heading">

					<h4 class="widgettitle"> Update Distributor

						<a href="<?php echo site_url('scratch-card-distributor/distributor-view/' . $distributor->id); ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
						<a href="<?php echo site_url('scratch-card-distributor'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
					</h4>

					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<!-- Form Left Part -->
			<div class="panel-body">
				<form  action="<?php echo site_url('scratch-card-distributor/distributor-update'); ?>" method="POST" name="saveDistributorAdd" class="form-horizontal">
					<div class="col-md-8">			
						<div class="form-group">
							<label class="col-sm-4 control-label" for="collector_name">Distributor Name <span style="color:red">*</span></label>						
							<div class="col-sm-5">
							<input type="hidden" name="distributor_id" value="<?php echo $distributor->id; ?>">
								<input type="text" class="form-control" id="collector_name" name="distributor_name" value="<?php echo $distributor->distributor_name; ?>" required="required">
							</div>
						</div>								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="present_address">Present Address <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<textarea class="form-control" id="present_address" name="present_address" required="required"><?php echo $distributor->present_address; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="parmanent_address">Permanent Address <span style="color:red">*</span></label>
							<div class="col-sm-5">
								<textarea class="form-control" id="parmanent_address" name="permanent_address" required="required"><?php echo $distributor->parmanent_address; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="phone1"> Phone 1 <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="phone1" name="phone1" value="<?php echo $distributor->phone1; ?>" required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="phone2"> Phone 2 </label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="phone2" name="phone2" value="<?php echo $distributor->phone2; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="nid_number"> National ID <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="nid_number" name="nid_number" value="<?php echo $distributor->nid_number; ?>" required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="reference_name"> Reference Name </label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="reference_name" name="reference_name" value="<?php echo $distributor->reference_name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="reference_phone"> Reference Phone </label>						
							<div class="col-sm-5">
								<input type="number" maxlength="11" class="form-control" id="reference_phone" name="reference_phone" value="<?php echo $distributor->reference_phone; ?>">
							</div>
						</div>
						<div class="col-md-4 col-md-offset-4">
							<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="saveDistributorAdd.$invalid" class="btn btn-success" value="Update Distributor" />
						</div>				
					</div>
				</form> 
			</div>
		</div>
	</div>
</div>  


