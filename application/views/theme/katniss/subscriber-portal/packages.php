<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo PROJECT_PATH; ?>public/theme/katniss/css/lightbox.min.css" type="text/css" />
<script type="text/javascript" src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>js/lightbox-plus-jquery.js"></script>
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
    var token = "<?php echo $user_info->token; ?>";
    var user_id = "<?php echo $user_info->id; ?>";
    var user_type = "<?php echo $user_info->user_type; ?>";
</script>
<div id="container" ng-controller="Packages" ng-cloak>

    <div class="panel panel-default">

        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> Packages of Subscriber [ <?php echo $subscriber_name; ?> ]


                        <?php if(in_array($user_info->user_type,array('lco','LCO'))){ ?>
                            <a href="<?php echo site_url('subscriber/charge/'.$token.'/all'); ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-money"></i> Charge</a>
                            <a href="<?php echo site_url('subscriber/migrate/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-exchange"></i> Migrate</a>
                            <a href="<?php echo site_url('subscriber-recharge/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-usd"></i> Recharge</a>
                            <a ng-if="permissions.edit_permission=='1'" href="<?php echo site_url('subscriber/edit/{{profile.token}}'); ?>" class="btn btn-success btn-sm pull-right" style="margin-right: 10px"><i class="fa fa-pencil"></i> Edit</a>
                        <?php } ?>
                        <a href="<?php echo (isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'') ?>" class="btn btn-danger btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr style="border-color:#ddd;"/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="tab-content" ng-if="!loader">
                        <style type="text/css">
                            .profile{background: rgba(202, 255, 202, 0.15) none repeat scroll 0 0;border-radius: 5px;box-shadow: 0 0 2px;margin-bottom: 20px; }
                            hr {border-color: teal;}
                            .profile h4{color:#008000;}
                        </style>
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
                        <div class="row" ng-repeat="ap in assigned_package_list" ng-if="!formData.pairing_id">

                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <h5 style="border-bottom:1px solid #dedede;">Pairing ID: {{ap.pairing_id}} [ <small>No of days: {{ap.no_of_days}}</small> ] [ <small>Charge Type: {{((ap.charge_type=='1')?  'By Amount':'By Package')}}</small> ]
                                        [ <small>Total Price: {{ap.total_price}}</small> ]
                                        <a ng-click="migrate(ap)" class="btn btn-danger btn-sm pull-right">Migrate</a>
                                    </h5>
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

                    </div>
                    <div class="row text-center" ng-show="loader">
                        <h3>Loading</h3>
                        <img  src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                    </div>
                </div>
            </div>
        </div>







