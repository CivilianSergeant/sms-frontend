<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="CreateRegion" ng-cloak>
	<?php 
	$error_messages = $this->session->flashdata('error_messages');
	if (!empty($error_messages)) { ?>
	<div class="alert alert-danger">
		<button class="close" data-dismiss="alert" aria-label="close">&times;</button>
		<p><?php echo $error_messages; ?></p>
	</div>
	<?php } ?>
	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{warning_messages}}
	</div>
	<div class="panel panel-default business_region">
		<div class="row" >
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Business Region</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<!-- <div class="col-md-12" ng-click="hideForm()">
						<form ng-submit="addItem()">
							<div class="form-group">
								<label>New Location</label>
								<input type="text" ng-model="form.name"/>
								<input type="submit" value="Save"/>
							</div>
						</form>
					</div> -->
					
						<div class="col-md-12">
							<a class="btn btn-default btn-sm" ng-click="refresh()"><i class="fa fa-refresh"></i> Refresh</a>
							<br/><br/>
							<small>(To Cancel Create/Edit or refresh item Click 'Refresh' Button)</small>
						</div>

					
					<div class="col-md-6">
						
						
						<div class="locations" >
							<strong>All Locations</strong>
							<a ng-if="permissions.create_permission=='1'" class="btn btn-xs" ng-click="showRootFrm()" style="margin-left:4px;"> <i class="fa fa-plus"></i></a>
							<form ng-submit="addItem()" ng-if="rootFrm" style="margin-left:15px;">
									<input type="text" ng-model="form.name"/>
							</form>
							<ul class="tree">

								<li ng-repeat="L1 in items" ng-init="L1Index=$index">
									<div ng-if="!L1.flag">
										<a ng-click="showItem(L1.id)" ng-dblclick="showUpdateForm(L1.id)"><span style="color:green; margin-right:10px;">Level-1:</span> {{L1.name}}</a>
										<a ng-if="permissions.create_permission=='1'" class="btn btn-xs" ng-click="showChildForm(L1.id)"><i class="fa fa-plus"></i></a>
									</div>
									<form ng-show="L1.flag" style="margin-left:7px;" ng-submit="updateItem(L1.id)">
										<input autofocus="true" type="text"  ng-model="L1.name"/>
									</form>
									<form id="L1Form" style="margin-left:7px;" ng-submit="addChildItem(L1.id)" ng-if="L1.create_form">
										<input autofocus="true" type="text"  ng-model="L1.childItemName"/>
									</form>
									<ul>
										<li ng-repeat="L2 in L1.childs" ng-init="L2Index=$index">
											<div ng-if="!L2.flag">
												<a ng-click="showItem(L1.id,L2.id)" ng-dblclick="showUpdateForm(L1.id,L2.id)"><span style="color:green; margin-right:10px;">Level-2:</span> {{L2.name}}</a>
												<a ng-if="permissions.create_permission=='1'" class="btn btn-xs" ng-click="showChildForm(L1.id,L2.id)"><i class="fa fa-plus"></i></a>
											</div>
											<form ng-show="L2.flag" style="margin-left:7px;" ng-submit="updateItem(L1.id,L2.id)">
												<input autofocus="true" type="text"  ng-model="L2.name"/>
											</form>
											<form id="L2Form" style="margin-left:7px;" ng-submit="addChildItem(L1.id,L2.id)" ng-if="L2.create_form">
												<input autofocus="true" type="text"    ng-model="L2.childItemName" />
											</form>
											<ul>
												<li ng-repeat="L3 in L2.childs" ng-init="L3Index=$index">
													<div ng-if="!L3.flag">
														<a ng-click="showItem(L1.id,L2.id,L3.id)" ng-dblclick="showUpdateForm(L1.id,L2.id,L3.id)"><span style="color:green; margin-right:10px;">Level-3:</span> {{L3.name}}</a>
														<a ng-if="permissions.create_permission=='1'" class="btn btn-xs" ng-click="showChildForm(L1.id,L2.id,L3.id)"><i class="fa fa-plus"></i></a>
													</div>
													<form ng-show="L3.flag" style="margin-left:7px;" ng-submit="updateItem(L1.id,L2.id,L3.id)">
														<input autofocus="true" type="text"  ng-model="L3.name"/>
													</form>
													<form id="L3Form" style="margin-left:7px;" ng-submit="addChildItem(L1.id,L2.id,L3.id)" ng-if="L3.create_form">
														<input autofocus type="text" autofocus ng-model="L3.childItemName" />
													</form>
													<ul>
														<li ng-repeat="L4 in L3.childs" ng-init="L4Index=$index">
															<div ng-if="!L4.flag">
																<a ng-click="showItem(L1.id,L2.id,L3.id,L4.id)" ng-dblclick="showUpdateForm(L1.id,L2.id,L3.id,L4.id)"><span style="color:green; margin-right:10px;">Level-4:</span> {{L4.name}}</a>
															</div>
															<form ng-show="L4.flag" style="margin-left:7px;" ng-submit="updateItem(L1.id,L2.id,L3.id,L4.id)">
																<input autofocus="true" type="text"  ng-model="L4.name"/>
															</form>
														</li>
													</ul>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								
							</ul>
						</div>
					</div>
					
					<div class="col-md-6" >
						<div class="row">
							<label class="col-md-3 text-right">LEVEL: </label>
							<span>{{item.level}}</span>
						</div>
						<div class="row">
							<label class="col-md-3 text-right">Region ID: </label>
							<span>{{item.hex}}</span>
						</div>
						<div class="row">
							<label class="col-md-3 text-right">ID: </label>
							<span>{{item.id}}</span>
						</div>
						<div class="row">
							<label class="col-md-3 text-right">Region Name: </label>
							<span>{{item.name}}</span>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>