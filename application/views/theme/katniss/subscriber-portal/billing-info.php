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
    var token = "<?php echo $token; ?>";
    var user_id = "<?php echo $user_info->id; ?>";
    var user_type = "<?php echo $user_info->user_type; ?>";
</script>
<div id="container" ng-controller="EditSubscriberProfile" ng-cloak>

    <div class="panel panel-default">

        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> View Subscriber [ {{profile.subscriber_name}} ]


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
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="tab-content" ng-if="!loader">
                        <style type="text/css">
                            .profile{background: rgba(202, 255, 202, 0.15) none repeat scroll 0 0;border-radius: 5px;box-shadow: 0 0 2px;margin-bottom: 20px; }
                            hr {border-color: teal;}
                            .profile h4{color:#008000;}
                        </style>
                        <div class="profile panel-body tab-pane active" id="billing_address" ng-show="!tabs.billing_address">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Billing Address</h4>
                                    <hr/>
                                    <span class="clearfix"></span>
                                </div>
                            </div>
                            <div class="col-md-5">

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Full Name</label>
                                    : {{billing_address.name}}<br/>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">Email</label>
                                    : {{billing_address.email}}<br/>
                                </div>

                                <div class="form-group">
                                    <label for="address1" class="col-md-4 control-label"> Address Line 1</label>
                                    : {{billing_address.address1}}<br/>
                                </div>

                                <div class="form-group">
                                    <label for="address2" class="col-md-4 control-label"> Address Line 2</label>
                                    : {{billing_address.address2}}<br/>

                                </div>
                                <div class="form-group">
                                    <label for="address2" class="col-md-4 control-label"> Contact</label>
                                    : {{billing_address.contact}}<br/>
                                </div>
                                <div class="form-group">
                                    <label for="address2" class="col-md-4 control-label"> Billing Contact </label>
                                    : {{billing_address.billing_contact}}<br/>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Country</label>
                                    : {{billing_address.country_name}}<br/>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Division</label>
                                    : {{billing_address.division_name}}<br/>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">District</label>
                                    : {{billing_address.district_name}}<br/>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Area</label>
                                    : {{billing_address.area_name}}<br/>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Sub Area</label>
                                    : {{billing_address.sub_area_name}}<br/>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Road</label>
                                    : {{billing_address.road_name}}<br/>
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







