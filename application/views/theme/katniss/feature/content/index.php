<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="CreateFeatureContent" ng-cloak>

    <?php if ($this->session->flashdata('success')) { ?>

        <div class="alert alert-success">
            <button class="close" aria-label="close" data-dismiss="alert">×</button>
            <p><?php echo $this->session->flashdata('success') ?></p>
        </div>

    <?php } ?>

    <div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>

    <div class="panel panel-default" ng-if="addFormFlag">
        <div class="row" >
            <!-- <div class="col-md-12">
                <div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
            </div> -->
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">Add Feature Content
                            <a ng-click="hideForm();" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form name="package" id="package" method="POST">

                    <div class='col-md-6'>

                        <div class="form-group">
                            <label class="control-label col-md-3">Type</label>
                            <div class="col-md-3">
                                <select id="select-type" ng-model="formData.type" ng-change="loadContent()">
                                    <option ng-repeat="t in types" value="{{t}}">{{t}}</option>
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <br/>
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <label class="control-label">Program List </label>
                                <input type="text" placeholder="Search" style="width:200px;" ng-model="programSearch"/>
                                <select id="select-from" ng-model="formData.selected_item"  style="width:200px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in programs | filter:programSearch"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                            <div class="col-md-1" style="margin-top:25px;margin-right:15px;margin-left:48px;">
                                <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                                <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label class="control-label">Assigned Program List</label>
                                <select id="select-from" ng-model="formData.included_item" style="width:200px;min-height:210px;" multiple="multiple" >
                                    <option ng-repeat="p in assigned_programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                            <div class="col-md-12 col-md-offset-6" style="padding-left:48px;">
                                Total : {{assigned_programs.length}} [max: 10]
                            </div>

                            <!--<div class="col-md-12" style="margin-top:10px;">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>-->
                        </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="panel panel-default" ng-if="!addFormFlag">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">
                            FEATURE CONTENT LIST
                            <a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add Feature Content </a>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12" ng-if="!delete_flag">
                    <kendo-grid options="mainGridOptions" id="stp-grid">
                    </kendo-grid>
                </div>
                <div class="col-md-12 text-center" ng-if="delete_flag">
                    <form>
                        <p><strong>Are you sure to delete this Channel</strong></p>
                        <p>
                            <input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
                            <input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




