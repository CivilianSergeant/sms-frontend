<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .table-bordered{border:1px solid;}
    .lightgreen{background: #92f4c7;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}

</style>
<div id="container" ng-controller="GroupAssign" ng-cloak>


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
                        Assign LCO To Group

                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" ng-submit="assignLco()">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label style="margin-top:10px;">Select Group</label>
                                <select ng-model="formData.group_id"
                                        kendo-combo-box
                                        k-placeholder="'Select Group'"
                                        k-data-text-field="'group_name'"
                                        k-data-value-field="'user_id'"
                                        k-filter="'contains'"
                                        k-min-length="5"
                                        k-data-source="groups"
                                        k-change="'loadGroupLco()'"
                                        style="width: 100%">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12" >

                                <label style="margin-top:10px;">Exclude</label>
                                <select id="select-from" ng-model="selected_item"  style="width:240px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="lco in lcos"  style="font-size:13px" value="{{lco.user_id}}" >{{lco.lco_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1" style="margin-top:105px;margin-right:0px;margin-left:20px;">
                        <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                        <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">

                                <label style="margin-top:10px;">Include</label>
                                <select id="select-from" ng-model="included_item" style="width:240px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="al in assigned_lcos"  style="font-size:13px" value="{{al.user_id}}" >{{al.lco_name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-primary" value="Assign"/>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


</div>