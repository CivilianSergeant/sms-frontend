<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type= "<?php echo $user_info->user_type;  ?>";
    var user_id = "<?php echo $user_info->id; ?>";
    var subscriber_id = "<?php echo $subscriber_id; ?>";
</script>
<div id="container" ng-controller="BankPayment" ng-cloak>


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
                    <h4 class="widgettitle"> Bank Payment</h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" name="bankPayment" ng-submit="saveBankPayment()">
                        <div class="form-group">
                            <label class="control-label col-md-3">Bank Account <span class="text-danger">*</span></label>
                            <div class="col-md-4">
                                <select kendo-combo-box
                                        k-placeholder="'Select Account'"
                                        k-data-text-field="'name'"
                                        k-data-value-field="'id'"

                                        k-data-source="accounts"
                                        style="width: 100%" ng-model="formData.bank_account_id" required="required">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Subscriber <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <select
                                        ng-model="formData.subscriber_id"
                                        kendo-combo-box
                                        k-placeholder="'Select Subscriber'"
                                        k-data-text-field="'subscriber_name'"
                                        k-data-value-field="'user_id'"
                                        k-change="'getPairingId()'"
                                        k-data-source="subscribers"
                                        k-auto-bind="true"
                                        style="width: 100%"  ng-change="getPairingId()" required="required">
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Pairing ID <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <select kendo-combo-box
                                        k-placeholder="'Select Pairing ID'"
                                        k-data-text-field="'pairing_id'"
                                        k-data-value-field="'id'"
                                        k-change="'setPairingId()'"
                                        k-data-source="pairings"
                                        style="width: 100%" ng-model="formData.stb_card_id" ng-change="setPairingId()" required="required">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Payment type <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <select kendo-combo-box
                                        k-placeholder="'Select Type'"
                                        k-data-text-field="'payment_type'"
                                        k-data-value-field="'id'"

                                        k-data-source="payment_types"
                                        style="width: 100%" ng-model="formData.type_id" required="required">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Amount <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" ng-model="formData.amount" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Check No <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" ng-model="formData.check_no" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Transaction ID <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" ng-model="formData.transaction_id" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Depositor Name <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" ng-model="formData.depositor_name" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Depositor Phone <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" ng-model="formData.depositor_phone" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Deposit Date <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" kendo-datepicker k-format="'yyyy-MM-dd'" ng-model="formData.deposit_date" class="form-control" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" ng-disabled="checkValid()" value="Submit"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>