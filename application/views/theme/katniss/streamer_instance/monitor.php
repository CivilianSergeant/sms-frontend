<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">

</script>
<div id="container" ng-controller="MonitorStreamerInstance" ng-cloak>

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
                        <h4 class="widgettitle">Monitor Instance <span class="text-danger">Under Construction</span>

                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <form name="monitorInstance" class="form-horizontal" id="monitorInstance" method="POST" ng-submit="getStreamerInstanceData()">

                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label col-md-3">Select Instance <span style="color:red">*</span></label>
                                <div class="col-md-3">
                                    <select class="form-control" ng-model="formData.instance_id" required="required">
                                        <option>All</option>
                                        <option ng-repeat="instance in instances"  value="{{instance.id}}">{{instance.instance_name}} [{{instance.instance_local_ip}}]</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3 col-md-offset-3">
                                    <button type="submit" ng-disabled="monitorInstance.$invalid" class="btn btn-success"> Search </button>
                                </div>
                            </div>
                        </div>

                        <hr/>
                            <!-- <div id="grid"></div> -->
                       <kendo-grid id="monitorInstanceGrid" options="mainGridOptions" >

                       </kendo-grid>


                        <!-- <table class="table">
                            <thead>
                            <tr>
                                <th>Streamer ID</th>
                                <th>Customer ID</th>
                                <th>Customer Token</th>
                                <th>Start Time</th>
                                <th>User IP</th>
                                <th>Channel Name</th>
                                <th>Bit Rate</th>
                                <th>Duration</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="d in instance_data">
                                    <td>{{d.streamerId}}</td>
                                    <td>{{d.customerId}}</td>
                                    <td>{{d.customerToken}}</td>
                                    <td>{{d.startTime}}</td>
                                    <td>{{d.userIp}}</td>
                                    <td>{{d.channelName}}</td>
                                    <td>{{d.bitRate}}</td>
                                    <td>{{d.duration}}</td>
                                </tr>
                            </tbody>
                        </table> -->

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


