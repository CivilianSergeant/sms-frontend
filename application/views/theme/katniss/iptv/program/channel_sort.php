<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="container" ng-controller="ChannelSort" ng-cloak>

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
                        <h4 class="widgettitle">Channel Sort</h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <!--All Channel-->
                <style>
                    #sortable {
                        border: 1px solid #eee;
                        width: 100%;
                        min-height: 14px;
                        list-style-type: none;
                        margin: 0;
                        padding: 5px 0 0 0;
                        float: left;
                        margin-right: 10px;
                        margin-top: 10px;
                    }
                    #sortable li {
                        margin: 0 5px 5px 5px;
                        padding: 5px;
                        font-size: 14px;
                        float: left;
                        height: 30px;
                        width: 15%;
                    }
                    .ui-state-disabled {
                        background: #FFF !important; 
                        color: #008000 !important;
                        pointer-events: all !important;
                        opacity: 1 !important;
                    }
                    
                    .acttive_program{
                        color: green;
                    }
                    .inactive_program{
                        color: red !important; 
                    }
                </style>
                <div class="col-md-2" style="width: 100px;">
                    <label class="control-label">First Index</label>
                </div>
                <div class="col-md-3" style="padding: 0px; width: 60px;">
                    <input type="text" class="form-control" id="index-start" value="100" onkeypress="return isNumber(event);" />
                </div>
                <div class="col-md-3">
                    <button id="sort-program" class="btn btn-success btn-sm">Apply</button>
                </div>
                <p></p>
                <div id="program-grid">
                    <ul id="sortable" class="connectedSortable">
                        <li ng-repeat="x in programs" ng-if="x.is_active==1" class="ui-state-default" ng-class="{ 'static':(x.is_sort_locked==1)}"    data-uid="{{x.id}}" id="item-{{ $index + 1 }}">{{ x.lcn }} - {{ x.program_name }} <i ng-click="updateProgramStatus(x.id, x.is_sort_locked)" ng-if="x.is_sort_locked == 0" class="fa fa-unlock pull-right" aria-hidden="true"></i><i ng-click="updateProgramStatus(x.id, x.is_sort_locked)" ng-if="x.is_sort_locked == 1" class="fa fa-lock pull-lock pull-right" aria-hidden="true"></i></li>
                        <li ng-repeat="x in programs" ng-if="x.is_active==0" class="ui-state-default static inactive_program" data-uid="{{x.id}}" id="item-{{ $index + 1 }}">{{ x.lcn }} - {{ x.program_name }} </li>
                        
<!--                        <li ng-repeat="x in programs" ng-if="x.is_active==1" class="ui-state-default" ng-class="{ inactive_program:(x.is_active==0),'static':(x.is_sort_locked==1)}"   data-uid="{{x.id}}" id="item-{{ $index + 1 }}">{{ x.lcn }} - {{ x.program_name }} <i ng-click="updateProgramStatus(x.id, x.is_sort_locked)" ng-if="x.is_sort_locked == 0" class="fa fa-unlock pull-right" aria-hidden="true"></i><i ng-click="updateProgramStatus(x.id, x.is_sort_locked)" ng-if="x.is_sort_locked == 1" class="fa fa-lock pull-lock pull-right" aria-hidden="true"></i></li>
                        <li ng-if="x.is_active == 0" ng-repeat="x in programs" class="ui-state-default inactive_program ui-state-disabled" data-uid="{{x.id}}" id="item-{{ $index + 1}}">{{ x.lcn}} - {{ x.program_name}}</li>-->
                    </ul> 
                </div>

                <!--End All Channel-->
            </div>
        </div>
    </div>
</div>
<script>
    var isNumber = function(evt) 
        {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        };
</script>
<style type="text/css">
    #service_operator li{list-style: none;}
    #service_operator li{margin-left: -35px;}
</style>