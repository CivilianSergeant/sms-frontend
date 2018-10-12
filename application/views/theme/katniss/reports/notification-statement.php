<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tfoot>tr>td,.table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>
<div id="container" ng-controller="notificationReport" ng-cloak>

    <?php if ($this->session->flashdata('success')) { ?>

    <div class="alert alert-success">
        <button class="close" aria-label="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('success') ?></p>
    </div>

    <?php } ?>
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


                    <h4 class="widgettitle"> Notification Report
                        <!--<a class="btn btn-primary btn-sm pull-right" href="<?php /*echo $_SERVER['REQUEST_URI']; */?>"><i class="fa fa-refresh"></i> Reset</a>-->
                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal">

                <div class="col-md-12">


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

                                       k-format="'yyyy-MM-dd'"
                                />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-12">
                    <div class="col-md-1" style="padding-left: 32px;">
                        <div class="form-group">
                            <div class="margin-bottom-sm">
                                <button class="btn btn-default" ng-click="getReports()"> Search </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div class="margin-bottom-sm">
                                <button class="btn btn-default" ng-click="refresh()"> Clear Search </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                        <hr/>
                    </div>
                </div>
                <div class="panel-body">
                     <div class="col-md-12">
                         <kendo-grid id="grid" options="mainGridOptions"></kendo-grid>
                    </div>
                </div>

        </form>
    </div>
</div>
</div>