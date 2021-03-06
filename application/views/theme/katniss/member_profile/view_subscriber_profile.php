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
</script>
<div id="container" ng-controller="EditSubscriberProfile" ng-cloak>

    <div class="panel panel-default">

        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> View Subscriber [ {{profile.subscriber_name}} ]
                        <!--<span ng-if="profile.id">[Available Balance:
                                    <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                    <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                    ]</span>-->

                        <?php if(in_array($user_info->user_type,array('lco','LCO'))){ ?>
                            <a href="<?php echo site_url('subscriber/charge/'.$token.'/all'); ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-money"></i> Charge</a>
                            <a href="<?php echo site_url('subscriber/migrate/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-exchange"></i> Migrate</a>
                            <a href="<?php echo site_url('subscriber/subscriber-recharge/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-usd"></i> Recharge</a>
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
                        <div class="profile panel-body tab-pane active" id="profile"  ng-show="tabs.profile">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Profile Information</h4>
                                    <hr/>
                                </div>
                            </div>
                            <span class="clearfix"></span>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputEmail1">Full Name</label>
                                        : {{profile.subscriber_name}}<br/>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="email">Email</label>                       
                                        : {{profile.email}}<br/>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="address1"> Address Line 1</label>                        
                                        : {{profile.address1}}<br/>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="address2"> Address Line 2</label>                        
                                        : {{profile.address2}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="address2"> Contact</label>                       
                                        : {{profile.contact}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="address2"> Billing Contact</label>                       
                                        : {{profile.billing_contact}}<br/>
                                    </div>
                                </div>
                                <div class="col-md-4">                          

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputPassword1">Country</label>                       
                                        : {{profile.country_name}}<br/>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputPassword1">Divisions</label>                     
                                        : {{profile.division_name}}<br/>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputPassword1">District</label>                      
                                        : {{profile.district_name}}<br/>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputPassword1">Area</label>                      
                                        : {{profile.area_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputPassword1">Sub Area</label>                     
                                        : {{profile.sub_area_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="exampleInputPassword1">Road</label>                     
                                        : {{profile.road_name}}<br/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="col-sm-4 control-label" for="exampleInputPassword1">Image</label>   
                                        <img class="thumbnail" width="100" height="100" ng-show="profile.photo" ng-src="<?php echo base_url('/'); ?>{{profile.photo}}"/>
                                        <img class="thumbnail" width="100" height="100" ng-if="!profile.photo" src="<?php echo base_url();?>public/profile.jpg">
                                </div>
                        </div>
                        <div class="profile panel-body tab-pane active" id="login" ng-show="!tabs.login">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Login Information</h4>
                                    <hr/>
                                    <span class="clearfix"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label id="view" class="col-sm-5 control-label" for="username">Username</label>                      
                                    : {{profile.username}}<br/>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label id="view" class="col-sm-5 control-label" for="username">Is Active:</label>                      
                                    <span ng-if="profile.user_status" class='label label-success'>Active</span>
                                    <span ng-show="!profile.user_status" class="label label-danger">Inactive</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="view" class="col-sm-8 control-label" for="username">Is Remote Access Enabled:</label>                      
                                    <span ng-if="profile.is_remote_access_enabled==0" class='label label-success'>Active</span>
                                    <span ng-if="profile.is_remote_access_enabled==1" class="label label-danger">Inactive</span>
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







