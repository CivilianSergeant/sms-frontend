<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
	.push-bottom-20{margin-bottom:20px;}
</style>
<script type="text/javascript">
	var uri = '<?php echo $uri; ?>';
</script>
<?php $packageType = preg_match('/(catchup|vod)/',$uri); ?>
<div id="container" ng-controller="IptvPackage" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>

	<div class="panel panel-default" ng-if="addFormFlag">
		<div class="row" >
			<!-- <div class="col-md-12">
				<div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
			</div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Add New Package
							<a ng-click="hideForm();" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<form name="package" id="package" method="POST" ng-submit="saveIptvPackage()">
					<div class="col-md-<?php echo ($packageType)? '10':'5';  ?>">
						<div style="padding:0;" class="col-md-12">
							<div class="col-md-10">
								<div class="form-group">
									<label for="exampleInputEmail1">Package Name <span style="color:red">*</span></label>
									<input type="text" class="form-control" ng-model="formData.package_name" placeholder="Enter Package Name" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-<?php echo ($packageType)? '2':'5';  ?>">
							<div class="form-group">
								<label for="exampleInputPassword1">Package Prices <span style="color:red">*</span></label>						
								<div class="input-group margin-bottom-sm">
									<input type="number" class="form-control" ng-model="formData.package_price" placeholder="Prices" required="required">
								</div>
							</div>
						</div>
						
						
						<!-- <div class="package_duration"> -->
						<div id="packageduration" class="col-md-<?php echo ($packageType)? '2':'4';  ?>">
							<div  class="form-group">
								<label for="exampleInputEmail1">Package Duration <span style="color:red">*</span></label>						
								<div class="input-group margin-bottom-sm">
									
									<input type="number" class="form-control" ng-model="formData.package_duration" placeholder="Duration" required="required">
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
										<input type="checkbox" ng-model="formData.is_commercial" ng-checked="formData.is_commercial" ng-true-value="'1'" ng-false-value="'0'"/>
										<strong>Is Commercial</strong>
									</label>
								</div>
							</div>
						</div>
						<div style="clear:both;" class="col-md-11">
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" ng-model="formData.is_active" ng-checked="formData.is_active" ng-true-value="'1'" ng-false-value="'0'"/>
										<strong>Is Active</strong>
									</label>
								</div>
							</div>
						</div>
						<div style="clear:both;" class="col-md-11">
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" ng-model="formData.not_deleteable" ng-checked="formData.not_deleteable" ng-true-value="'1'" ng-false-value="'0'"/>
										<strong>Is Base Package</strong>
									</label>
								</div>
							</div>
						</div>
						<div  class="col-md-12 push-bottom-20">
							<div  class="form-group">
								<!--<label for="exampleInputEmail1">Package Logo <small>(STB)</small></label>
								<div class="col-md-12" style="padding-left: 0px;">
									<input type="text" class="form-control" ng-model="formData.package_logo_stb"/>
								</div>-->
								<label class="control-label col-md-3">STB Thumbnail</label>

								<div class="col-md-8">
									<div ng-hide="messageState">
										<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderLogoSTB" nv-file-select="">
												</a>
											  </span>
										</div>

									</div>
									<span><small><em>(Max filesize 1M and supported format png)</em></small></span>
								</div>
							</div>
						</div>
						<div  class="col-md-12 push-bottom-20">
							<div  class="form-group">
								<!--<label for="exampleInputEmail1">Package Logo <small>(Mobile)</small> </label>
								<div class="col-md-12" style="padding-left: 0px;">
									<input type="text" class="form-control" ng-model="formData.package_logo_mobile"/>
								</div>-->
								<label class="control-label col-md-3">Mobile Thumbnail</label>

								<div class="col-md-8">
									<div ng-hide="messageState">
										<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderLogoMobile" nv-file-select="">
												</a>
											  </span>
										</div>

									</div>
									<span><small><em>(Max filesize 1M and supported format png)</em></small></span>
								</div>
							</div>
						</div>
						<div  class="col-md-12 push-bottom-20">
							<div  class="form-group">
								<!--<label for="exampleInputEmail1">Package Poster <small>(STB)</small> </label>
								<div class="col-md-12" style="padding-left: 0px;">
									<input type="text" class="form-control" ng-model="formData.package_poster_stb"/>
								</div>-->
								<label class="control-label col-md-3">Poster <small>(STB)</small></label>

								<div class="col-md-8">
									<div ng-hide="messageState">
										<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderPosterSTB" nv-file-select="">
												</a>
											  </span>
										</div>

									</div>
									<span><small><em>(Max filesize 1M and supported format png)</em></small></span>
								</div>
							</div>
						</div>
						<div  class="col-md-12 push-bottom-20">
							<div  class="form-group">
								<!--<label for="exampleInputEmail1">Package Poster <small>(Mobile)</small> </label>
								<div class="col-md-12" style="padding-left: 0px;">
									<input type="text" class="form-control" ng-model="formData.package_poster_mobile"/>
								</div>-->
								<label class="control-label col-md-3">Poster <small>(Mobile)</small></label>

								<div class="col-md-8">
									<div ng-hide="messageState">
										<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderPosterMobile" nv-file-select="">
												</a>
											  </span>
										</div>

									</div>
									<span><small><em>(Max filesize 1M and supported format png)</em></small></span>
								</div>
							</div>
						</div>

					</div>

					<?php

					if(!$packageType){
					?>

					<div class='col-md-6'>

						<div class="col-md-12">
                            <div class="col-md-4">
                                <label class="control-label">Program List </label>
                                <select id="select-from" ng-model="formData.selected_item"  style="width:200px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                            <div class="col-md-1" style="margin-top:25px;margin-right:13px;margin-left:53px;">
                                <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                                <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label class="control-label">Assigned Program List</label> 
                                <select id="select-from" ng-model="formData.included_item" style="width:200px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in assigned_programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                            <div class="col-md-12 col-md-offset-6" style="padding-left:40px;">
                            	Total : {{assigned_programs.length}} [max: 190]
                        	</div>
                        </div>
					</div>
					<?php } ?>
					<div class="col-md-12">
						<div class="col-md-10">
							<div class="form-group">
								<button type="submit" ng-disabled="package.$invalid" class="btn btn-success"> Save </button> <button type="reset" id="buttoncancel" class="btn btn-danger" >Reset</button>
							</div>
						</div>
					</div>
				</form> 
			</div>
		</div>
	</div>
	<div class="panel panel-default" ng-if="!addFormFlag">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<?php 
						if(preg_match('/live/',$uri)){
							$uri = 'channel packages';
						}
					?>
					<h4 class="widgettitle"><?php echo ucwords(str_replace('-',' ',$uri)); ?>
						<a ng-show="permissions.create_permission=='1'" ng-click="showForm(); " id="buttoncancel" class="btn btn-success btn-sm pull-right">
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
