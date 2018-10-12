<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="LcoUsers" ng-cloak>
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
                        <h4 class="widgettitle">Search LSP Users</h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" ng-submit="searchLcoStaff()">
                    <div class="form-group">
                        <label class="control-label col-md-2">SELECT LSP</label>
                        <div class="col-md-3">
                            <select kendo-combo-box
                                    k-placeholder="'Select LCO'"
                                    k-data-text-field="'lco_name'"
                                    k-data-value-field="'user_id'"
                                    k-data-source="lco_profiles"
                                    style="width: 100%" ng-model="lco_user_id" >
                            </select>
                        </div>
                        <div class="col-md-2">
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
                        <h4 class="widgettitle">LSP Users List</h4>
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