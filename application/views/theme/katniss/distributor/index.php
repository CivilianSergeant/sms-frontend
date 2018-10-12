<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container" ng-controller="Distributor" ng-cloak>
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

<div class="panel panel-default" ng-show="showFrm">			

	<div class="row">

		<div class="col-md-12">
			<div class="panel-heading">

				<h4 class="widgettitle"> Add New Distributor
					<a ng-if="permissions.create_permission == '1'" ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
				</h4>

				<span class="clearfix"></span>
			</div>
			<hr/>
		</div>
		<!-- Form Left Part -->
		<div class="panel-body">
			<form  ng-submit="saveDistributor()" method="POST" name="saveDistributorAdd" class="form-horizontal">
				<div class="col-md-8">			
						<div class="form-group">
							<label class="col-sm-4 control-label" for="distributor_name">Distributor Name <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="distributor_name" ng-model="distributor.distributor_name" placeholder="Enter Collector Name" required="required">
							</div>
						</div>								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="present_address">Present Address <span style="color:red">*</span></label>
							<div class="col-sm-5">
								<textarea class="form-control" id="present_address" ng-model="distributor.present_address" placeholder="Enter Present Address" required="required"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="parmanent_address">Permanent Address <span style="color:red">*</span></label>
							<div class="col-sm-5">
								<textarea class="form-control" id="permanent_address" ng-model="distributor.parmanent_address" placeholder="Enter Parmanent Address" required="required"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="phone1"> Phone 1 <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="phone1" ng-model="distributor.phone1" placeholder="Enter Primary Phone" required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="phone2"> Phone 2 </label>						
							<div class="col-sm-5">
								<input type="text" maxlength="11" class="form-control" id="phone2" ng-model="distributor.phone2" placeholder="Enter Secondary Phone">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="nid_number"> National ID <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="nid_number" ng-model="distributor.nid_number" placeholder="Enter National ID Number" required="required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="reference_name"> Reference Name </label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="reference_name" ng-model="distributor.reference_name" placeholder="Enter Reference Name">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="reference_phone"> Reference Phone </label>						
							<div class="col-sm-5">
								<input type="number" maxlength="11" class="form-control" id="reference_phone" ng-model="distributor.reference_phone" placeholder="Enter Reference Phone">
							</div>
						</div>
						<div class="col-md-4 col-md-offset-4">
							<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="saveDistributorAdd.$invalid" class="btn btn-success btnNext" value="Save Distributor" />
						</div>
					
				</div>
			</form> 
		</div>
	</div>
</div>

<div class="panel panel-default" ng-if="!showFrm">
	<div class="row">
		<div class="col-md-12">
			<div class="panel-heading">
				<div class="col-md-12">
					<h4 class="widgettitle">
						All Distributor List
						<a ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New Distributor </a>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>			
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<kendo-grid options="mainGridOptions">
            		</kendo-grid>	
				</div>
			</div>
		</div>
	</div>
</div>  


