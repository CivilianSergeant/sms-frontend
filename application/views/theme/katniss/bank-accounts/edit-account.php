<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="EditBankAccount" ng-cloak>


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
                    <h4 class="widgettitle"> Edit Bank Account
                        <a href="<?php echo site_url('bank-accounts/view/'.$token)?>" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-search"></i> View</a>

                        <a href="<?php echo site_url('bank-accounts'); ?>"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" name="saveBankAccFrm" ng-submit="updateBankAccount()">

                        <div class="form-group">
                            <label class="control-label col-md-3">Account Number <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" readonly="readonly" ng-model="bankAccount.account_no" required="required"/>
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
</div>