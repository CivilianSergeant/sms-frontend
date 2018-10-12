<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
    var user_type = "<?php echo $user_info->user_type; ?>";
</script>
<div id="container" ng-controller="ViewBankAccount" ng-cloak>


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
                    <h4 class="widgettitle"> View Bank Account

                        <a ng-show="permissions.edit_permission == '1' && hideFlag == 1 && account_details.length==0 && user_type == 'LCO'" href="<?php echo site_url('bank-accounts/edit/'.$token)?>" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-pencil"></i> Edit</a>
                        <a ng-if="permissions.edit_permission == '1' && user_type != 'LCO'" href="<?php echo site_url('bank-accounts/edit/'.$token)?>" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="<?php echo site_url('bank-accounts'); ?>"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">

                    <div class="col-md-5">
                        <label>Account Name :</label>
                        {{account.account_name}}
                    </div>
                    <div class="col-md-5">
                        <label>Account Number :</label>
                        {{account.account_no}}
                    </div>
                    <div class="col-md-5">
                        <label>Bank Name :</label>
                        {{account.bank_name}}
                    </div>
                    <div class="col-md-5">
                        <label>Is Shared :</label>
                        <span class="text-green">{{getStatus()}}</span>

                    </div>
                    <div class="col-md-12">
                        <label>Bank Address :</label>
                        {{account.address}}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default" ng-if="account_details.length>0">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> Shared Account Details</h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Account Number</th>
                                <th>LCO Name</th>
                                <th>Shared Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="detail in account_details">
                                <td>{{detail.account_no}}</td>
                                <td>{{detail.lco_name}}</td>
                                <td>{{detail.created_at}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>