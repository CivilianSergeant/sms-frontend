<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>

<div id="container" ng-controller="PackageAssign" ng-cloak>

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
    

    <div class="panel panel-default">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel-heading">
                        
                            <h4 class="widgettitle">Add-On Package assign <span ng-if="profile.id">[ {{profile.subscriber_name}} ]</span>
                                <span ng-if="profile.id">[Available Balance: 
                                            <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                            <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                            ]</span>
                                <a href="<?php echo site_url('add-on-package-subscriber'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                            </h4>
                            
                     
                        <span class="clearfix"></span>
                    </div>
                    <hr/>
                </div>
                <div class="col-md-12">
                    <div class="panel-body">
                        <form class="form-horizontal" >
                            <div id="package_assign" class="form-horizontal" style="padding-top:10px;">

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-5">
                                                <label>SUBSCRIBER:</label>
                                                <!--<select ng-model="subscriber_id" class="form-control">
                                                    <option value="">--SELECT SUBSCRIBER--</option>
                                                    <option ng-repeat="s in subscribers" value="{{s.id}}">{{s.subscriber_name}}</option>
                                                </select>-->
                                                <select

                                                    ng-model="subscriber_id"
                                                    kendo-combo-box
                                                    k-placeholder="'Select Subscriber'"
                                                    k-data-text-field="'subscriber_name'"
                                                    k-data-value-field="'user_id'"
                                                    k-change = "'getPairingId()'"
                                                    k-min-length="5"
                                                    k-data-source="subscribers"

                                                    style="width: 100%" required>
                                                </select>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="form-group" ng-if="balance">
                                        <div class="col-md-12" >
                                            <div class="col-md-5">
                                                <label class="control-label">AVAILABLE BALANCE:</label>
                                                <span><strong ng-class="[{'text-green':(balance>0)},{'text-danger':(balance==0)}]">{{balance}}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-5">
                                                <label>STB/SMART-CARD PAIRING:</label>
                                                <!--<select name="stb_card_id" ng-model="stb_card_id" class="form-control">
                                                    <option value="">--SELECT STB-SMART-CARD PAIR</option>
                                                    <option ng-repeat="stb_card in unassigned_stb_cards"  value="{{stb_card.id}}">PairingID({{stb_card.pairing_id}})-STB({{stb_card.stb_number}})-Card({{stb_card.smart_card_number}})</option>
                                                </select>-->
                                                <select

                                                    ng-model="stb_card_id"
                                                    ng-change="setStbCardId()"
                                                    kendo-combo-box
                                                    k-placeholder="'Select Pairing ID'"
                                                    k-data-text-field="'pairing_id'"
                                                    k-data-value-field="'id'"
                                                    k-min-length="5"
                                                    k-data-source="stb_card_pairs"
                                                    style="width: 100%" required>
                                                </select>
                                            </div>
                                            
                                            <!-- <div>
                                                <label>Available Balance:</label>
                                                <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                                <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                            </div> -->
                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-4">
                                                <label class="control-label">Available Add-On Package List {{selected_package}}</label>
                                                <select id="select-from" class="form-control" ng-model="selected_package" ng-disabled="(packages.length<=0 || (subscriber_id == null || stb_card_id == null))">
                                                    <option value="">--SELECT Package--</option>
                                                    <option ng-repeat="p in packages"  style="font-size:13px" value="{{p.id}}" >{{p.package_name}} - [ Price:{{p.price}} - Duration:{{p.duration}} - Count:{{p.programs}} ]</option>
                                                </select>
                                            </div>
                                            <!--<div class="col-md-1" style="margin-top:25px;">
                                                <button type="button" ng-click="IncludeItems()" ng-disabled="(packages.length<=0 || (subscriber_id == null || stb_card_id == null))" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                                                <button type="button" ng-click="ExcludeItems()" ng-disabled="assigned_packages.length<=0" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label">Assigned Add-On Package List</label>
                                                <select id="select-from" ng-model="included_item" style="min-width:245px;min-height:100px;" ng-model="selected_package" multiple="multiple" >
                                                    <option ng-repeat="p in assigned_packages"  style="font-size:13px" value="{{p.id}}" >{{p.package_name}} - [ Price:{{p.price}} - Duration:{{p.duration}} - Count:{{p.programs}} ]</option>
                                                </select>
                                            </div>-->
                                        </div>
                                        
                                    </div>
                                    <?php if(0){ ?>
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <div class="col-md-5">
                                                <label>Total Price: {{package_price}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <!--<div class="form-group">
                                        <div class="col-md-10">
                                            <div class="col-md-5">
                                                <label>Charge By</label>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label class="control-label"><input type="radio" ng-model="charge_type" ng-disabled="!unassigned_stb_cards.length" value="1" /> Amount</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label class="control-label"><input type="radio" ng-model="charge_type" ng-disabled="!unassigned_stb_cards.length" value="0" /> Package</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                    <?php if(!empty($permissions) && $permissions->create_permission==1){ ?>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <input type="button" ng-disabled="isDisabledAssignPackage()" id="buttonsuccess" ng-click="addPackage()"  class="btn btn-success" value="Add Package"/>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>

                                <div class="col-md-12"><hr/></div>
                                <div class="row">
                                    <hr/>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Pairing ID</th>
                                                    <th>Subscriber</th>
                                                    <th>Package Name</th>
                                                    <th>Amount</th>
                                                    <th>Duration</th>
                                                    <th>Package Start Date</th>
                                                    <th>Package End Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr ng-repeat="item in add_on_packages">
                                                    <td>{{item.pairing_id}}</td>
                                                    <td>{{item.subscriber_name}}</td>
                                                    <td>{{item.package_name}}</td>
                                                    <td>{{item.price}}</td>
                                                    <td>{{item.duration}}</td>
                                                    <td>{{item.start_date}}</td>
                                                    <td>{{item.expire_date}}</td>
                                                    <td>
                                                <span ng-if="item.user_package_id==null">
                                                <a id="buttoncancel" ng-click="deleteItem($index)" class="btn btn-danger btn-xs">Cancel</a>
                                                <a id="buttonsuccess" ng-click="confirmAddOnPackage($index)" class="btn btn-success btn-xs">Confirm</a>
                                                </span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row" ng-repeat="ap in assigned_package_list">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <h4 style="border-bottom:1px solid #dedede;">Pairing ID: {{ap.pairing_id}} [ <small>No of days: {{ap.no_of_days}}</small> ] [ <small>Charge Type: {{((ap.charge_type=='1')?  'By Amount':'By Package')}}</small> ] </h4>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="lightgreen">Package Name</th>
                                                        <th class="lightgreen">Number Of Programs</th>
                                                        <th class="lightgreen">Start Date</th>
                                                        <th class="lightgreen">Expire Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="package in ap.packages">
                                                        <td>{{package.package_name}}</td>
                                                        <td>{{package.no_of_program}}</td>
                                                        <td>{{package.start_date}}</td>
                                                        <td>{{package.expire_date}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </div>
</div>