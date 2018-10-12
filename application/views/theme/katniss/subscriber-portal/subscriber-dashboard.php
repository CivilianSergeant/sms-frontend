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
<div id="container" ng-controller="SubscriberDashboard" ng-cloak>

    <div class="panel panel-default">

        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> Welcome to PLAAS


                        <?php if(in_array($user_info->user_type,array('lco','LCO'))){ ?>
                            <a href="<?php echo site_url('subscriber/charge/'.$token.'/all'); ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-money"></i> Charge</a>
                            <a href="<?php echo site_url('subscriber/migrate/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-exchange"></i> Migrate</a>
                            <a href="<?php echo site_url('subscriber-recharge/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-usd"></i> Recharge</a>
                            <a ng-if="permissions.edit_permission=='1'" href="<?php echo site_url('subscriber/edit/{{profile.token}}'); ?>" class="btn btn-success btn-sm pull-right" style="margin-right: 10px"><i class="fa fa-pencil"></i> Edit</a>
                        <?php } ?>

                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr style="border-color:#ddd;"/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <a href="<?php echo site_url('subscriber-packages/'.$user_info->token);?>">
                                    <div class="small-box bg-aqua">
                                        <div class="top">
                                            <h6>Subscribed Packages</h6>
                                        </div>
                                        <div class="inner">
                                            <h3><span>Total </span><?php echo $package_count;?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-bar-chart"></i>
                                        </div>
                                        <div class="small_box_footer small-box-footer">
                                            <!--<p >Active <?php /*echo $program_active->activeprogram;*/?></p>
                                            <p style="float:right;">Deactive <?php /*echo $program_deactive->deactiveprogram;*/?></p>-->
                                        </div>
                                    </div></a>
                            </div>
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <a href="<?php echo site_url('subscriber-addon-packages/'.$user_info->token);?>">
                                    <div class="small-box bg-green">
                                        <div class="top">
                                            <h6>Subscribed Addon Packages</h6>
                                        </div>
                                        <div class="inner">
                                            <h3><span>Total </span><?php echo $add_on_package_count;?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-bar-chart"></i>
                                        </div>
                                        <div class="small_box_footer small-box-footer">
                                            <!--<p >Active <?php /*echo $program_active->activeprogram;*/?></p>
                                            <p style="float:right;">Deactive <?php /*echo $program_deactive->deactiveprogram;*/?></p>-->
                                        </div>
                                    </div></a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" ng-if="!loader">
                        <style type="text/css">
                            .profile{background: rgba(202, 255, 202, 0.15) none repeat scroll 0 0;border-radius: 5px;box-shadow: 0 0 2px;margin-bottom: 20px; }
                            hr {border-color: teal;}
                            .profile h4{color:#008000;}

                        </style>

                        <div class="col-md-6">
                            <h6 style="border-bottom:1px solid lightgray;color:#337ab7;">Available Packages</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-aqua">Package Name</th>
                                        <th class="bg-aqua">Duration</th>
                                        <th class="bg-aqua">Channels</th>
                                        <th class="bg-aqua">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="package in packages">
                                        <td>{{package.package_name}}</td>
                                        <td>{{package.duration}}</td>
                                        <td>{{package.no_of_program}}</td>
                                        <td>{{package.price}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 style="border-bottom:1px solid lightgray;color:#00a65a;">Available Add-on Packages</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-green">Package Name</th>
                                        <th class="bg-green">Duration</th>
                                        <th class="bg-green">Channels</th>
                                        <th class="bg-green">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="a in add_ons">
                                        <td>{{a.package_name}}</td>
                                        <td>{{a.duration}}</td>
                                        <td>{{a.programs}}</td>
                                        <td>{{a.price}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row text-center" ng-show="loader">
                        <h3>Loading</h3>
                        <img  src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                    </div>
                </div>
            </div>
        </div>







