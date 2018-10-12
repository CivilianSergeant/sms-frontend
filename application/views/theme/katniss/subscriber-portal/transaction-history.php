<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
    var user_id = "<?php echo $user_info->id; ?>";
    var user_type = "<?php echo $user_info->user_type; ?>";
</script>
<div id="container" ng-controller="MyTransactions" ng-cloak>
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

                    <h4 class="widgettitle"> Transactions of Subscriber [<?php echo $subscriber_name; ?>]


                        <?php if(in_array($user_info->user_type,array('lco','LCO'))){ ?>
                            <a href="<?php echo site_url('subscriber/charge/'.$token.'/all'); ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-money"></i> Charge</a>
                            <a href="<?php echo site_url('subscriber/migrate/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-exchange"></i> Migrate</a>
                            <a href="<?php echo site_url('subscriber-recharge/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-usd"></i> Recharge</a>
                            <a ng-if="permissions.edit_permission=='1'" href="<?php echo site_url('subscriber/edit/{{profile.token}}'); ?>" class="btn btn-success btn-sm pull-right" style="margin-right: 10px"><i class="fa fa-pencil"></i> Edit</a>
                        <?php } ?>
                        <a href="<?php echo (isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'') ?>" class="btn btn-danger btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr style="border-color:#ddd;"/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="tab-content" ng-if="!loader">
                        <style type="text/css">
                            .profile{background: rgba(202, 255, 202, 0.15) none repeat scroll 0 0;border-radius: 5px;box-shadow: 0 0 2px;margin-bottom: 20px; }
                            hr {border-color: teal;}
                            .profile h4{color:#008000;}
                        </style>
                        <form class="form-horizontal" ng-submit="getStatements()">
                            <div class="col-md-2">
                                <select

                                    ng-model="formData.pairing_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Pairing ID'"
                                    k-data-text-field="'pairing_id'"
                                    k-data-value-field="'pairing_id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="pairings"
                                    style="width: 100%" ></select>

                            </div>
                            <div class="col-md-2" style="padding: 0px; margin-left: 15px">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text"
                                               ng-model="formData.from_date"
                                               placeholder="From Date"
                                               kendo-datepicker
                                               k-format="'yyyy-MM-dd'"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding: 0px; margin-left: 15px">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="text"
                                               ng-model="formData.to_date"
                                               placeholder="To Date"
                                               kendo-datepicker
                                               k-format="'yyyy-MM-dd'"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding: 0px; margin-left: 30px">
                                <div class="form-group">
                                    <div class="cold-md-12">
                                        <input type="submit" class="btn btn-primary" value="Search"/>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="row text-center" ng-show="loader">
                        <h3>Loading</h3>
                        <img  src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <table class="table table-bordered" ng-if="transactions.length>0">
                            <thead>
                            <tr>
                                <th class="lightgreen">SL</th>
                                <th class="lightgreen">Description</th>
                                <th class="lightgreen">Transaction Date</th>
                                <th class="lightgreen">Credit</th>
                                <th class="lightgreen">Debit</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="transaction in transactions">
                                <td>{{($index+1)}}</td>
                                <td>{{transaction.description}}</td>
                                <td>{{transaction.transaction_date}}</td>
                                <td>{{transaction.credit}}</td>
                                <td>{{transaction.debit}}</td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>{{totalCredit}}</strong></td>
                                <td><strong>{{totalDebit}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>Total Credit</strong></td>
                                <td><strong>{{totalCredit}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>Total Debit</strong><span class="pull-right"><strong>-</strong></span></td>
                                <td><strong>{{totalDebit}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"><strong>Total Balance</strong></td>
                                <td><strong>{{totalBalance}}</strong></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>







