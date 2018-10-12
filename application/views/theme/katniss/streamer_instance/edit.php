<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var instanceId = "<?php echo $instanceId; ?>";
</script>
<div id="container" ng-controller="EditStreamerInstance" ng-cloak>

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
                        <h4 class="widgettitle">Edit Streamer Instance
                            <a href="<?php echo site_url('streamer-instance/view/'.$instanceId); ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-search"></i> View </a>
                            <a href="<?php echo site_url('streamer-instance'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
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
                                    <input type="text" class="form-control" ng-model="formData.instance_local_ip" placeholder="Enter Instance Local IP"  required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Global IP <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.instance_global_ip" placeholder="Enter Instance Global IP"  required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Alias Domain/Url <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.alias_domain_url" placeholder="Enter Alias Domain/Url"  required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Instance Capacity </label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" ng-model="formData.instance_capacity" placeholder="Enter Instance Capacity" >
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
                                    <textarea class="form-control" ng-model="formData.instance_description" placeholder="Enter Instance Description" ></textarea>
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
                        <div class="form-group">
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
                                    <button type="submit" ng-disabled="package.$invalid" class="btn btn-success"> Submit </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


