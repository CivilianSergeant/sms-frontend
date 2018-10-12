	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	?>
	
	
	<!-- <link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.common-bootstrap.min.css" />
	<link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.bootstrap.min.css" /> -->
	<div id="container" ng-controller="CreateICProvider" ng-cloak>
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

						<h4 class="widgettitle"> Add New Provider
							<a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
						</h4>

						<span class="clearfix"></span>
					</div>
					<hr/>
				</div>

				<!-- Form Left Part -->
				<div class="panel-body">
					<form action="<?php echo site_url('create-icsmartcard-proprovider'); ?>" method="POST" name="icProviderAdd" class="form-horizontal">
						<div class="col-md-12">
							<div class="col-md-10">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="ic_type"> Type <span style="color:red">*</span></label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="ic_type" ng-model="providers.ic_type" placeholder="Enter IC Type" required>
									</div>
								</div>			
								<div class="form-group">
									<label class="col-sm-4 control-label" for="stb_supplier">IC or Smartcard Supplier  <span style="color:red">*</span></label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="ic_supplier" ng-model="providers.ic_supplier" placeholder="Enter STB Supplier" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="description">Description </label>						
									<div class="col-sm-4">
										<textarea class="form-control" id="description" ng-model="providers.description" placeholder="Enter Description"></textarea>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="address1">Address Line 1 <span style="color:red">*</span></label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="address1" ng-model="providers.address1" placeholder="Enter Address Line 1" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="address2">Address Line 2 </label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="address2" ng-model="providers.address2" placeholder="Enter Address Line 2">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="country">Country </label>						
									<div class="col-sm-4">
										<select class="form-control" id="country" ng-model="providers.country">
											<option value="">---Select Country---</option>
											<option value="">---Select Country---</option>
											<?php if ($countries) { ?>
											<?php foreach ($countries as $country) { ?> 
											<option value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
											<?php } ?>
											<?php } ?>
										</select>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="state"> State </label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="state" ng-model="providers.state" placeholder="Enter State">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="city"> City </label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="city" ng-model="providers.city" placeholder="Enter State">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="zip"> Zip Code </label>						
									<div class="col-sm-4">
										<input type="text" class="form-control" id="zip" ng-model="providers.zip" placeholder="Enter Zip Code">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="email"> Email </label>						
									<div class="col-sm-4">
										<input type="email" class="form-control" id="email" ng-model="providers.email" placeholder="Enter Email">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="phone"> Phone <span style="color:red">*</span></label>						
									<div class="col-sm-4">
										<input type="text" maxlength="11" class="form-control" id="phone" ng-model="providers.phone" placeholder="Enter Phone" required>
									</div>
								</div>
								<div class="col-md-4 col-md-offset-4">									
									<a id="btnNext" ng-click="saveProviders()" class="btn btn-success btnNext" ng-disabled="!icProviderAdd.$valid" >Save Provider</a>
								</div>
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
								All IC or Smart Card Providers List
								<a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New IC or Smartcard Provider </a>
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

