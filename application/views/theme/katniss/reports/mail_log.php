<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="mailLog" ng-cloak>

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


                    <h4 class="widgettitle"> Condition Log
                        <a class="btn btn-primary btn-sm pull-right" href="<?php echo $_SERVER['REQUEST_URI']; ?>"><i class="fa fa-refresh"></i> Reset</a>
                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>
                    

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal">
                <div class="col-md-12">
                    <label class="col-md-2" style="width:12%;">Condition Type</label>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select ng-model="type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="Mail">Conditional Mail</option>
                                    <option value="Search">Conditional Search</option>
                                    <option value="Scrolling">Scrolling OSD</option>
                                    <option value="Force">Force OSD</option>
                                    <option value="Limited">Conditional Limited</option>
                                    <option value="ECM">Ecm Fingerprint</option>
                                    <option value="EMM">Emm Fingerprint</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <label class="col-md-1" >Status</label>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select ng-model="status" class="form-control">
                                <option value="">--Select Status--</option>
                                <option value="expired">Expired</option>
                                <option value="not_expired">Not Expired</option>
                                <option value="active">Active</option>
                                <option value="stopped">Stopped</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="lco_id"
                                kendo-combo-box
                                k-placeholder="'Select LCO'"
                                k-data-text-field="'lco_name'"
                                k-data-value-field="'user_id'"
                                k-filter="'contains'"
                                k-auto-bind="false"
                                k-change = "'loadSubscriber()'"
                                k-min-length="5"
                                k-data-source="lco"                                
                                style="width: 100%"></select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="subscriber_id"
                                kendo-combo-box
                                k-placeholder="'Select Subscriber'"
                                k-data-text-field="'subscriber_name'"
                                k-data-value-field="'user_id'"
                                k-filter="'contains'"
                                k-auto-bind="false"
                                k-change = "'loadCards()'"
                                k-min-length="5"
                                k-data-source="subscribers"                               
                                style="width: 100%" ></select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 0px; margin-left: 15px">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="stb"
                                kendo-combo-box
                                k-placeholder="'Select STB'"
                                k-data-text-field="'external_card_number'"
                                k-data-value-field="'external_card_number'"
                                k-filter="'contains'"
                                k-min-length="5"
                                k-data-source="stbcards"                               
                                style="width: 100%" ></select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-left: 30px; padding: 0px">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="smart_card"
                                kendo-combo-box
                                k-placeholder="'Select Card'"
                                k-data-text-field="'external_card_number'"
                                k-data-value-field="'external_card_number'"
                                k-filter="'contains'"
                                k-min-length="5"
                                k-data-source="cards"                               
                                style="width: 100%" ></select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding-left: 35px;">
                        <div class="form-group">                    
                            <div class="margin-bottom-sm">
                                <button class="btn btn-default" ng-click="getLogs()"> Search </button>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="panel-body">
                     <div class="col-md-12">
                        <kendo-grid id="grid" options="mainGridOptions"></kendo-grid>
                    </div>
                </div>
                
        </form>
    </div>
</div>
</div>