<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
	var card_info_id = "<?php echo $card_info_id; ?>";
</script>
<div id="container" ng-controller="ScratchCardBatchInfo" ng-cloak>
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

	<div class="panel panel-default">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">
							Scratch Cards Batch Info Detail
							<a ng-click="showLogin()" id="buttoncancel" class="btn btn-primary btn-sm pull-right"><i class="fa fa-download"></i> Download </a>
							<a href="<?php echo site_url('scratch-card-generate'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-arrow-left"></i> Back </a>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12" ng-if="!showDownloadFrm">
					<form method="post" action="<?php echo site_url('scratch-card-generate/change-status'); ?>">
						<div class="col-md-3">
							<div class="form-group">
								<strong>Batch Number : </strong><?php echo $card_info->batch_no; ?>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<strong>Value : </strong><?php echo $card_info->value; ?>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<strong>Number Of Cards : </strong><?php echo $card_info->number_of_cards; ?>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<strong>Active From : </strong><?php echo $card_info->active_from_date; ?>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<strong>Prefix : </strong><?php echo $card_info->prefix; ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<strong >Is Active : </strong>

								<select class="form-control inline" name="status">
									<?php foreach($status as $i=> $s){ ?>
										<option <?php if($card_info->is_active == $i){ echo 'selected="selected"'; } ?> value="<?php echo $i; ?>"><?php echo $s; ?></option>
									<?php } ?>
								</select>
								<?php //echo ($card_info->is_active == 1) ? '<span class="text-success">Active</span>' : '<button class="btn btn-danger btn-sm">Inactive</button>'; ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> Update Status</button>
							</div>
						</div>
						<input type="hidden" name="id" value="<?php echo $card_info->id; ?>"/>
					</form>
				</div>
				<div class="col-md-12" ng-if="!showDownloadFrm">
					<!--<div class="col-md-4" style="padding-left: 0px">
                        <div class="form-group">
                            <input type="text" class="form-control" ng-model="card_no" placeholder="Search By Card Numebr">
                        </div>
                    </div>-->
					<div class="col-md-4" style="padding-left: 0px">
						<div class="form-group">
							<input type="text" class="form-control" ng-model="queryData.serial_no" placeholder="Search By  Serial Number">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<select ng-model="queryData.search_type" class="form-control">
								<option value="">None</option>
								<option value="used">USED Card</option>
								<option value="unused">Un-Used Card</option>
								<option value="distributed">Distributed Card</option>
							</select>
						</div>
					</div>
					<div class="col-md-1" style="padding: 0px">
						<div class="form-group">
							<button class="btn btn-primary btn-sm" type="submit" ng-click="getCardByCardNoSerialNo()"><i class="fa fa-search"> Search</i> </button>
						</div>
					</div>
				</div>
				<div class="col-md-12" ng-if="!showDownloadFrm">
					<kendo-grid options="mainGridOptions" id="cardInfoGrid"></kendo-grid>
				</div>
				<div class="col-md-12" ng-if="showDownloadFrm">
					<form class="form-horizontal" ng-submit="downloadScratchCard()">
						<div class="form-group">
							<label class="control-label col-md-3">Password :</label>
							<div class="col-md-3">
								<input type="password" class="form-control" ng-model="download.password"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">

								<input type="submit" class="btn btn-success"/>
								<input type="button" ng-click="hideLogin()" class="btn btn-danger" value="Cancel"/>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>



