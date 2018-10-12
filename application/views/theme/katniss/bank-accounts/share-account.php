<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="ShareBankAccount" ng-cloak>


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
                    <h4 class="widgettitle"> Share Bank Account
                        <a href="<?php echo site_url('bank-accounts'); ?>"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" name="addShareAccFrm" ng-submit="addShareAcc()">

                        <div class="form-group">
                            <label class="control-label col-md-3">Select LCO</label>
                            <div class="col-md-3">
                                <select kendo-combo-box
                                        k-placeholder="'Select LCO'"
                                        k-data-text-field="'lco_name'"
                                        k-data-value-field="'user_id'"

                                        k-data-source="lco_profiles"
                                        style="width: 100%" ng-model="lco_user_id" required="required" >
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Select Account</label>
                            <div class="col-md-3">
                                <select kendo-combo-box
                                        k-placeholder="'Select Account'"
                                        k-data-text-field="'account_no'"
                                        k-data-value-field="'account_no'"

                                        k-data-source="accounts"
                                        style="width: 100%" ng-model="account_no" required="required">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" ng-disabled="addShareAccFrm.$invalid" value="Add Share Account"/>
                            </div>
                        </div>

                        <div class="row">
                            <hr/>
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Bank Name</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>LCO</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr ng-repeat="item in accountShareList">
                                        <td>{{item.bank_name}}</td>
                                        <td>{{item.account_name}}</td>
                                        <td>{{item.account_no}}</td>
                                        <td>{{item.lco_name}}</td>
                                        <td>
                                            <span ng-if="!item.id">
                                            <a id="buttoncancel" ng-click="deleteItem($index)" class="btn btn-danger btn-xs">Delete</a>
                                            <a id="buttonsuccess" ng-click="confirmShareAccount($index)" class="btn btn-success btn-xs">Confirm</a>
                                            </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>