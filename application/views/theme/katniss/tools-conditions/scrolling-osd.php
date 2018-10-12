<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var sign = "<?php echo $sign; ?>";
</script>
<div id="container" ng-controller="conditionalScrolling" ng-cloak>


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


                    <h4 class="widgettitle"> Conditional Scrolling OSD

                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal" ng-submit="sendConditionalScrolling()">
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
                                <div class="col-md-5">
                                    <label style="margin-right:5px;"><input type="radio" ng-disabled="disabledSubscriber()" ng-model="address_by" value="CARD"/>&nbsp;&nbsp;<span style="position:relative;top:-2px;">Card ID</span></label>
                                    <label><input type="radio" ng-disabled="disabledSubscriber()" ng-model="address_by" value="STB"/>&nbsp;&nbsp;<span style="position:relative;top:-2px;">STB ID</span></label>
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

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr/>
            </div>
            <div class="col-md-9" style="margin-left:-6px">

                   <div class="form-group">
                       <label class="control-label col-md-3 col-md-offset-1">Pre-defined Settings</label>
                       <div class="col-md-3">
                           <select
                               ng-model="settings_id"
                               kendo-combo-box
                               k-placeholder="'Select Settings'"
                               k-data-text-field="'name'"
                               k-data-value-field="'id'"
                               k-filter="'contains'"
                               k-auto-bind="true"
                               k-min-length="5"
                               k-data-source="settings"
                               ng-change="setSettings()";
                               style="width: 100%">

                           </select>
                       </div>
                       <div class="col-md-3">
                           <input type="checkbox" ng-model="showAdvanceForm"/> <span style="position:relative;top:-2px;">Show More</span>
                       </div>
                   </div>
                    <div class="form-group" ng-show="settings_id== '-1'">
                        <label class="control-label col-md-3 col-md-offset-1">Settings Name</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" ng-model="settings_name"/>
                        </div>
                    </div>

                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Priority</label>
                        <div class="col-md-3">
                            <select
                                ng-model="priority_id"
                                kendo-combo-box
                                k-placeholder="'Select Priority'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-filter="'contains'"
                                k-auto-bind="true"
                                k-min-length="5"
                                k-data-source="priorities"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Display Position</label>
                        <div class="col-md-3">
                            <select
                                ng-model="position_id"
                                kendo-combo-box
                                k-placeholder="'Select Position'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-filter="'contains'"
                                k-auto-bind="true"
                                k-min-length="5"
                                k-data-source="positions"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Size</label>
                        <div class="col-md-3">
                            <select
                                ng-model="size_id"
                                kendo-combo-box
                                k-placeholder="'Select Size'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-filter="'contains'"
                                k-auto-bind="true"
                                k-min-length="5"
                                k-data-source="sizes"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Type</label>
                        <div class="col-md-3">
                            <select
                                ng-model="type_id"
                                kendo-combo-box
                                k-placeholder="'Select Type'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-filter="'contains'"
                                k-auto-bind="true"
                                k-min-length="5"
                                k-data-source="types"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Color Type</label>
                        <div class="col-md-3" ng-init="color_type_id=3">
                            <select
                                ng-model="color_type_id"
                                kendo-combo-box
                                k-placeholder="'Select Type'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-auto-bind="true"
                                k-filter="'contains'"
                                k-data-source="color_types"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>



                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Font</label>
                        <div class="col-md-3">
                            <select
                                ng-model="font_id"
                                kendo-combo-box
                                k-placeholder="'Select Font'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-filter="'contains'"
                                k-data-source="fonts"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group" ng-show="showAdvanceForm">
                        <label class="control-label col-md-3 col-md-offset-1">Back Color </label>
                        <div class="col-md-3" ng-init="back_color_id=-2139062017">
                            <select
                                ng-model="back_color_id"
                                kendo-combo-box
                                k-placeholder="'Select Font'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-data-source="back_colors"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-md-offset-1">Display Times</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" ng-model="display_times"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-md-offset-1">Content</label>
                        <div class="col-md-6">
                            <textarea class="form-control" ng-model="content"></textarea>
                        </div>
                    </div>

                   
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-6 col-md-offset-4">
                                <input type="submit" class="btn btn-primary" value="Submit"/>
                            </div>
                        </div>
                    </div>

            </div>
            </form>
        </div>
    </div>
</div>