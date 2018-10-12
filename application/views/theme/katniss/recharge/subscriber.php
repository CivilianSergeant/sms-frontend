<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
 var id = '<?php echo $subscriber_user["id"]; ?>';
 var segment = '<?php echo $segment; ?>';
</script>
<div id="container" ng-controller="RechargeSubscriber" ng-cloak>

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
                    
                    <h4 class="widgettitle"> 
                        Recharge Subscriber Account
                        <!-- <a id="buttoncancel" ng-click="hideForm()" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>
                        
                 
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" ng-submit="accountRecharge()">
                    <!-- <div class="form-group">
                        <label class="control-label col-md-2">Subscriber</label>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option value="">--Select Subscriber--</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="control-label col-md-2">Subscriber</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" disabled value="<?php echo $subscriber['subscriber_name']; ?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2">Payment Method</label>
                        <div class="col-md-3">
                            <select class="form-control" ng-model="recharge.payment_method_id">
                                <option value="">--Select Payment Method--</option>
                                <option ng-repeat="item in payment_methods" ng-value="{{item.id}}">{{item.method}}</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="control-label col-md-2">Amount</label>
                        <div class="col-md-2">
                            <input type="number" name="number" ng-model="recharge.amount" class="form-control"/>
                        </div>
                    </div> -->
                    <div class="form-group">
                        
                        <div class="col-md-3 col-md-offset-2">
                            <input type="submit" class="btn btn-success" value="Recharge" />
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>