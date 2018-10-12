<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var id = "<?php echo $id; ?>";
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
                    <h4 class="widgettitle"> View FTP Account

                        <a ng-show="permissions.edit_permission == '1'" href="<?php echo site_url('ftp-accounts/edit/'.$id)?>" class="btn btn-success btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="<?php echo site_url('ftp-accounts'); ?>"  class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="col-md-5">
                        <label>FTP Name :</label>
                        {{ftpAccount.name}}
                    </div>
                    <div class="col-md-5">
                        <label>Server IP :</label>
                        {{ftpAccount.server_ip}}
                    </div>
                    <div class="col-md-5">
                        <label>Server Port :</label>
                        {{ftpAccount.server_port}}
                    </div>
                    <div class="col-md-5">
                        <label>User ID :</label>
                        {{ftpAccount.user_id}}
                    </div>
                    <div class="col-md-5">
                        <label>Password :</label>
                        {{ftpAccount.password}}
                    </div>
                    <div class="col-md-5">
                        <label>Dir Location :</label>
                        {{ftpAccount.dir_location}}
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>