<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var id = '<?php echo $user_info->id;  ?>';
    var user_type = '<?php echo $user_info->user_type; ?>';
</script>
<div id="container" ng-controller="LcoSubscribers" ng-cloak>
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
                        <h4 class="widgettitle">Search LCO Subscribers</h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" ng-submit="searchLcoStaff()">
                    <?php if($user_info->user_type == 'MSO'){ ?>
                        <div class="form-group">
                            <label class="control-label col-md-2">SELECT GROUP</label>
                            <div class="col-md-4">
                                <select kendo-combo-box
                                        k-placeholder="'Select Group'"
                                        k-data-text-field="'group_name'"
                                        k-data-value-field="'user_id'"
                                        k-data-source="group_profiles"
                                        k-change="'loadGroupLco()'"
                                        style="width: 100%" ng-model="group_user_id">

                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label class="control-label col-md-2">SELECT LCO</label>
                        <div class="col-md-4">
                            <select kendo-combo-box
                                    k-placeholder="'Select LCO'"
                                    k-data-text-field="'lco_name'"
                                    k-data-value-field="'user_id'"
                                    k-data-source="lco_profiles"
                                    style="width: 100%" ng-model="lco_user_id" >
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Filter</label>
                        <div class="col-md-4">
                            <select kendo-combo-box
                                    k-placeholder="'Select Filter'"
                                    k-data-text-field="'name'"
                                    k-data-value-field="'value'"
                                    k-data-source="filters"
                                    style="width:100%"
                                    ng-model="filter">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-md-offset-2">
                            <input type="submit" class="btn btn-primary" value="Search"/>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">LCO Subscriber List
                            <a  class="btn btn-default pull-right" ng-click="downloadSubscriberList()"><i class="fa fa-download"></i> Download</a>
                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <kendo-grid id="grid" options="mainGridOptions">
                    </kendo-grid>
                </div>
            </div>
        </div>
    </div>
</div>