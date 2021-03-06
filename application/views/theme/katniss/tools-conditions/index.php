<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
var sign = "<?php echo $sign; ?>";
</script>
<div id="container" ng-controller="conditionalMail" ng-cloak>

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


                    <h4 class="widgettitle"> Conditional Mail

                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>
                    

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal" ng-submit="sendConditionalMail()">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="col-md-7">
                        
                            <div class="form-group">
                                <input type="radio" class="col-md-2" ng-checked="broadcast_type == 'LCO'" ng-model="broadcast_type" value="LCO"/>
                                <label class="control-label col-md-3 ">
                                     Group
                                </label>
                                <div class="col-md-6">
                                    <select
                                    ng-disabled="disableLco()"            
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

                            <div class="form-group">
                                <input type="radio" class="col-md-2" ng-checked="broadcast_type == 'SUBSCRIBER'" ng-model="broadcast_type" value="SUBSCRIBER"/>
                                <label class="control-label col-md-3">
                                    Subscriber
                                </label>
                                <div class="col-md-6">
                                    <select
                                    ng-disabled="disabledSubscriber()"            
                                    ng-model="subscriber_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Subscriber'"
                                    k-data-text-field="'subscriber_name'"
                                    k-data-value-field="'user_id'"
                                    k-filter="'contains'"
                                    k-auto-bind="false"
                                    k-change = "'loadPairings()'"
                                    k-min-length="5"
                                    k-data-source="subscribers"
                                    
                                    style="width: 100%" ></select>
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                
                                <label class="control-label col-md-3 col-md-offset-2">
                                    Subscriber Pairings 
                                </label>
                                <div class="col-md-6">
                                    <select
                                    ng-disabled="disabledSubscriber()"            
                                    ng-model="pairing_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Pairing ID'"
                                    k-data-text-field="'pairing_id'"
                                    k-data-value-field="'id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="pairings"
                                    
                                    style="width: 100%" ></select>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-3">
                                    <label class="control-label col-md-3" style="margin-left:-10px">
                                        Address By
                                    </label>
                                    <div class="col-md-6">
                                        <label style="margin-right:5px;"><input type="radio" ng-disabled="disabledSubscriber()" ng-model="address_by" value="CARD"/>&nbsp;&nbsp;<span style="position:relative;top:-2px;">Card ID</span></label>
                                        <label><input type="radio" ng-disabled="disabledSubscriber()" ng-model="address_by" value="STB"/>&nbsp;&nbsp;<span style="position:relative;top:-2px;">STB  ID</span></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="radio" class="col-md-2" ng-checked="broadcast_type == 'BUSINESS_REGION'" ng-model="broadcast_type" value="BUSINESS_REGION"/>
                                <label class="control-label col-md-3">
                                    Business Region
                                </label>
                                <div class="col-md-7">
                                    <select        
                                    ng-disabled="disabledBroadCast()"  
                                    ng-model="business_region_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Business Region'"
                                    k-data-text-field="'name'"
                                    k-data-value-field="'hex'"
                                    k-filter="'contains'"
                                    k-auto-bind="false"
                                    k-change = "'getPairingId()'"

                                    k-min-length="5"
                                    k-data-source="regions"
                                    style="width: 100%"></select>
                                </div>
                            </div>
                        
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="col-md-3">From Date</label>
                            <div class="col-md-5">
                                <input kendo-date-picker class="form-control" 

                                k-ng-model="from_day"
                                k-value="from_day"
                                k-min="minDate"
                                k-format="'yyyy-MM-dd HH:mm:ss'"
                                style="width: 100%;" required/>

                            </div>
                            <!--<div class="col-md-4">
                            <input kendo-time-picker
                                   ng-model="start_time_min"
                                   k-min="start_time_min"
                                   k-max="start_time_max"
                                   k-ng-model="startTimeObj"
                                   style="width: 100%;" />
                            </div>-->
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">To Date</label>
                            <div class="col-md-5">
                                <input kendo-date-picker class="form-control"
                                k-ng-model="end_day"
                                k-value="end_day"
                                k-min="end_day"
                                k-format="'yyyy-MM-dd 23:59:59'"
                                style="width: 100%;" required/>

                            </div>
                            <!--<div class="col-md-4">
                                <input kendo-time-picker
                                       ng-model="end_time_min"
                                       k-min="end_time_min"

                                       k-ng-model="endTimeObj"
                                       style="width: 100%;" />
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>
            <div class="col-md-9" style="margin-left:-6px">
                
                    
                    
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-4">Title</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" maxlength="15" ng-model="title"/>
                                <small>(Maximum 15 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-4">Message</label>
                            <div class="col-md-8">
                                <textarea rows="8" cols="7" class="form-control" maxlength="400" ng-model="content"></textarea>
                                <small>(Maximum 400 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-4">Sign</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" maxlength="15" ng-model="sign" />
                                <small>(Maximum 15 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-6 col-md-offset-4">
                                <input type="submit" class="btn btn-primary" value="Send Mail"/>
                            </div>
                        </div>
                    </div>

                
            </div>
            </form>
        </div>
    </div>
</div>