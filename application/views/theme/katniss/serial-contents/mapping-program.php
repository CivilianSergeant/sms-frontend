<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	.align-to-label{
		padding-top:8px;display:block;
	}
</style>
<script type="text/javascript">
	var programId = "<?php echo $program['id']; ?>";
	var programDir = "<?php echo $program['content_dir']; ?>";
	var parentId = "<?php echo $parentId; ?>";
</script>
<div id="container" ng-controller="MappingSerialProgram" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{warning_messages}}
	</div>

	<div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{success_messages}}
	</div>

	<div class="panel panel-default">
		<div class="row" >
			<!-- <div class="col-md-12">
				<div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
			</div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Mapping Content [<?php echo $program['program_name']; ?>]
							<a href="<?php echo site_url('serial-contents'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
							<a href="<?php echo site_url('serial-contents/edit/'.$program['id']); ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-pencil"></i> Edit </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" ng-submit="saveMapping()">
					<div class="col-md-12">

						<div class="form-group">
							<label class="control-label col-md-3">Content Name :</label>
							<div class="col-md-4">
								<span class="align-to-label"><?php echo $program['program_name']; ?></span>
							</div>
						</div>
						<!--<div class="form-group">
							<label class="control-label col-md-3">LCN :</label>
							<div class="col-md-2">
								<span class="align-to-label"><?php /*echo $program['lcn']; */?></span>
							</div>
						</div>-->
						<div class="form-group">
							<label class="control-label col-md-3">Type :</label>
							<div class="col-md-3">
								<span class="align-to-label"><?php echo $program['type']; ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Description :</label>
							<div class="col-md-6">
								<span class="align-to-label"><?php echo (!empty($program['description']))? base64_decode($program['description']) : null; ?></span>
							</div>
						</div>
						<div class="form-group" ng-if="parentId == 1">
							<label class="control-label col-md-3">Operator</label>
							<div class="col-md-5">
								<select kendo-combo-box
										k-placeholder="'Select LCO'"
										k-data-text-field="'lco_name'"
										k-data-value-field="'user_id'"
										k-data-source="operators"
										k-change="'loadStreamerInstances()'"
										k-bind="true"
										ng-model="formData.operator_id"
								style="width:100%"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Streamer Instance</label>
							<div class="col-md-5">
								<select kendo-combo-box
										k-placeholder="'Select Streamer Instance'"
										k-data-text-field="'instance_name'"
										k-data-value-field="'id'"
										k-data-source="instances"
										k-bind="true"
										ng-model="formData.instance_id"
										ng-disabled="instances==null || instances.length==0"
										style="width:100%"></select>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<input type="hidden" ng-model="formData.programId"/>
						<hr/>
					</div>
					<div class="col-md-12">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>SL</th>
									<th>HLS URL (Mobile)</th>
									<th>HLS URL (STB)</th>
									<th>HLS URL (WEB)</th>
									<th><a ng-click="addRow()" ng-disabled="formData.instance_id == undefined" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add HLS</a></th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="i in formData.hls">
									<td>{{($index+1)}}</td>
									<td><input type="text" class="form-control" ng-model="i.hls_url_mobile" /></td>
									<td><input type="text" class="form-control input-large" ng-model="i.hls_url_stb"/></td>
									<td><input type="text" class="form-control input-large" ng-model="i.hls_url_web"/></td>
									<td><a ng-click="deleteRow($index)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-12" ng-if="formData.hls.length>0">
						<div class="form-group">
							<div class="col-md-3 pull-right">
								<button type="submit" class="btn btn-success">Save Mapping</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>