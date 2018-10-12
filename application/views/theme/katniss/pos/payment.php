<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type= "<?php echo $user_info->user_type;  ?>";
    var user_id = "<?php echo $user_id; ?>";
    var subscriber_id = "<?php echo $subscriber_id; ?>";
</script>
<div id="container" ng-controller="PosPayment" ng-cloak>


     <div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>


    <div class="panel panel-default" >

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle">
                        POS Payment

                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="col-md-12">
                <div class="panel-body">
                    <div class="col-md-12">
                        <form class="form-horizontal" name="PosPayment" ng-submit="savePosPayment()">
                            <?php if($user_info->user_type == 'MSO'){ ?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Group <span class="text-danger">*</span></label>
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
                                        k-change = "'loadData()'"
                                        k-min-length="5"
                                        k-data-source="lco"

                                        style="width: 100%" required="required"></select>

                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="control-label col-md-3">Collector <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <select

                                        ng-model="formData.collector_id"
                                        kendo-combo-box
                                        k-placeholder="'Select Collector'"
                                        k-data-text-field="'name'"
                                        k-data-value-field="'id'"
                                        k-filter="'contains'"
                                        k-auto-bind="false"

                                        k-min-length="5"
                                        k-data-source="collectors"

                                        style="width: 100%" required="required"></select>

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
                                        k-filter="'contains'"
                                        k-auto-bind="true"
                                        k-change="'getPairingId()'"
                                        k-min-length="5"
                                        k-data-source="subscribers"

                                        style="width: 100%" required="required"></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pairing ID <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <select

                                        ng-model="formData.stb_card_id"
                                        kendo-combo-box
                                        k-placeholder="'Select Subscriber'"
                                        k-data-text-field="'pairing_id'"
                                        k-data-value-field="'id'"
                                        k-filter="'contains'"
                                        k-auto-bind="false"
                                        k-change="'setPairingId()'"
                                        k-min-length="5"
                                        k-data-source="pairings"

                                        style="width: 100%" required="required"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Pos Machine ID <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <select

                                        ng-model="formData.pos_machine_id"
                                        kendo-combo-box
                                        k-placeholder="'Select POS'"
                                        k-data-text-field="'pos_machine_id'"
                                        k-data-value-field="'id'"
                                        k-filter="'contains'"
                                        k-auto-bind="true"

                                        k-min-length="5"
                                        k-data-source="pos"

                                        style="width: 100%" required="required"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Date <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input kendo-datepicker k-format="'yyyy-MM-dd'" ng-model="formData.date" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Time <span class="text-danger">*</span></label>
                                <div class="col-md-2">
                                    <input type="text" ng-model="formData.time" class="form-control" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">TID <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.tid" class="form-control" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">MID <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.mid" class="form-control" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Invoice No <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.invoice_no" class="form-control" required="required"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Batch No <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.batch_no" class="form-control" required="required"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Card Last 4 digit <span class="text-danger">*</span></label>
                                <div class="col-md-2">
                                    <input type="text" maxlength="4" ng-model="formData.last_four" class="form-control" required="required"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Card Type <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <select

                                        ng-model="formData.card_type"
                                        kendo-combo-box
                                        k-placeholder="'Select Card Type'"
                                        k-data-text-field="'payment_type'"
                                        k-data-value-field="'id'"
                                        k-filter="'contains'"
                                        k-auto-bind="false"

                                        k-min-length="5"
                                        k-data-source="payment_types"

                                        style="width: 100%" required="required"></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Approval Code <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.approval_code" class="form-control" required="required"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">RPN <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.rpn" class="form-control" required="required"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Amount <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="formData.amount" class="form-control" required="required"/>
                                </div>
                            </div>

                            
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" ng-disabled="PosPayment.$invalid" value="Save POS"/>
                            </div>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>