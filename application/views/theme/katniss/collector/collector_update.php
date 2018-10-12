<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
	var collector_id = <?php echo $collector_id; ?>
</script>
<div id="container" ng-controller="CollectorEdit" ng-cloak>
	<?php if ($this->session->flashdata('success')) { ?>

	<div class="alert alert-success"> 
		<button class="close" aria-label="close" data-dismiss="alert">×</button>
		<p><?php echo $this->session->flashdata('success') ?></p>
	</div>

	<?php } ?>
	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{warning_messages}}
	</div>

	<div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{success_messages}}
	</div>

	<div class="panel panel-default">

		<div class="row">

			<div class="col-md-12">
				<div class="panel-heading">

					<h4 class="widgettitle"> Update Collector
						<a href="<?php echo site_url('collector/view/'.$collector_id);?>" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-search"></i> View</a>
						<a href="<?php echo site_url('collector'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
					</h4>

					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<!-- Form Left Part -->
			<div class="panel-body">
				<form  name="saveCollectorAdd" ng-submit="updateCollector()" class="form-horizontal">
					<div class="col-md-8">			
						<div class="form-group">
							<label class="col-sm-4 control-label" for="collector_name">Collector Name <span style="color:red">*</span></label>						
							<div class="col-sm-5">

								<input type="text" class="form-control" id="collector_name" ng-model="collector.name"  required="required">
							</div>
						</div>								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="present_address">Present Address <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<textarea class="form-control" id="present_address" ng-model="collector.present_address" required="required"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="parmanent_address">Permanent Address <span style="color:red">*</span></label>
							<div class="col-sm-5">
								<textarea class="form-control" id="parmanent_address" ng-model="collector.parmanent_address" required="required"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="phone1"> Phone 1 <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="phone1" ng-model="collector.phone1"  required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="phone2"> Phone 2 </label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="phone2" ng-model="collector.phone2" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="nid_number"> National ID <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="nid_number" ng-model="collector.nid_number"  required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="reference_name"> Reference Name </label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="reference_name" ng-model="collector.reference_name" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="reference_phone"> Reference Phone </label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="reference_phone" ng-model="collector.reference_phone" >
							</div>
						</div>
						<div class="col-md-4 col-md-offset-4">
							<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="saveCollectorAdd.$invalid" class="btn btn-success btnNext" value="Update Collector" />
						</div>				
					</div>
				</form> 
			</div>
		</div>
	</div>
</div>  


