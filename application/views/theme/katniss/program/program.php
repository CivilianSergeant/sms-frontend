	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	?>
	<script type="text/javascript">
		var unassigned_programs = "<?php echo $unassigned_programs; ?>";
	</script>
	<div id="container" ng-controller="program" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter" ng-cloak>
		
		<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
	        <button class="close" ng-click="closeAlert()">×</button>
	        {{warning_messages}}
	    </div>
		<div class="panel panel-default" ng-show="addFrom_ViewFromFlag">
			<div class="row">
				<div class="col-md-12">
					<div class="panel-heading">
						<div class="col-md-12">
							<h4 class="widgettitle">Add New Program</h4>
						</div>
						<span class="clearfix"></span>
					</div>
					<hr/>
				</div>
				<!-- Form Left Part -->
				<div class="panel-body">
					<form action="<?php echo site_url('program/save-program');?>" method="POST"  name= "addProgram" class="form-horizontal">
						<div class="col-md-12">
							<div class="col-md-10">

								
								<div class="form-group">
									<label class="col-sm-4 control-label" for="program_name">Program Name <span style="color:red">*</span></label>						
									<div class="col-sm-4">			
										<input type="text" class="form-control" maxlength="20" id="program_name" name="program_name" ng-model="program_name" placeholder="Enter Program Name" required>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label" for="LCN">LCN </label>						
									<div class="col-sm-4">
										
										<input type="text" class="form-control" id="LCN" name="LCN" ng-model="lcn" placeholder="Enter Program LCN" value="0">
										<span style="color:red" ng-show="lcnOver">Lcn cant over 3 digit</span>
									</div>					
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label" for="program_service_id">Service ID </label>						
									<div class="col-sm-4">			
										<input type="text" class="form-control" id="program_service_id" name="program_service_id" placeholder="Enter Service ID" value="0">
									</div>
								</div>

								<div ng-hide="advancedOptions">
									<div class="form-group">
										<label class="col-sm-4 control-label" for="advanced_option">{{advancedOptionsTitle}}</label>						
										<div class="col-sm-4">
											<input type="checkbox" ng-model="advancedOptionsConfirmed" ng-change="advancedOptionsChange()" name="advanced_option" id = "advanced_option"> 
										</div>
									</div>
								</div>
								<div ng-show="advancedOptions">
									
									<div class="form-group">
										<label class="col-sm-4 control-label" for="program_type">Program Type</label>						
										<div class="col-sm-4">
											
											<select name="program_type" id ="program_type" class="form-control"> 
												<option value="1">PPV</option>
												<option value="2" selected="selected">Pay Per Month</option>
											</select>
										</div>
									</div>		

									<div class="form-group">
										<label class="col-sm-4 control-label" for="teleview_level">Teleview Level</label>						
										<div class="col-sm-4">
											
											<select name="teleview_level" id = "teleview_level" class="form-control"> 
												<option value="1">1</option>
												<option value="1">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
											</select>
										</div>
									</div>	

									<div class="form-group">
										<label class="col-sm-4 control-label" for="program_status">Program Status</label>						
										<div class="col-sm-4">
											
											<select name="program_status"  id="program_status"class="form-control"> 
												<option value="1" selected="selected">Active</option>
												<option value="0">In Active</option>
											</select>
										</div>
									</div>			

									<div class="form-group">
										<label class="col-sm-4 control-label" for="network_id">Emergency Brodcast NetworkID</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="network_id" name="network_id" value = "0" placeholder="Enter Network ID" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="transport_stream_id">Emergency Brodcast TransportStreamID</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="transport_stream_id" value="0" name="transport_stream_id" placeholder="Transport Stream ID" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="service_id">Emergency Brodcast ServiceID</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="service_id" name="service_id" value = "0" placeholder="Enter Service ID" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="display_position">Fingerprint Display Position</label>						
										<div class="col-sm-4">
											
											<select class="form-control" id="display_position" name="display_position"  required="required">
												<option value="4" selected="selected">RANDOM</option>
												<option value="255">FINGER_POS_FIXED</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="position_x">Position X</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="position_x" value = "0" name="position_x" placeholder="Enter Position X" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="position_y">Position Y</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="position_y" value = "0" name="position_y" placeholder="Enter Position Y" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="font_type">Font Type</label>						
										<div class="col-sm-4">
											
											<select class="form-control" id="font_type" name="font_type" required="required">
												<option value="0" selected="selected">Arial</option>
												<option value="1">Microsoft Sans Serif</option>
												<option value="2">Microsoft Ya Hei</option>
												<option value="3">STSong</option>
												<option value="4">KaiTi</option>
											</select>
											
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="font_size">Font Size</label>						
										<div class="col-sm-4">
											
											<select class="form-control" id="font_size" name="font_size" required="required">
												<option value="8" selected="selected">8</option>
												<option value="12">12</option>
												<option value="16">16</option>
												<option value="18">18</option>
												<option value="20">20</option>
												<option value="22">22</option>
												<option value="24">24</option>
												<option value="26">26</option>
												<option value="28">28</option>
												<option value="36">36</option>
												<option value="48">48</option>
												<option value="72">72</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="font_color">Font Color</label>						
										<div class="col-sm-4">
											<select class="form-control" id="font_color" name="font_color" required="required">
												<option value="0">Tansparent</option>
												<option value="-16776961">Blue</option>
												<option value="255">Black</option>
												<option value="65535">Red</option>
												<option value="8388863">Green</option>
												<option value="-1">White</option>
												<option value="-65281">Cyan</option>
												<option value="16777215">Yellow</option>
												<option value="10878975">Orange</option>
												<option value="-2147450625">Purple</option>
												<option value="-2139062017" selected="selected">Gray</option>
											</select>
										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-4 control-label" for="background_color">Font Background Color</label>						
										<div class="col-sm-4">
											<select class="form-control" id="background_color" name="background_color" required="required">
												<option value="0">Tansparent</option>
												<option value="-16776961">Blue</option>
												<option value="255">Black</option>
												<option value="65535">Red</option>
												<option value="8388863">Green</option>
												<option value="-1" selected="selected">White</option>
												<option value="-65281">Cyan</option>
												<option value="16777215">Yellow</option>
												<option value="10878975">Orange</option>
												<option value="-2147450625">Purple</option>
												<option value="-2139062017">Gray</option>
											</select>
										</div>
									</div>

									
									<div class="form-group">
										<label class="col-sm-4 control-label" for="show_time">Show Duration(sec)</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="show_time" value = "0" name="show_time" placeholder="Enter Background Color" required="required">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="stop_time">Stop Duration(sec)</label>						
										<div class="col-sm-4">
											
											<input type="text" class="form-control" id="stop_time" value = "0" name="stop_time" placeholder="Enter Visible Level" required="required">
										</div>
									</div>



									<div class="form-group">
										<label class="col-sm-4 control-label" for="over_flag">Fingerprint Over</label>						
										<div class="col-sm-4">
											<input type="hidden" name="over_flag" value="0">
											<input type="checkbox" name="over_flag" value="1" checked="checked" id = "over_flag"> 
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-4 control-label" for="show_background_flag">Show Background Color</label>						
										<div class="col-sm-4">
											<input type="hidden" name="show_background_flag" value="0">
											<input type="checkbox" name="show_background_flag" value="1" checked="checked" id = "show_background_flag"> 
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label" for="show_stb_number_flag">Show STB or IC Number</label>						
										<div class="col-sm-4">
											<input type="hidden" name="show_stb_number_flag" value="0">
											<input type="checkbox" name="show_stb_number_flag" value="1" checked="checked" id = "show_stb_number_flag"> 
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-md-offset-4">
								<input type="submit" class="btn btn-success" ng-disabled="! addProgram.$valid" id="buttonsuccess" value="Submit"> &nbsp <button type="button" id="buttoncancel" class="btn btn-danger" ng-click="addFrom_ViewFromFlagtoggleState()">Cancel</button>
							</div>
						</div>
					</form> 
				</div>
			</div>
		</div>

		<div class="panel panel-default" ng-hide="importProgram">
			<div class="row">
				<div class="col-md-12">
					<div class="panel-heading">
						<div class="col-md-12">
							<h4 class="widgettitle">Import Program Template <a href="#" ng-click="uploadViewBack()" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a></h4>
						</div>
						<span class="clearfix"></span>
					</div>
					<hr/>			
				</div>
				<div class="col-md-12">
					<div class="panel-body">
						<!-- <div id ="grid" kendo-grid k-options="mainGridOption" k-rebind="mainGridOption"/> -->
						<style type="text/css">
							.form-control{height: 34px !important;}
							.progress {height: 40px !important;}
							.progress-bar {font-size: 18px; line-height: 38px; color: springgreen;}
							.input-file { position: relative;} /* Remove margin, it is just for stackoverflow viewing */
							.input-file .input-group-addon { border: 0px; padding: 0px; }
							.input-file .input-group-addon .btn { border-radius: 0 4px 4px 0 }
							.input-file .input-group-addon input { cursor: pointer; position:absolute; width: 72px; z-index:2;top:0;right:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0; background-color:transparent; color:transparent; }
						</style>
						<div filters="queueLimit, customFilter" uploader="uploader" nv-file-drop="" class="col-md-6 col-md-offset-3">
						    <div style="margin-bottom: 40px">
						        <div ng-hide="messageState">
						        	<div class="input-group input-file">
									  <div class="form-control">
									    <!-- <a href="/path/to/your/current_file_name.pdf" target="_blank">current_file_name.pdf</a> -->
									  </div>
									  <span class="input-group-addon">
									    <a href="javascript:;" class="btn btn-primary">
									      Browse
									      <input type="file" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="uploader" nv-file-select="">
									    </a>
									  </span>
									</div>
						            <div>
						                <div style="margin-top:5px" class="progress">
						                    <div ng-style="{ 'width': uploader.progress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="uploader.progress" class="ng-binding ng-hide">{{uploader.progress}} %</div></div>
						                </div>
						            </div>  
					                <button ng-disabled="!uploader.getNotUploadedItems().length" ng-click="uploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
						                <span class="glyphicon glyphicon-upload"></span> Upload
						            </button>
						            <button ng-disabled="!uploader.isUploading" ng-click="uploader.cancelAll()" class="btn btn-warning btn-s" type="button" disabled="disabled">
						                <span class="glyphicon glyphicon-ban-circle"></span> Cancel
						            </button>
							    </div>  
					        </div>
					    </div>
					</div>
				</div>
			</div>
		</div>


		<div class="panel panel-default" ng-hide="addFrom_ViewFromFlag" ng-show="importProgram">
			<div class="row" >
				<div class="col-md-12">
					<div class="panel-heading">
						<div class="col-md-12">
							<h4 class="widgettitle">All Program List
								<a ng-if="permissions.create_permission==1" href="#" ng-click="addFrom_ViewFromFlagtoggleState()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add Program</a></h4>
						</div>
						<span class="clearfix"></span>
					</div>
					<hr/>			
				</div>
				<div class="col-md-12" ng-show="!delete_item">
					<div class="panel-body">
						<!-- <div id ="grid" kendo-grid k-options="mainGridOption" k-rebind="mainGridOption"/> -->
						<kendo-grid options="mainGridOptions" id="grid"></kendo-grid>
						<br/>
						<strong class="text-danger" ng-show="unassigned_programs!=0">Un-Assigned Number of Programs [{{unassigned_programs}}].</strong>
					</div>

				</div>
				<div class="col-md-12 text-center" ng-show="delete_item">
					<form>
						<p><strong>Are you sure to delete this program</strong></p>
						<p>
							<input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
							<input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
						</p>
					</form>
				</div>
			</div>
			
		</div>
	</div>  

<script type="text/javascript">

</script>

