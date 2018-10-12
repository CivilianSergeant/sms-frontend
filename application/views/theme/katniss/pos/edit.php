<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
    var user_id = "<?php echo $user_id; ?>";
</script>
<div id="container" ng-controller="PosEdit" ng-cloak>


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

                    <h4 class="widgettitle">
                        POS
                        <a href="<?php echo site_url('pos-settings/view/' . $token); ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
                        <a id="buttoncancel" href="<?php echo site_url('pos-settings'); ?>" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="col-md-12">
                <div class="panel-body">
                    <div class="col-md-12">
                        <form class="form-horizontal" name="posSettings" ng-submit="updatePOS()">
                            <div class="form-group">
                                <label class="control-label col-md-3">Bank Account <span class="text-danger">*</span></label>
                                <div class="col-md-5">
                                    <select kendo-combo-box
                                            k-placeholder="'Select Bank'"
                                            k-data-text-field="'name'"
                                            k-data-value-field="'id'"
                                            k-data-source="accounts"
                                            style="width: 100%" ng-model="pos.bank_account_id"
                                            required="required"
                                    ></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Collector <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <select kendo-combo-box
                                            k-placeholder="'Select Collector'"
                                            k-data-text-field="'name'"
                                            k-data-value-field="'id'"
                                            k-data-source="collectors"
                                            style="width: 100%" ng-model="pos.collector_id"
                                            required="required"
                                    ></select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">POS Machine No <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="pos.pos_machine_id" class="form-control" required="required" readonly/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Charge Interest</label>
                                <div class="col-md-3">
                                    <input type="text" ng-model="pos.charge_interest" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" ng-disabled="posSettings.$invalid" value="Save POS"/>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>