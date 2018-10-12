<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .table-bordered{border:1px solid;}
    .lightgreen{background: #92f4c7;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}

</style>
<div id="container" ng-controller="ownershipTransfer" ng-cloak>

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
                        Ownership Transfer

                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" ng-submit="transfer()">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12" >
                                <select
                                    ng-model="formData.old_subscriber_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Old Subscriber'"
                                    k-data-text-field="'subscriber_name'"
                                    k-data-value-field="'user_id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="subscribers"
                                    k-change="'loadPairings()'"
                                    style="width: 100%" >
                                </select>
                                <label style="margin-top:10px;">Parked Pairing ID</label>
                                <select id="select-from" ng-model="selected_item"  style="width:240px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in pairings"  style="font-size:13px" value="{{p.id}}" >{{p.pairing_id}}</option>
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
                                <select
                                    ng-model="formData.new_subscriber_id"
                                    kendo-combo-box
                                    k-placeholder="'Select New Subscriber'"
                                    k-data-text-field="'subscriber_name'"
                                    k-data-value-field="'user_id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="subscribers"
                                    style="width: 100%" >
                                </select>
                                <label style="margin-top:10px;">Assigned Pairing ID</label>
                                <select id="select-from" ng-model="included_item" style="width:240px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in assigned_pairings"  style="font-size:13px" value="{{p.id}}" >{{p.pairing_id}}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-primary" value="Transfer"/>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


</div>