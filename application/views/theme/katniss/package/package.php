<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="package" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>

	<div class="panel panel-default" ng-show="showFrm">
		<div class="row" >
			<!-- <div class="col-md-12">
				<div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
			</div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Add New Package
							<a ng-click="hideForm(); removeAlert()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<form name="package" id="package" method="POST" ng-submit="savePackage()">
					<div class="col-md-5">
						<div style="padding:0;" class="col-md-12">
							<div class="col-md-10">
								<div class="form-group">
									<label for="exampleInputEmail1">Package Name <span style="color:red">*</span></label>						
									
										<input type="text" class="form-control" ng-model="package_name" placeholder="Enter Package Name" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label for="exampleInputPassword1">Package Prices <span style="color:red">*</span></label>						
								<div class="input-group margin-bottom-sm">
									
									<input type="number" class="form-control" ng-model="package_price" placeholder="Prices" required="required">
								</div>
							</div>
						</div>
						
						
						<!-- <div class="package_duration"> -->
						<div id="packageduration" class="col-md-4">
							<div  class="form-group">
								<label for="exampleInputEmail1">Package Duration <span style="color:red">*</span></label>						
								<div class="input-group margin-bottom-sm">
									
									<input type="number" class="form-control" ng-model="package_duration" placeholder="Duration" required="required">
								</div>
							</div>
						</div>
						<div id="duration">
							<div class="col-md-2">
								<p>Day(s)</p>
							</div>
						</div>
						<div style="clear:both;" class="col-md-11">
							<div class="form-group">
								<div class="checkbox">
									<label>					
										<input type="checkbox"  ng-model="is_active" ng-checked="is_active" ng-true-value="1" ng-false-value="0"/>
										<strong>Is Active</strong>
									</label>	
								</div>
							</div>
						</div>
					</div>
						
					<div class='col-md-6'>
						<!-- <div class='col-md-7'>
							<div class="form-group">
								<label for="exampleInputEmail1">Program list <span style="color:red">*</span></label>
								<fieldset class="scheduler-border">
							    	<div class="control-group">
									   <div class='col-md-9' style="padding: 0px;">
											<select class="form-control"  name="selectfrom" id="select-from" multiple size="15">
											  <?php
												if (!empty($programs)) {
													foreach ($programs as $program) {
														echo '<option value="'.$program->id.'">'.$program->program_name.'</option>';
													}
												}
											  ?>
											</select>
										</div>
										<div class='col-md-2'>
											 <p><a href="JavaScript:void(0);" id="btn-add">
											 <button type="button" class="btn btn-default" > <i class="fa fa-arrow-right"></i> <i class="fa fa-arrow-down"></i></button></a>
											 </p><br/>
											<p><a href="JavaScript:void(0);" id="btn-remove">
											<button type="button" class="btn btn-default" > <i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-up"></i></button></a></p>
		   								</div>
							    			
							    	</div>
								</fieldset>
							</div>
						</div>
						<div class='col-md-4' style="padding: 0px;">
							<div id="add-program" class="form-group">
								<label for="exampleInputEmail1">Add Program list</label>
								<fieldset class="scheduler-border">
									<div class="control-group">
										<select class="form-control" name="programs[]" id="select-to" multiple size="15" required="required">
										</select>
									</div>
								</fieldset>
							</div>
						</div> -->
						<div class="col-md-12">
                            <div class="col-md-4">
                                <label class="control-label">Program List</label> 
                                <select id="select-from" ng-model="selected_item"  style="width:200px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                            <div class="col-md-1" style="margin-top:25px;margin-right:13px;margin-left:53px;">
                                <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                                <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label class="control-label">Assigned Program List</label> 
                                <select id="select-from" ng-model="included_item" style="width:200px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in assigned_programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                            <div class="col-md-12 col-md-offset-6" style="padding-left:0px;">
                            	Total : {{assigned_programs.length}} [max: 190]
                        	</div>
                        </div>
					</div>
					<div class="col-md-12">
						<div class="col-md-10">
							<div class="form-group">
								<button type="submit" ng-disabled="package.$invalid" class="btn btn-default"> Submit </button> <button type="reset" id="buttoncancel" class="btn btn-danger" >Reset</button>
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
					
					<h4 class="widgettitle">Package List 
						<a ng-show="permissions.create_permission=='1'" ng-click="showForm(); removeAlert()" id="buttoncancel" class="btn btn-success btn-sm pull-right">
							<i class="fa fa-plus-circle"></i> Add Package
						</a>
					</h4>
				</div>
				<span class="clearfix"/>
				<hr/>
			</div>	
			
			
			
			<div class="panel-body">
				<div class="col-md-12" ng-if="!delete_item">
					<div kendo-grid id="grid" options="mainGridOptions"></div>
				</div>
				<div class="col-md-12 text-center" ng-if="delete_item">
					<form>
						<p><strong>Are you sure to delete this package</strong></p>
						<p>
							<input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
							<input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
						</p>
					</form>
				</div>
			</div>
		</div>	
	</div>
</div>

<script type="text/javascript">
	// For demo to fit into DataTables site builder...
	$('#example')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');
</script>

<script type="text/javascript">
$(document).ready(function() {
 
    
 
});
</script>
