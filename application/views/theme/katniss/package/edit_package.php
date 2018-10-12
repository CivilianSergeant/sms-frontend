<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="editPackage" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        <span ng-bind-html="warning_messages"></span>
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
					<h4 class="widgettitle">Edit Package</h4>

					<a href="<?php echo site_url('package/view/' . $package->get_attribute('token')); ?>" class="btn btn-success btn-sm pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
					<a href="<?php echo site_url('package'); ?>" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-circle-left"></i> Back</a>
				</div>
				<span class="clearfix"></span>
			</div>
			<hr/>
		</div>
		<div class="panel-body">
		<form name="package" id="package" method="POST" ng-submit="savePackage()">
			<div class="col-md-5">
				<div class="col-md-11">
					<div class="form-group">
						<label for="exampleInputEmail1">Package Name <span style="color:red">*</span></label>						
							
							<input type="text" class="form-control" id="exampleInputEmail1" ng-model="package_name" placeholder="Enter Package Name"  required="required">
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label for="exampleInputPassword1">Package Prices <span style="color:red">*</span></label>						
						<div class="input-group margin-bottom-sm">
							
							<input type="number"  maxlength="5" class="form-control" ng-model="package_price" placeholder="Enter Prices" required="required">
							
						</div>
					</div>
				</div>
				<div class="package_duration">
					<div id="packageduration" class="col-md-4">
						<div class="form-group">
							<label for="exampleInputEmail1">Package Duration <span style="color:red">*</span></label>						
							<div class="input-group margin-bottom-sm">
								
								<input type="number" class="form-control" min="1" ng-model="package_duration" placeholder="Enter Duration" required="required">
							</div>
						</div>
					</div>
					<label style="padding:0px;margin-top:27px;" for="exampleInputEmail1"> Day(s)</label>
				</div>
				<div style="clear:both;"  class="col-md-11">
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" ng-model="is_active" ng-checked="is_active" ng-true-value="1" ng-false-value="0"/>
								<strong>Is Active</strong>  
							</label>						
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<!--<div class="col-md-7">
					 <div class="form-group">
						<label for="exampleInputEmail1">Program list <span style="color:red">*</span></label>
						<fieldset class="scheduler-border">
					    	<div class="control-group">
							   <div class='col-md-9' style="padding: 0px;">
									<select class="form-control" name="selectfrom" id="select-from" multiple size="15">
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
								<select class="form-control" name="programs[]" id="select-to" multiple size="15">
								     	<?php
									      	if (!empty($package_programs)) {
									      		foreach ($package_programs as $package_program) {
									      			echo '<option selected="selected" value="'.$package_program->id.'">'.$package_program->program_name.'</option>';
									      		}
									      	}
								      	?>
						    	</select>
							</div>
						</fieldset>
					</div>
				</div> -->
				<div class="col-md-12">
	                <div class="col-md-4">
	                    <label class="control-label">Program List</label> 
	                    <select id="select-from" ng-model="selected_item"  style="width:200px;min-height:100px;" multiple="multiple" size="15">
	                        <option ng-repeat="p in programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
	                    </select>
	                </div>
	                <div class="col-md-1" style="margin-top:25px;margin-right:13px;margin-left:53px;">
	                    <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
	                    <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
	                </div>
	                <div class="col-md-5">
	                    <label class="control-label">Assigned Program List</label> 
	                    <select id="select-from" ng-model="included_item" style="width:200px;min-height:100px;" multiple="multiple" size="15">
	                        <option ng-repeat="p in assigned_programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
	                    </select>
	                </div>
	            </div>
			</div>
			<div class="col-md-12">
				<div class="col-md-10">
					<div class="form-group">
						<button type="submit" id="buttonsuccess" class="btn btn-success" ng-disabled="package.$invalid">Update</button>
						<a href="<?php echo site_url('package'); ?>" class="btn btn-default"> Back </a>
					</div>
				</div>
			</div>
			</form>
			</div> 
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
 
    $('#btn-add').click(function(){
        $('#select-from option:selected').each( function() {
                $('#select-to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            $(this).remove();
            $("#select-to").children().attr('selected','selected');
        });
    });
    $('#btn-remove').click(function(){
        $('#select-to option:selected').each( function() {
            $('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
            $(this).remove();
            $("#select-to").children().attr('selected','selected');
        });
    });
 
});
</script>