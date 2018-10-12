<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var subscriber_id = "<?php echo $subscriber_id; ?>";
</script>
<div id="container" ng-controller="Billing" ng-cloak>

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
                    <h4 class="widgettitle"> Cash Receive </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="profile">
                        <form ng-submit="saveCashReceive()" name="saveCash" class="form-horizontal" method="post">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="money_receipt">Money Receipt Number <span style="color:red">*</span></label>                      
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="money_receipt" ng-model="cash.money_receipt"  placeholder="Enter Money Receipt Number" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                           <label  class="col-md-5  control-label" for="subscriber_id">Subscriber<span style="color:red">*</span></label>                      
                                           <div class="col-sm-7">
                                            <select
                                            
                                            ng-model="cash.subscriber_id"
                                            kendo-combo-box
                                            k-placeholder="'Select Subscriber'"
                                            k-data-text-field="'name'"
                                            k-data-value-field="'id'"
                                            k-filter="'contains'"
                                            k-auto-bind="false"
                                            k-change = "'getPairingId()'"
                                            k-min-length="5"
                                            k-data-source="subscriber"
                                            
                                            style="width: 100%" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group" ng-show="!isNaN(cash.subscriber_id) && cash.subscriber_id >0 ">
                                    <label  class="col-md-5  control-label" for="pairing_id">Pairing ID <span style="color:red">*</span></label>						
                                    <div class="col-sm-7" id="pairing_box">

                                        <select 

                                        ng-model="cash.stb_card_id"
                                        ng-change="setStbCardId()"
                                        kendo-combo-box
                                        k-placeholder="'Select Pairing ID'"
                                        k-data-text-field="'pair_id'"
                                        k-data-value-field="'row_id'"
                                        k-filter="'contains'"
                                        k-auto-bind="false"
                                        k-min-length="5"
                                        k-data-source="pair_row_id"
                                        style="width: 100%" required>
                                    </select>

                                </div>
                            </div> 

                            <div class="form-group">
                                <label class="col-md-5  control-label" for="receive_date">Collection date (From Client) <span style="color:red">*</span></label>						
                                <div class="col-sm-7">
                                    <input kendo-date-picker class="form-control" 
                                    ng-model="cash.receive_date"
                                    k-ng-model="dateObject"
                                    k-max = "'<?php echo date('Y-m-d'); ?>'"
                                    k-format="'yyyy-MM-dd'"
                                    style="width: 100%;" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-md-5  control-label" for="amount">Amount <span style="color:red">*</span></label>                       
                                <div class="col-sm-3">
                                    <input type="number" min="0" ng-model="cash.amount" id="amount" value="0" class="form-control" required/>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label" for="discount">Discount </label>                       
                                <div class="col-sm-3">
                                    <input type="number" min="0" ng-model="cash.discount" id="discount" value="0" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label" for="vat">VAT </label>                  
                                <div class="col-sm-2" style="padding-right: 0px">  
                                   <input type="number" min="0" ng-model="cash.vat_amount" id="vat" class="form-control"/>
                               </div>
                               <div class="col-sm-1" style="font-size: 22px; padding-top: 5px;">%</div>
                           </div>

                           <div class="form-group">
                            <label class="col-sm-5 control-label" for="total_amount">Total Amount</label>                       
                            <div class="col-sm-3">
                               <input type="number" readonly="readonly" ng-model="cash.total_amount" id="total_amount" class="form-control" required/>
                           </div>
                       </div>

                   </div>

                   <div class="col-md-6">							
                       <div class="form-group">
                        <label class="col-sm-5 control-label" for="collector_id">Collector Name <span style="color:red">*</span></label>						
                        <div class="col-sm-7">
                            <select class="form-control" ng-model="cash.collector_id" id="collector_id" required>
                                <option value="">--Select Collector--</option>
                                <?php foreach ($collectors as $value) { ?>                 
                                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>   
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <?php if(!empty($permissions) && ($permissions->create_permission == 1)){ ?>
            <div class="col-md-12">
                <hr/>
                <div class="col-md-12 text-right">
                    <input type="submit" ng-disabled="isDisabled()" class="btn btn-success" value="Save" />
                    <!-- <a id="buttoncancel" id="btnNext" ng-click="hideForm()"  class="btn btn-warning btnNext" >Cancel</a> -->
                </div>
            </div>
            <?php } ?>
        </div>
    </form> 
</div>
</div>
</div>
</div>
</div>
</div>








