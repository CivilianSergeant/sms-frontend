	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	?>
	
	<!-- <link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.common-material.min.css" />
	<link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.material.min.css" /> -->

	<div id="container" ng-controller="CreateSTBProvider" ng-cloak>
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

						<h4 class="widgettitle"> Add New STB Provider
							<a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
						</h4>

						<span class="clearfix"></span>
					</div>
					<hr/>
				</div>
				<!-- Form Left Part -->
				<div class="panel-body">
					<form  ng-submit="saveProviders()" method="POST" name="stbProviderAdd" class="form-horizontal">
						<div class="col-md-8">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="stb_type"> Type <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="stb_type" ng-model="providers.stb_type" placeholder="Enter STB Type" required="required">
									</div>		
								</div>			
								<div class="form-group">
									<label class="col-sm-4 control-label" for="stb_supplier">STB Supplier <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="stb_supplier" ng-model="providers.stb_supplier" placeholder="Enter STB Supplier" required="required">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-sm-4 control-label" for="description">Description</label>						
									<div class="col-sm-5">
										<textarea class="form-control" id="description" ng-model="providers.description" placeholder="Enter Description"></textarea>																				
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="address1">Address Line 1 <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="address1" ng-model="providers.address1" placeholder="Enter Address Line 1" required="required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="address2">Address Line 2 </label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="address2" ng-model="providers.address2" placeholder="Enter Address Line 2">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="country">Country </label>						
									<div class="col-sm-5">
										<select class="form-control" id="country" ng-model="providers.country">
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
									<div class="col-sm-5">
										<input type="text" class="form-control" id="state" ng-model="providers.state" placeholder="Enter State">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="city"> City </label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="city" ng-model="providers.city" placeholder="Enter State">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="zip"> Zip Code</label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="zip" ng-model="providers.zip" placeholder="Enter Zip Code">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="email"> Email </label>						
									<div class="col-sm-5">
										<input type="email" class="form-control" id="email" ng-model="providers.email" placeholder="Enter Email">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="phone"> Phone <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="text" class="form-control"  maxlength="11"  ng-model="providers.phone" placeholder="Enter Phone" required="required">
									</div>
								</div>
								<div class="col-md-4 col-md-offset-4">
									<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="stbProviderAdd.$invalid" class="btn btn-success btnNext" value="Save Provider" />
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
								All STB Providers List
								<a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New STB Provider </a>
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


