<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
	var token = "<?php echo $user_info->token;?>";
	var user_id = "<?php echo $user_info->id; ?>";
	var user_type = "<?php echo $user_info->user_type; ?>";
</script>
<div id="container" ng-controller="ScratchPayment" ng-cloak>


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
					<h4 class="widgettitle"> Scratch Card Recharge</h4>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="col-md-12">
				<div class="panel-body">
					<form class="form-horizontal" name="ScratchPayment" ng-submit="saveScratchPayment()">
						<div class="form-group">

							<label class="control-label col-md-3">
								Subscriber Pairings <span class="text-danger">*</span>
							</label>
							<div class="col-md-3">
								<select

										ng-model="formData.pairing_id"
										kendo-combo-box
										k-placeholder="'Select Pairing ID'"
										k-data-text-field="'pairing_id'"
										k-data-value-field="'id'"
										k-filter="'contains'"
										k-auto-bind="false"
										k-min-length="5"
										k-data-source="pairings"
										k-change="'setPairingID()'"
										style="width: 100%" required="required"></select>

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Serial No <span class="text-danger">*</span></label>
							<div class="col-md-3">
								<input type="text" ng-model="formData.serial_no" class="form-control" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Card No <span class="text-danger">*</span></label>
							<div class="col-md-3">
								<input type="text" ng-model="formData.card_no" class="form-control" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<input type="submit" class="btn btn-success" nd-disabled="ScratchPayment.$invalid" value="Make Payment"/>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>