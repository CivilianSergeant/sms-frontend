<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container" ng-controller="CreateStb" ng-cloak>

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
					<h4 class="widgettitle"> Set-Top Box 
						<a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
					</h4>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="col-md-12">
						<div class="panel-heading">
							<ul class="tab_nav nav nav-tabs">
								<li class="active"><a data-toggle="tab" class="tab_top" href="#home">Add New</a></li>
								<li><a data-toggle="tab" class="tab_top" href="#menu1">Export</a></li>						
								<li><a data-toggle="tab" class="tab_top" href="#menu2">Import</a></li>						
							</ul>
						</div>
					</div>
				</div>
				<div class="tab-content">
					<div id="home" class="tab-pane fade in active">					
						<div class="panel-body">
							<div class="col-md-12">
								<div class="col-md-3" style="padding-bottom: 20px">
									<h4 class="widgettitle">Add New Set-Top Box</h4>
								</div>
							</div>

							<form method="POST" name="stbAdd" class="form-horizontal">
								<div class="col-md-12">
									<div class="col-md-10">
										<div class="form-group" style="display: none">
											<label class="col-sm-4 control-label" for="internal_card_number"> Internal Card Number <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="text" class="form-control" id="internal_card_number" ng-model="stb.internal_card_number" placeholder="Enter IC Internal Number" required="required" readonly="readonly">
											</div>
										</div>			
										<div class="form-group">
											<label class="col-sm-4 control-label" for="external_card_number">External Card Number <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="text" maxlength="16" class="form-control" id="external_card_number" ng-model="stb.external_card_number" placeholder="Enter External Number" required="required">
											</div>
										</div>							
										<div class="form-group">
											<label class="col-sm-4 control-label" for="stb_card_provider">Set-Top Box Provider <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<select class="form-control" id="stb_card_provider" ng-model="stb.stb_card_provider" required="required">
													<option value="">---Select Provider---</option>

													<?php if ($stb_providers) { ?>
													<?php foreach ($stb_providers as $value) { ?> 
													<option value="<?php echo $value->id; ?>"><?php echo $value->stb_provider; ?></option>
													<?php } ?>
													<?php } ?>
												</select>										
											</div>
										</div>									
										<div class="form-group">
											<label class="col-sm-4 control-label" for="price"> Price <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="number" class="form-control" id="price" ng-model="stb.price" placeholder="Enter Price" required="required">
											</div>
										</div>
										<div class="col-md-4 col-md-offset-4">									
											<a id="btnNext" ng-click="saveStb()" ng-disabled="!stbAdd.$valid" class="btn btn-success btnNext"> Save Set-Top Box </a>
										</div>
									</div>
								</div>
							</form> 
						</div>
					</div>
					<div id="menu1" class="tab-pane fade">
						<div class="panel-body">
							<div class="col-md-12">
								<div class="col-md-3" style="padding-bottom: 20px">
									<h4 class="widgettitle">Export Tempate</h4>
								</div>
							</div>
							<form action="<?php echo site_url('set-top-box/export-stb'); ?>" method="post" class="form-horizontal">
								<div class="col-md-12">
									<div class="col-md-10">			
										<!-- <div class="form-group">
											<label class="col-sm-4 control-label" for="external_card_number">External Card Number <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="number" maxlength="16" class="form-control" id="external_card_number" ng-model="stb.external_card_number" placeholder="Enter External Number" required="required">
											</div>
										</div> -->							
										<div class="form-group">
											<label class="col-sm-4 control-label" for="stb_card_provider">Set-Top Box Provider <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<select class="form-control" id="stb_card_provider" name="stb_card_provider" required="required">
													<option value="">---Select Provider---</option>

													<?php if ($stb_providers) { ?>
													<?php foreach ($stb_providers as $value) { ?> 
													<option value="<?php echo $value->id; ?>"><?php echo $value->stb_provider; ?></option>
													<?php } ?>
													<?php } ?>
												</select>										
											</div>
										</div>									
										<div class="form-group">
											<label class="col-sm-4 control-label" for="price"> Price <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" required="required">
											</div>
										</div>
										<div class="col-md-4 col-md-offset-4">									
											<button type="submit" class="btn btn-success">Export</button>  
										</div>
									</div>
								</div>
							</form> 
						</div>
					</div>
					<!--Import -->
					<div id="menu2" class="tab-pane fade">
						<div class="panel-body">
							<div class="col-md-12">
								<div class="col-md-3" style="padding-bottom: 20px">
									<h4 class="widgettitle">Import Tempate</h4>
								</div>
							</div>	
						</div>
						<form method="post" class="form-horizontal">
							<div class="col-md-12">
								<div class="col-md-10">	

									<style type="text/css">
										.form-control{height: 34px !important;}
										.progress {height: 40px !important;}
										.progress-bar {font-size: 18px; line-height: 38px; color: lime-green;}
										.input-file { position: relative;} /* Remove margin, it is just for stackoverflow viewing */
										.input-file .input-group-addon { border: 0px; padding: 0px; }
										.input-file .input-group-addon .btn { border-radius: 0 4px 4px 0 }
										.input-file .input-group-addon input { cursor: pointer; position:absolute; width: 72px; z-index:2;top:0;right:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0; background-color:transparent; color:transparent; }
									</style>
									<div filters="queueLimit, customFilter" uploader="uploader" nv-file-drop="" class="col-md-6 col-md-offset-3">
									    <div style="margin-bottom: 40px">
									        <div ng-hide="messageState">
									        	<div class="input-group input-file">
												  <div id="fileValue" class="form-control">
												    <!-- <a href="/path/to/your/current_file_name.pdf" target="_blank">current_file_name.pdf</a> -->
												  </div>
												  <span class="input-group-addon">
												    <a href="javascript:;" class="btn btn-primary">
												      Browse
												      <input type="file" id="file" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="uploader" nv-file-select="">
												    </a>
												  </span>
												</div>
									            <div>
									                <div style="margin-top:5px" class="progress">
									                    <div ng-style="{ 'width': fileUploadProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadProgress" class="ng-binding ng-hide">{{fileUploadProgress}} %</div></div>
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
						</form>
					</div>
					<!--End Import-->
				</div>
			</div>

		</div>
	</div>

	<div class="panel panel-default" ng-if="!showFrm">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">
							All Set-Top Boxes List
							<a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New Set-Top Box </a>
						</div>
						<span class="clearfix"></span>
					</div>
					<hr/>			
				</div>
				<div class="panel-body">
					<div class="col-md-12">
						<kendo-grid options="mainGridOptions" id="stp-grid">
					</kendo-grid>
				</div>
			</div>
		</div>
	</div>
</div>  


