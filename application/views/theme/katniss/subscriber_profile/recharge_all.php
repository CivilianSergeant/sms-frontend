<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type = "<?php echo $user_info->user_type; ?>";
    var user_id = "<?php echo $user_info->id; ?>";
</script>
<div id="container" ng-controller="RechargeAll" ng-cloak>


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
                    <h4 class="widgettitle"> Bulk Recharge
                        <a href="<?php echo site_url('subscriber'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right" style="margin-right: 10px"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" ng-submit="rechargeAll()">
                        <div class="form-group">
                            <label class="control-label col-md-3">MSO</label>
                            <div class="col-md-3">
                                <select class="form-control">
                                    <option>MSO</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Recharge Filter</label>
                            <div class="col-md-3">
                                <select class="form-control" ng-model="formData.recharge_filter">
                                    <option value="1" ng-selected="1">All</option>
                                    <option value="2">Zero Balance Only</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-3">
                                <input id="is_cache_received" type="checkbox" ng-model="formData.is_cache_received" value="1" />
                                <label class="control-label" style="position: absolute;margin-top: -5px;left: 35px" for="is_cache_received">Is Cache Received</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Amount</label>
                            <div class="col-md-3">
                                <input type="number" ng-model="formData.amount" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" value="Recharge All"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>