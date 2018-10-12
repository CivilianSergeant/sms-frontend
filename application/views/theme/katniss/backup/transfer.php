<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .table-bordered{border:1px solid;}
    .lightgreen{background: #92f4c7;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}


</style>
<script type="text/javascript">
    var file_name = "<?php echo $file_name; ?>";
</script>
<div id="container" ng-controller="BackupTransfer" ng-cloak>


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

                    <h4 class="widgettitle">Transfer System Backup</h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <!--<div class="col-md-12" ng-if="!isOk">
                        <form class="form-horizontal" ng-submit="checkPassword()">
                            <div class="form-group">
                                <label class="control-label col-md-4">Password</label>
                                <div class="col-md-2">
                                    <input type="password" class="form-control" ng-model="formData.password"/>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" value="Check" class="btn btn-success btn-sm"/>
                                </div>
                            </div>

                        </form>
                    </div>-->
                    <form class="form-horizontal" ng-submit="transferFile()">

                        <div class="form-group">
                            <label class="control-label col-md-3">Filename</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="<?php echo $file_name; ?>" readonly/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">File Size</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" value="<?php echo $size; ?>" readonly/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Date</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="<?php echo $datetime; ?>" readonly/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Select FTP Account</label>
                            <div class="col-md-3">
                                <select class="form-control" ng-model="transferData.ftp_account">
                                    <option ng-repeat="ftp in accounts" ng-selected="ftp.id==transferData.ftp_account" value="{{ftp.id}}">{{ftp.name}} [{{ftp.server_ip}}]</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3"><input type="checkbox" ng-model="transferData.run_as_background" ng-true-value="'1'" ng-false-value="'0'"/> Run as Background</label>
                        </div>
                        <div class="form-group" ng-if="!loader">
                            <div class="col-md-3 col-md-offset-3">
                                <input type="submit" class="btn btn-default" value="Transfer File"/>
                            </div>
                        </div>
                        <div class="form-group" ng-if="loader">
                            <div class="row text-center">
                                <h3>Transfering ...</h3>
                                <img  src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>