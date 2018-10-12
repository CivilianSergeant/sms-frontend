<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var user_type= "<?php echo $user_info->user_type;  ?>";
</script>
<div id="container" ng-controller="FTPAccount" ng-cloak>


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
                    <h4 class="widgettitle"> Add FTP Account
                        <a ng-click="hideForm()"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" name="saveFTPAccFrm" ng-submit="saveFTPAccount()">
                        <div class="form-group">
                            <label class="control-label col-md-3">FTP Name <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="ftpAccount.name" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Server IP <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="ftpAccount.server_ip" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Server Port <span class="text-danger">*</span></label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" ng-model="ftpAccount.server_port" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">User ID <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="ftpAccount.user_id" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Password <span class="text-danger">*</span></label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" ng-model="ftpAccount.password" required="required"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Directory Location</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" ng-model="ftpAccount.dir_location" /> <small>(Optional)</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-success" ng-disabled="saveFTPAccFrm.$invalid" value="Save FTP Account"/>
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

                    <h4 class="widgettitle"> FTP Account List

                        <a ng-if="permissions.create_permission=='1'" ng-click="showForm()" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add FTP Account</a>
                    </h4>

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="col-md-12" ng-if="!delete_item">
                <div class="panel-body">
                    <kendo-grid options="mainGridOptions" id="accountGrid"></kendo-grid>
                </div>
            </div>

            <div class="col-md-12 text-center" ng-show="delete_item">
                <form>
                    <p><strong>Are you sure to delete this FTP Account</strong></p>
                    <p>
                        <input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
                        <input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

