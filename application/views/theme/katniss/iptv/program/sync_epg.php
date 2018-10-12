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
</script>
<div id="container" ng-controller="SyncEPG" ng-cloak>

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
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Sync EPG for Channel [<?php echo $program['program_name']; ?>]
							<a href="<?php echo site_url('channels'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
							<a href="<?php echo site_url('manage-epg'); ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-cog"></i> Manage EPG </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" ng-submit="syncEpg()">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Provider</label>
                                            <div class="col-md-3">
                                                <input typ="text" class="form-control" readonly="readonly" value="<?php echo $epg_provider_info->provider; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Provider Channel</label>
                                            <div class="col-md-3">
                                                <input typ="text" class="form-control" readonly="readonly" value="<?php echo $epg_provider_info->provider_channel; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Tag ID</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" ng-model="tag_id"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Date</label>
                                            <div class="col-md-3">
                                                <input type="text" kendo-date-picker k-format="'yyyy-MM-dd'" ng-model="date" required="required"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-3 col-md-offset-3">
                                                <button type="submit" ng-disabled="loading" class="btn btn-success"><span ng-show="loading==0">Sync EPG</span><span ng-show="loading==1">Sync in progress</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    
				</form>
			</div>
		</div>
	</div>

</div>