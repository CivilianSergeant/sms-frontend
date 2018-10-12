<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var id = "<?php echo $id; ?>";
</script>
<div id="container" ng-controller="EditFTPAccount" ng-cloak>


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
                    <h4 class="widgettitle"> Edit FTP Account
                        <a href="<?php echo site_url('ftp-accounts/view/'.$id)?>" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-search"></i> View</a>

                        <a href="<?php echo site_url('ftp-accounts'); ?>"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form class="form-horizontal" name="saveFTPAccFrm" ng-submit="updateFTPAccount()">
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
                                <input type="submit" class="btn btn-success" ng-disabled="saveFTPAccFrm.$invalid" value="Update Account"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>