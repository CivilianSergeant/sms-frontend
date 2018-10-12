<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="AvailableScratchCard" ng-cloak>
	<script type="text/javascript">

		var token = "<?php echo $user_info->token ?>";
		var user_type = '<?php echo $user_info->user_type; ?>';
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


	<div class="panel panel-default">

		<div class="row">


			<div class="col-md-12">
				<div class="panel-heading">

					<h4 class="widgettitle">
						Avaialble Card List

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