<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
	.align-to-label{
		padding-top:8px;display:block;
	}
</style>
<script type="text/javascript">
</script>
<div id="container" ng-controller="SyncEPGCustom" ng-cloak>

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
						<h4 class="widgettitle">Sync EPG </h4>
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
                                                <select  class="form-control" ng-model="formData.provider" ng-change="loadMapping()">
                                                    <option value="">--SELECT PROVIDER--</option>
                                                    <?php 
                                                        if(!empty($providers)){
                                                            foreach($providers as $provider){
                                                                echo '<option value='.$provider->id.'>'.$provider->provider_name.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Provider Channel</label>
                                            <div class="col-md-3">
                                                <select class="form-control" ng-model="formData.providerChannel" ng-disabled="!formData.provider">
                                                    <option value="">--SELECT PROVIDER--</option>
                                                    <option ng-repeat="channel in channels" value="{{channel.streaming_channel_id}}">{{channel.provider_channel_name}}[{{channel.provider_channel_id}}]-{{channel.program_name}}[{{channel.streaming_channel_id}}]</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Tag ID</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" ng-model="formData.tag"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Date</label>
                                            <div class="col-md-3">
                                                <input type="text" kendo-date-picker k-format="'yyyy-MM-dd'" ng-model="formData.date" required="required"/>
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