<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type= "<?php echo $user_info->user_type;  ?>";
    var user_id = "<?php echo $user_info->id; ?>";
    var subscriber_id = "<?php echo $subscriber_id; ?>";
</script>
<div id="container" ng-controller="BkashPayment" ng-cloak>


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
                    <h4 class="widgettitle"> Bkash Payment</h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" ng-submit="saveBkashPayment()">
                        <?php if($user_info->user_type == 'MSO'){ ?>
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
                                    k-auto-bind="true"
                                    k-change = "'loadSubscribers(formData.lco_id)'"
                                    k-min-length="5"
                                    k-data-source="lco"

                                    style="width: 100%"></select>

                            </div>
                        </div>
                        <?php } ?>
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
                                        k-auto-bind="true"
                                        k-data-source="subscribers"
                                        style="width: 100%"  required="required">
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Bkash Phone No</label>
                            <div class="col-md-3">
                                <input type="text" ng-model="formData.bkash_phone" maxlength="14" placeholder="+8801700000000" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bkash Transaction ID</label>
                            <div class="col-md-3">
                                <input type="number" ng-model="formData.bkash_transaction_id" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bkash Amount</label>
                            <div class="col-md-3">
                                <input type="number" ng-model="formData.bkash_amount" class="form-control"/>
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