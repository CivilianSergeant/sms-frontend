<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var lspTypeId = "<?php echo $user_info->lsp_type_id; ?>";
</script>
<div id="container" ng-controller="StreamerInstance" ng-cloak>

    <div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>

    <div class="panel panel-default" ng-show="showFrm">
        <div class="row" >
            <!-- <div class="col-md-12">
                <div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
            </div> -->
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">Add New Streamer Instance
                            <a ng-click="hideForm(); removeAlert()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <form name="package" class="form-horizontal" id="package" method="POST" ng-submit="saveStreamerInstance()">

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Name <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.instance_name" placeholder="Enter Instance Name" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Local IP <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.instance_local_ip" placeholder="Enter Instance IP" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Global IP <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.instance_global_ip" placeholder="Enter Instance IP" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Alias Domain/Url <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.alias_domain_url" placeholder="Enter Alias Domain/Url" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Capacity <span style="color:red">*</span></label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" ng-model="formData.instance_capacity" placeholder="Enter Instance Capacity" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Index </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.instance_index" placeholder="Enter Instance INDEX" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Description </label>
                                <div class="col-md-6">
                                    <textarea class="form-control" style="height:90px;resize:none;" ng-model="formData.instance_description" placeholder="Enter Instance Description" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="formData.is_active" ng-checked="formData.is_active=='1'" ng-true-value="'1'" ng-false-value="'0'"/>
                                        <strong>Is Active</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" ng-if="lspTypeId==0">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Operator</label>
                                <div class="col-md-3">
                                    <select kendo-combo-box class="from-control"
                                            k-placeholder="'Select LCO'"
                                            k-data-text-field="'lco_name'"
                                            k-data-value-field="'user_id'"
                                            k-data-source="operators"
                                            k-bind="true"
                                            ng-model="formData.operator_id">
                                        ></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3 col-md-offset-3">
                                    <button type="submit" ng-disabled="package.$invalid" class="btn btn-success"> Submit </button> <button type="reset" id="buttoncancel" class="btn btn-danger" >Reset</button>
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

                    <h4 class="widgettitle">Streamer Instance List
                        <a ng-show="permissions.create_permission=='1'" ng-click="showForm(); removeAlert()" id="buttoncancel" class="btn btn-success btn-sm pull-right">
                            <i class="fa fa-plus-circle"></i> Add Streamer Instance
                        </a>
                    </h4>
                </div>
                <span class="clearfix"/>
                <hr/>
            </div>



            <div class="panel-body">
                <div class="col-md-12" ng-if="!delete_item && !sync_item">
                    <div kendo-grid id="grid" options="mainGridOptions"></div>
                </div>
                <div class="col-md-12 text-center" ng-if="delete_item">
                    <form>
                        <p><strong>Are you sure to delete this instance</strong></p>
                        <p>
                            <input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
                            <input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
                        </p>
                    </form>
                </div>
                <div class="col-md-12 text-center" ng-if="sync_item">
                    <form>
                        <p><strong>Are you sure to delete this instance</strong></p>
                        <p>
                            <input type="submit" ng-click="confirm_sync()" class="btn btn-danger" value="Yes"/>
                            <input type="button" ng-click="cancel_sync()" class="btn btn-warning" value="No"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


