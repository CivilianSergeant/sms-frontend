<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .table-bordered{border:1px solid;}
    .lightgreen{background: #92f4c7;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}


</style>
<div id="container" ng-controller="Backup" ng-cloak>


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

                    <h4 class="widgettitle">System Backup</h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="col-md-12" ng-if="!isOk">
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
                    </div>

                    <div class="col-md-12" ng-if="isOk">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="lightgreen">DateTime</th>
                                <th class="lightgreen">FileName</th>
                                <th class="lightgreen">Size</th>
                                <th class="lightgreen">
                                    <button ng-click="dump()" ng-if="!loader" class="btn btn-default pull-right">Backup</button>
                                    <div class="col-md-12 text-center" ng-if="loader">
                                        <span>Processing ... </span>
                                        <img src="<?php echo base_url('public/theme/katniss/img/loading_32.GIF');?>"/>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="file in files">
                                    <td>{{file.datetime}}</td>
                                    <td>{{file.filename}}</td>
                                    <td>{{file.size}}</td>
                                    <td>
                                        <a class="btn btn-success btn-xs" href="backup/download/{{file.filename}}"><i class="fa fa-download"></i> Download</a>
                                        <a class="btn btn-default btn-xs" href="backup/transfer-file/{{file.filename}}" title="FTP Transfer"><i class="fa fa-exchange"></i> Transfer</a>
                                        <a class="btn btn-danger btn-xs" ng-click="deleteItem(file)" title="Delete"><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>