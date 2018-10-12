<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container" ng-controller="module" ng-cloak>
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

				<h4 class="widgettitle"> Add New Module
					<a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
				</h4>

				<span class="clearfix"></span>
			</div>
			<hr/>
		</div>
		<!-- Form Left Part -->
		<div class="panel-body">
			<form  ng-submit="saveModule()" method="POST" name="moduleAddFrm" class="form-horizontal">
				<div class="col-md-8">			
						<div class="form-group">
							<label class="col-sm-4 control-label" for="collector_name">Module Name <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="collector_name" ng-model="module.module_name" placeholder="Enter Module Name" required="required"/>
							</div>
						</div>								
						<div class="form-group">
							<label class="col-sm-4 control-label" for="present_address">Module Route <span style="color:red">*</span></label>						
							<div class="col-sm-5">
								<input type="text" class="form-control" id="module_name" ng-model="module.route" placeholder="Enter Module Route" required="required"/>																			
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="module_description">Module Description</label>						
							<div class="col-sm-6">
								<textarea class="form-control" id="module_description" ng-model="module.module_description" placeholder="Enter Parmanent Address"></textarea>																				
							</div>
						</div>
						<div class="col-md-4 col-md-offset-4">
							<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="moduleAddFrm.$invalid" class="btn btn-success btnNext" value="Save Module" />
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
						All Modules
						<a ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New Collector </a>
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


