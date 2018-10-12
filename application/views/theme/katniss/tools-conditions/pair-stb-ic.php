<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var sign = "<?php echo $sign; ?>";
</script>
<div id="container" ng-controller="pairStbIc" ng-cloak>


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


                    <h4 class="widgettitle"> Pair STB & IC

                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal" ng-submit="sendConditionalPairing()">
            <div class="col-md-12">

                <div class="col-md-7">

                        <div class="form-group">
                            <!--<input type="radio" class="col-md-2" ng-checked="broadcast_type == 'LCO'" ng-model="broadcast_type" value="LCO"/>-->
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

                            <label class="control-label col-md-3">
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

                                <label class="control-label col-md-3" >
                                    Condition
                                </label>
                                <div class="control-label col-md-3" style="text-align:left;">
                                    <label style="margin-right:5px;"><input type="radio"  ng-model="condition" value="1"/>&nbsp;&nbsp;<span style="position:relative;top:-2px;">Pair</span></label>
                                    <label><input type="radio"  ng-model="condition" value="0"/>&nbsp;&nbsp;<span style="position:relative;top:-2px;">Un-pair</span></label>
                                </div>

                        </div>



                </div>

            </div>
            <div class="col-md-9" style="margin-left:-6px">



                    <!-- <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-4">Title</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" maxlength="15" name="title"/>
                                <small>(Maximum 15 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-4">Message</label>
                            <div class="col-md-8">
                                <textarea rows="8" cols="7" class="form-control" maxlength="400" name="content"></textarea>
                                <small>(Maximum 400 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-4">Sign</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" maxlength="15" name="title"/>
                                <small>(Maximum 15 characters)</small>
                            </div>
                        </div>
                    </div> -->
                    <div class="form-group">

                            <div class="col-md-3" style="margin-left:180px;">
                                <input type="submit" class="btn btn-primary" value="Submit"/>
                            </div>

                    </div>


            </div>
            </form>
        </div>
    </div>
</div>