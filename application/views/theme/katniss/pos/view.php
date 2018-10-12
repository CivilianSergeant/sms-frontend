<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
    var user_id = "<?php echo $user_id; ?>";
</script>
<div id="container" ng-controller="PosEdit" ng-cloak>


     <!--<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>-->


    <div class="panel panel-default">

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle">
                        POS
                        <a ng-if="permissions.edit_permission=='1'" href="<?php echo site_url('pos-settings/edit/' . $token); ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>
                        <a id="buttoncancel" href="<?php echo site_url('pos-settings'); ?>" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="col-md-12">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-3">Bank Account </label>
                                <div class="col-md-5" style="line-height:34px;">

                                    {{showBankAccount()}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Collector </label>
                                <div class="col-md-3" style="line-height:34px;">

                                    {{showCollector()}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">POS Machine No <span class="text-danger">*</span></label>
                                <div class="col-md-3" style="line-height:34px;">
                                    {{pos.pos_machine_id}}                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Charge Interest</label>
                                <div class="col-md-3" style="line-height:34px;">
                                    {{pos.charge_interest}}
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

</div>