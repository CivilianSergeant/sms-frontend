<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .table-bordered{border:1px solid;}
    .lightgreen{background: #92f4c7;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}

</style>
<div id="container" ng-controller="assignFromParking" ng-cloak>

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
                    
                    <h4 class="widgettitle"> 
                        Assign From Parking
                        
                    </h4>
                        
                 
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" ng-submit="addToList()">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select
                                    ng-model="formData.pairing_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Parked Pairing ID'"
                                    k-data-text-field="'pairing_id'"
                                    k-data-value-field="'id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="parks"
                                    style="width: 100%" >

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                            <select

                                ng-model="formData.subscriber_id"
                                kendo-combo-box
                                k-placeholder="'Select Subscriber'"
                                k-data-text-field="'subscriber_name'"
                                k-data-value-field="'user_id'"
                                k-filter="'contains'"
                                k-min-length="5"
                                k-data-source="subscribers"

                                style="width: 100%" ></select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Add To List"/>
                        </div>
                    </div>
                </form>
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="lightgreen">Subscriber Name</th>
                                <th class="lightgreen">Pairing ID</th>
                                <th class="lightgreen">STB ID</th>
                                <th class="lightgreen">Card ID</th>
                                <th class="lightgreen">Parking Date</th>
                                <th class="lightgreen">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="p in parkings">
                                <td>{{p.subscriber_name}}</td>
                                <td>{{p.pairing_id}}</td>
                                <td>{{p.external_card_number}}</td>
                                <td>{{p.internal_card_number}}</td>
                                <td>{{p.parking_date}}</td>
                                <td>
                                   <span ng-if="p.log_id == undefined">
                                        <a ng-click="reassign($index)" class="btn btn-success btn-sm" >Re-assign</a>
                                        <a ng-click="cancel($index)" class="btn btn-danger btn-sm" >Cancel</a>
                                   </span>
                                    <span ng-if="p.log_id != undefined">
                                        <strong class="text-green">Re-Assigned</strong>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>