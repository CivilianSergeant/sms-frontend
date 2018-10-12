<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="Distribution" ng-cloak>
	<script type="text/javascript">
		var user_type = "<?php echo $user_info->user_type; ?>";
		var user_id = "<?php echo $id; ?>";
	</script>

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

	<div class="panel panel-default" ng-if="showFrm">

		<div class="row">


			<div class="col-md-12">
				<div class="panel-heading">

					<h4 class="widgettitle">
						Card Distribution
						  <a id="buttoncancel" ng-click="hideForm()" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a>
					</h4>


					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="col-md-12">
				<div class="panel-body" >
					<form  ng-submit="saveDistributionData()" method="POST" name="saveDistribution" class="form-horizontal">
						<div class="col-md-12">
							<?php if($user_info->user_type == "MSO") { ?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="batch">Group <span style="color:red">*</span></label>
									<div class="col-sm-5">
										<div class="margin-bottom-sm">
											<select kendo-combo-box
													k-placeholder="'Select Group'"
													k-data-text-field="'group_name'"
													k-data-value-field="'user_id'"
													k-on-change="loadData()"
													k-data-source="group_profiles"
													style="width: 100%" ng-model="distribution_data.group_id"  required="required">
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if($user_info->user_type == "MSO" || $user_info->user_type=="Group") { ?>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="batch">LCO </label>
									<div class="col-sm-5">
										<div class="margin-bottom-sm">
											<select kendo-combo-box
													k-placeholder="'Select LCO'"
													k-data-text-field="'lco_name'"
													k-data-value-field="'user_id'"
													k-on-change="loadDistributors()"
													k-data-source="lco_profiles"
													ng-disabled="distribution_data.group_id > 1"
													style="width: 100%" ng-model="distribution_data.lco_id">
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<!--<div class="form-group">
									<label class="col-sm-2 control-label" for="batch">Distributors </label>
									<div class="col-sm-5">
										<div class="margin-bottom-sm">
											<select kendo-combo-box
											k-placeholder="'Select Distributor'"
											k-data-text-field="'distributor_name'"
											k-data-value-field="'id'"

											k-data-source="distributor_profiles"
											style="width: 100%" ng-model="distribution_data.distributor_id" >
										</select>
									</div>
								</div>
							</div>-->

							<div class="form-group">
								<label class="col-sm-2 control-label" for="batch">Batch Number <span style="color:red">*</span></label>
								<div class="col-sm-5">
									<div class="margin-bottom-sm">
										<select kendo-combo-box
												k-placeholder="'Select Batch Number'"
												k-data-text-field="'batch_no'"
												k-data-value-field="'batch_no'"
												k-change="'loadSerialNumbers()'"
												k-data-source="batch_numbers"
												style="width: 100%" ng-model="distribution_data.batch" >
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="inline"><input type="checkbox" ng-model="sameAsFrom" ng-change="setToSerialNo()" /> <small style="padding-bottom:2px;">( Same as From )</small></label>
								<label class="col-sm-2 control-label" for="serial_from">Serial From <span style="color:red">*</span></label>
								<div class="col-sm-2">
									<input type="text" list="serial_numbers" autocomplete="off" class="form-control" id="serial_from" ng-model="distribution_data.serial_from" placeholder="Enter Serial From" required="required">
									<datalist id="serial_numbers">
										<option ng-repeat="s in serial_numbers" value="{{s.serial_no}}"/>
									</datalist>
								</div>

								<label class="col-sm-1 control-label" for="serial_to">To <span style="color:red">*</span></label>
								<div class="col-sm-2">
									<input type="text" list="serial_numbers" autocomplete="off" minlength="" class="form-control" id="serial_to" ng-change="checkCardSerial()" ng-model="distribution_data.serial_to" placeholder="Enter Serial To" required="required">
									<datalist id="serial_numbers">
										<option ng-repeat="s in serial_numbers" value="{{s.serial_no}}"/>
									</datalist>
								</div>

								<div class="col-md-7 text-right" style="padding-top: 10px">
									<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="saveDistribution.$invalid" class="btn btn-success btnNext" value="Distribute Card" />

								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default" ng-if="!showFrm">

		<div class="row">


			<div class="col-md-12">
				<div class="panel-heading">

					<h4 class="widgettitle">
						Distribution List
						  <a ng-click="showForm()" class="btn btn-default btn-sm pull-right"><i class="fa fa-paper-plane"></i> Distribute Card</a>
					</h4>


					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="col-md-12">
				<div class="panel-body">
					<kendo-grid options="mainGridOptions">
					</kendo-grid>
				</div>
			</div>
		</div>
	</div>
</div>