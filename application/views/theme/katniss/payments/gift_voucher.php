<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type= "<?php echo $user_info->user_type;  ?>";
    var user_id = "<?php echo $user_info->id; ?>";
</script>
<div id="container" ng-controller="OnlinePayment" ng-cloak>


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
                    <h4 class="widgettitle"> Gift Voucher</h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">Group</label>
                            <div class="col-md-3">
                                <select
                                    ng-disabled="disableLco()"
                                    ng-model="formData.lco_id"
                                    kendo-combo-box
                                    k-placeholder="'Select LCO'"
                                    k-data-text-field="'lco_name'"
                                    k-data-value-field="'user_id'"
                                    k-filter="'contains'"
                                    k-auto-bind="false"
                                    k-change = "'loadSubscribers()'"
                                    k-min-length="5"
                                    k-data-source="lco"

                                    style="width: 100%"></select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Subscriber</label>
                            <div class="col-md-3">
                                <select
                                        ng-model="formData.subscriber_id"
                                        kendo-combo-box
                                        k-placeholder="'Select Subscriber'"
                                        k-data-text-field="'subscriber_name'"
                                        k-data-value-field="'user_id'"
                                        k-change="'getPairingId()'"
                                        k-auto-bind="false"
                                        k-data-source="subscribers"
                                        style="width: 100%"  ng-change="getPairingId()" required="required">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">

                            <label class="control-label col-md-3">
                                Subscriber Pairings
                            </label>
                            <div class="col-md-3">
                                <select
                                    ng-disabled="disabledSubscriber()"
                                    ng-model="formData.pairing_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Pairing ID'"
                                    k-data-text-field="'pairing_id'"
                                    k-data-value-field="'id'"
                                    k-filter="'contains'"
                                    k-auto-bind="false"
                                    k-min-length="5"
                                    k-data-source="pairings"

                                    style="width: 100%" ></select>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Serial</label>
                            <div class="col-md-3">
                                <input type="number" ng-model="formData.serial" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Card no</label>
                            <div class="col-md-3">
                                <input type="number" ng-model="formData.card_no" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" value="Make Payment"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>