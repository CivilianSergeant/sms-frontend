<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="UserRole" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{warning_messages}}
	</div>

	<div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{success_messages}}
	</div>
	<div class="panel panel-default" ng-if="showFrm">
		<div class="row" >
			<!-- <div class="col-md-12">
                <div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
            </div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Add User Role
							<a ng-click="hideForm();" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i> Close </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<form name="roleFrm"  ng-submit="saveRole()" class="form-horizontal">
						<div class="col-md-6">

							<div class="form-group">
								<label class="control-label col-md-3">Role Name</label>
								<div class="col-md-6">
									<input type="text" class="form-control" ng-model="formData.role_name" placeholder="Enter Role Name" required="required">
								</div>
							</div>


							<!--<div class="form-group">
								<label class="control-label col-md-3">User Type</label>
								<div class="col-md-6">

									<select class="form-control" ng-model="formData.role_type" required>
										<option value="">---Select Role Type---</option>
										<option ng-repeat="rtype in role_types" value="{{rtype}}">{{rtype}}</option>

									</select>
								</div>
							</div>-->

							<div class="form-group">
								<div class="col-md-3 col-md-offset-3">
									<input type="submit" ng-disabled="roleFrm.$invalid" class="btn btn-success" value="Save"/>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default" ng-if="!showFrm">
		<div class="row" >
			<!-- <div class="col-md-12">
                <div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
            </div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">User Roles
							<a ng-if="permissions.create_permission == '1'" ng-click="showForm();" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add Role </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div kendo-grid options="mainGridOptions"></div>
				</div>
			</div>
		</div>
	</div>
</div>









