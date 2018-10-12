<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container" ng-controller="GenerateCard" ng-cloak>
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

					<h4 class="widgettitle"> Generate Scratch Card
						<a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
					</h4>

					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<!-- Form Left Part -->
			<div class="panel-body">
				<form  ng-submit="saveCards()" method="POST" name="saveCardAdd" class="form-horizontal">
					<div class="col-md-5">

						<div class="form-group">
							<label class="col-sm-4 control-label" for="prefix">Prefix <span style="color:red">*</span></label>
							<div class="col-sm-8">
								<input type="text" maxlength="2" class="form-control" id="prefix" ng-model="card_data.prefix" placeholder="Enter Card Prefix" required="required">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="value">Value <span style="color:red">*</span></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="value" ng-model="card_data.value" placeholder="Enter Card Value" required="required">
							</div>
						</div>

					</div>
					<div class="col-md-5">

						<div class="form-group">
							<label class="col-sm-4 control-label" for="number_of_cards">Number Of Cards <span style="color:red">*</span></label>
							<div class="col-sm-8" style="height:0px;">
								<input type="text" maxlength="4" class="form-control" id="number_of_cards" ng-model="card_data.number_of_cards" placeholder="Enter Number Of Cards" required="required">
								<span style="position:relative;left:282px;top:-25px;"><small>(max 10000 card)</small></span>
							</div>

						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="active_from">Active From <span style="color:red">*</span></label>
							<div class="col-sm-8">
								<input type="text" kendo-datepicker k-format="'yyyy-MM-dd'" class="form-control" id="active_from" ng-model="card_data.active_from" placeholder="2011-12-01" style="width: 100%">
							</div>
						</div>

					</div>

					<div class="col-md-10 padding-0 text-right">
						<input type="submit" id="buttonsuccess" id="btnNext" ng-disabled="saveCardAdd.$invalid" class="btn btn-success btnNext" value="Generate Card" />
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="panel panel-default" ng-if="!showFrm">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">
							All Scratch Card Batch List
							<a href="<?php echo site_url('scratch-card-generate/download-pdf'); ?>" class="btn btn-default btn-sm pull-right"><i class="fa fa-download"></i> Download </a>
							<a ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-plus-circle"></i> Generate Card </a>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<kendo-grid options="mainGridOptions">
					</kendo-grid>
				</div>
			</div>
		</div>
	</div>
</div>  



