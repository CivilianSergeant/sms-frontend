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
        <div class="row">
            <!-- <div class="col-md-12">
                <div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
            </div> -->
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">View Streamer Instance
                            <a ng-if="permissions.edit_permission == '1'"
                               href="<?php echo site_url('streamer-instance/edit/' . $instanceId); ?>" id="buttoncancel"
                               class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i
                                    class="fa fa-pencil"></i> Edit </a>
                            <a href="<?php echo site_url('streamer-instance'); ?>" id="buttoncancel"
                               class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">

                    <ul class="tab_nav nav nav-tabs">
                        <li ng-class="{active:tabs.detail}">
                            <a class="tab_top" ng-click="setTab('detail')">Detail</a>
                        </li>
                        <li ng-class="{active:tabs.hls}">
                            <a class="tab_top" ng-click="setTab('hls')">Assigned HLS</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div id="profile" class="tab-pane active" ng-show="tabs.detail">
                            <div class="form-horizontal" id="streamerInstance">

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Instance Name </label>

                                        <div class="col-md-3">
                                            <span style="position:relative;top:7px;">{{formData.instance_name}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Instance Local IP </label>

                                        <div class="col-md-3">
                                            <span style="position:relative;top:7px;">{{formData.instance_local_ip}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Instance Global IP </label>

                                        <div class="col-md-3">
                                            <span style="position:relative;top:7px;">{{formData.instance_global_ip}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Alias Domain/Url</label>

                                        <div class="col-md-3">
                                            <span
                                                style="position:relative;top:7px;">{{formData.alias_domain_url}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Instance Capacity </label>

                                        <div class="col-md-3">
                                            <span
                                                style="position:relative;top:7px;">{{formData.instance_capacity}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Instance Index </label>

                                        <div class="col-md-3">
                                            <span style="position:relative;top:7px;">{{formData.instance_index}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">Instance Description </label>

                                        <div class="col-md-6">
                                        <span
                                            style="position:relative;top:7px;">{{formData.instance_description}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-md-offset-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" disabled ng-model="formData.is_active" ng-checked="formData.is_active=='1'" ng-true-value="'1'" ng-false-value="'0'"/>
                                                <strong>Is Active</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="control-label col-md-3">LCO Operator</label>

                                        <div class="col-md-3">
                                            <select readonly kendo-combo-box
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
                            </div>
                        </div>
                        <div id="profile" class="tab-pane active" ng-show="tabs.hls">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>Channel</th>
                                        <th>HLS WEB</th>
                                        <th>HLS STB</th>
                                        <th>HLS MOBILE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="h in hls">
                                        <td>{{h.program_name}}</td>
                                        <td>{{h.hls_url_web}}</td>
                                        <td>{{h.hls_url_stb}}</td>
                                        <td>{{h.hls_url_mobile}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


