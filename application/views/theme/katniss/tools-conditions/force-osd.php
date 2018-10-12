<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var sign = "<?php echo $sign; ?>";
</script>
<div id="container" ng-controller="conditionalForceOSD" ng-cloak>


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


                    <h4 class="widgettitle"> Force OSD

                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal" ng-submit="sendForceOSD()">
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
                <div class="col-md-12"><h5>Advance Settings</h5></div>

            </div>
            <div class="col-md-6" style="margin-left:-6px;margin-top:20px;">

                   <div class="form-group">
                       <label class="control-label col-md-4 col-md-offset-1">Screen Ratio</label>
                       <div class="col-md-3">
                           <input kendo-numeric-text-box k-min="0" k-max="100" k-format="'n0'" k-ng-model="screen_ratio" style="width: 100%;" />
                       </div>
                   </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Show Duration</label>
                        <div class="col-md-3">
                            <input kendo-numeric-text-box k-min="0" k-max="100" k-format="'n0'" k-ng-model="show_duration" style="width: 100%;" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Stop Duration</label>
                        <div class="col-md-3">
                            <input kendo-numeric-text-box k-min="0" k-max="100" k-format="'n0'" k-ng-model="stop_duration" style="width: 100%;" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Content</label>
                        <div class="col-md-6">
                            <textarea class="form-control" rows="5" ng-model="content"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Font Size</label>
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

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Font Type</label>
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

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Color Type</label>
                        <div class="col-md-4">
                            <select
                                ng-model="color_type_id"
                                kendo-combo-box
                                k-placeholder="'Select Type'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"

                                k-filter="'contains'"
                                k-data-source="color_types"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Font Color</label>
                        <div class="col-md-3">
                            <select
                                ng-model="font_id"
                                kendo-combo-box
                                k-placeholder="'Select Font'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-filter="'contains'"
                                k-auto-bind="true"
                                k-min-length="5"
                                k-data-source="fonts"
                                style="width: 100%">

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Background Color</label>
                        <div class="col-md-4">
                            <select
                                ng-model="back_color_id"
                                kendo-combo-box
                                k-placeholder="'Select Font'"
                                k-data-text-field="'name'"
                                k-data-value-field="'value'"
                                k-data-source="back_colors"
                                k-index="back_color_id"
                                style="width: 100%">
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-md-offset-1">Transparency</label>
                        <div class="col-md-3">
                            <input kendo-numeric-text-box k-min="0" k-max="100" k-format="'n0'" k-ng-model="transparency" style="width: 100%;" />
                        </div>
                    </div>








            </div>
                <div class="col-md-5">
                    <div class="form-group" style="margin-bottom:10px;">

                        <div class="col-md-5">
                            <label class="control-label col-md-12" style="text-align:left;">Program List</label>
                            <div class="col-md-12">
                                <select id="select-from" ng-model="selected_item"  style="min-width:145px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top:30px;">
                            <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                            <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                        </div>
                        <div class="col-md-5">
                            <label class="control-label col-md-12" style="text-align:left;">Included List</label>
                            <div class="col-md-12">
                                <select id="select-from" ng-model="included_item" style="min-width:145px;min-height:190px;" multiple="multiple" >
                                    <option ng-repeat="p in assigned_programs"  style="font-size:13px" value="{{p.id}}" >{{p.program_name}}</option>
                                </select>
                            </div>
                        </div>
                        <!--<div class="col-md-12 col-md-offset-6" style="padding-left:0px;">
                            Total : {{assigned_programs.length}} [max: 190]
                        </div>-->
                    </div>
                </div>
                <div class="col-md-12">
                    <hr/>
                </div>
                <div class="form-group">

                        <div class="col-md-5 col-md-offset-2">
                            <input type="submit" class="btn btn-primary" value="Submit"/>
                        </div>

                </div>
            </form>
        </div>
    </div>
</div>