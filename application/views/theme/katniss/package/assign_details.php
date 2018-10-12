<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="assignedDetails" ng-cloak>
	
	<div class="panel panel-default">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">					
					<h4 class="widgettitle">Package Assign Details
						<a href="<?php echo site_url('package'); ?>" id="buttoncancel" class="btn btn-primary btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
					</h4>
				</div>
				<span class="clearfix"/>
				<hr/>
			</div>
		</div>
		<div class="row">

			<div class="col-lg-12">
				
				<div class="panel-body">
					<div kendo-grid id="grid" options="mainGridOptions"></div>
					
					<!-- <table class="table">
						<thead>
							<tr>
								<th>Pairing ID</th>
								<th>Subscriber Name</th>
								<th>Package Start Date</th>
								<th>Package Expire Date</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($assigned_package_list)){ ?>
							<?php foreach($assigned_package_list as $item){ ?>
							<tr>
								<th><?php echo $item->pairing_id; ?></th>
								<th><?php echo $item->subscriber_name; ?></th>
								<th><?php echo $item->package_start_date; ?></th>
								<th><?php echo $item->package_expire_date; ?></th>
							</tr>
							<?php  } ?>
							<?php } ?>
						</tbody>
					</table> -->

					

				</div>
			</div>
		</div>

	</div>
</div>
