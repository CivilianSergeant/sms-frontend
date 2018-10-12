<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type= "<?php echo $user_info->user_type;  ?>";
</script>
<div id="container" ng-controller="BankAccount" ng-cloak>


    <div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>


    <div class="panel panel-default" ng-show="showFrm">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> Add Bank Account
                        <a ng-click="hideForm()"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" name="saveBankAccFrm" ng-submit="saveBankAccount()">

                        <div class="form-group">
                            <label class="control-label col-md-3">Account Number <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="bankAccount.account_no" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Account Name <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="bankAccount.account_name" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bank Name <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="bankAccount.bank_name" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Bank Address</label>
                            <div class="col-md-6">
                                <textarea ng-model="bankAccount.address" cols="30" rows="5" style="resize:none" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" ng-disabled="saveBankAccFrm.$invalid" value="Save Account"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default" ng-show="!showFrm">

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> Bank Account List

                        <a ng-if="permissions.create_permission=='1'" ng-click="showForm()" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add Bank Account</a>
                    </h4>

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <kendo-grid options="mainGridOptions" id="accountGrid"></kendo-grid>
                </div>
            </div>
        </div>
    </div>
</div>

