<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="PackageMigrate" ng-cloak>

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
                    
                    <h4 class="widgettitle"> 
                        Migrate Subscriber Account [ <?php echo $subscriber->get_attribute('subscriber_name'); ?> ] 
                        <span>[Available Balance: 
                                        <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                        <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                        ]</span>
                        <a href="<?php echo site_url('subscriber'); ?>" id="buttoncancel" class="btn btn-primary btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                        
                 
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="row" ng-if="formData.pairing_id">
                    <div class="col-md-12 text-center">
                        <p><strong>Are you sure to migrate for {{formData.pairing_id}}?</strong></p>
                        <div class="row text-center" ng-show="loader">
                            <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                        </div>
                        <div ng-show="!loader">
                            <p>{{message}}</p>
                            <p>
                                <a ng-click="confirm()" class="btn btn-success btn-sm">Confirm</a>
                                <a ng-click="cancel()" class="btn btn-danger btn-sm">Cancel</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row" ng-repeat="ap in assigned_packages" ng-if="!formData.pairing_id">
                        
                        <div class="col-md-12">    
                            <div class="col-md-12">
                                <h4 style="border-bottom:1px solid #dedede;">Device ID: {{ap.pairing_id}} [ <small>No of days: {{ap.no_of_days}}</small> ] [ <small>Charge Type: {{((ap.charge_type=='1')?  'By Amount':'By Package')}}</small> ]
                                [ <small>Total Price: {{ap.total_price}}</small> ] 
                                    <a ng-click="migrate(ap)" class="btn btn-danger btn-sm pull-right">Migrate</a>
                                </h4>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="lightgreen">Package Name</th>
                                            <th class="lightgreen">Package Price</th>
                                            <th class="lightgreen">Number Of Programs</th>
                                            <th class="lightgreen">Start Date</th>
                                            <th class="lightgreen">Expire Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="package in ap.packages">
                                            <td>{{package.package_name}}</td>
                                            <td>{{package.price}}</td>
                                            <td>{{package.no_of_program}}</td>
                                            <td>{{package.start_date}}</td>
                                            <td>{{package.expire_date}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>    
                        
                    </div>
                    <div class="col-md-12 text-center" ng-show="assigned_packages.length == 0">
                        <p><strong>No package assigned yet</strong></p>
                        <p><a class="btn btn-primary " href="<?php echo site_url($this->uri->segment(1).'/edit/'.$token.'#package_assign'); ?>"><i class="fa fa-plus-circle"></i> Assign Package</a></p>
                    </div>
            </div>
        </div>
    </div>
</div>