<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="AssignSTB" ng-cloak>
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
					<div class="col-md-12">
						<h4 class="widgettitle">Assign STB</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<!-- <form ng-submit="searchSTB()" method="POST"> -->
					<div class="col-md-12">
						<div class="col-md-3">
							<div class="form-group">
								<label  for="exampleInputPassword1">LCO</label>						
								<div class="margin-bottom-sm">
									<select kendo-combo-box
									k-placeholder="'Select LCO'"
									k-data-text-field="'lco_name'"
									k-data-value-field="'user_id'"

									k-data-source="lco_profiles"
									style="width: 100%" ng-model="lco_user_id" >
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="type">Type</label>					
							<div class="margin-bottom-sm">
								<select kendo-combo-box
								k-placeholder="'Select Type'"
								k-data-text-field="'stb_type'"
								k-data-value-field="'id'"

								k-data-source="stb_types"
								style="width: 100%" ng-model="stb_type_id" >
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="stb_number">Search</label>						
						<div class="margin-bottom-sm">
							<input type="text" class="form-control k-widget k-input" ng-model="stb_number" id="stb_number" value="" placeholder="Enter Number to Search">
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label for="username"></label>						
						<div class="margin-bottom-sm">
							<button class="btn btn-default" ng-click="searchSTB()"> Search </button>
						</div>
					</div>
				</div>
			</div>
		<!-- </form> -->
		<form ng-submit="saveLcoStb()">
			<div class="col-md-12">
				<div class="col-md-12" ng-show="loader">
					<style type="text/css">

					</style>
					<kendo-grid options="mainGridOptions" id="grid">
				</kendo-grid>         
			</div>
			<div class="col-md-12 text-center" ng-if="!loader">
				<h3>Loading</h3>
				<img src="<?php echo base_url('public/theme/katniss/img/loading_32.GIF');?>"/>
			</div>

		</div>
		<div class="col-md-12" ng-if="permissions.create_permission == '1'">
			<div class="col-md-3">
				<div class="form-group">
					<button type="submit" ng-disabled="isDisabled()" class="btn btn-success">Save STB</button>
				</div>
			</div>
		</div>
	</form>
</div>

</div>
</div>
</div>
