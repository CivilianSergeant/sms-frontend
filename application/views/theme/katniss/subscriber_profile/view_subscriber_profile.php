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
                        <span ng-if="profile.id">[Available Balance: 
                                    <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                    <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                    ]</span>

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
                        <div class="profile panel-body tab-pane active" id="documents"  ng-show="!tabs.documents">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Identity Verification Document</h4>
                                    <hr/>
                                    <span class="clearfix"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <style type="text/css">
                                .lb-caption a{color: white;}
                                </style>
                                <div class="col-md-3">
                                    <!--<a href="<?php /*echo base_url('/'); */?>{{identity.identity_attachment}}" data-lightbox="example-2"  data-title="<a href='<?php /*echo site_url('lco/image_download')*/?>/{{profile.token}}/?id=identity'>Download</a>">-->
                                    <img width="100" height="100" class="example-image" ng-show="identity.identity_attachment"  ng-src="<?php echo base_url('/'); ?>{{identity.identity_attachment}}"/></a>
                                    <img width="100" height="100" class="example-image" ng-if="!identity.identity_attachment"  src="<?php echo base_url();?>public/id_card.jpg"/>
                                </div>
                            </div>
                        </div>  
                        <div class="profile panel-body tab-pane active" id="documents"  ng-show="!tabs.documents">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Subscription Copy</h4>
                                    <hr/>
                                    <span class="clearfix"></span>
                                </div>
                            </div>
                           <div class="col-md-12">
                                <div class="col-md-3">
                                    <!--<a href="<?php /*echo base_url('/'); */?>{{profile.subscription_copy}}" data-lightbox="example-21"  data-title="<a href='<?php /*echo site_url('lco/image_download')*/?>/{{profile.token}}/?id=sub_copy'>Download</a>">-->
                                    <img width="100" height="100" class="example-image" ng-show="identity.identity_attachment"  ng-src="<?php echo base_url('/'); ?>{{profile.subscription_copy}}"/></a>
                                    <img width="100" height="100" class="example-image" ng-show="!identity.identity_attachment"  src="<?php echo base_url();?>public/sub.jpg"/>                                
                                </div>
                            </div>
                        </div>
                        <!--<div class="profile panel-body tab-pane active" id="business_region" ng-show="!tabs.business_region">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Business Region </h4>
                                    <hr/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                 <div class="form-group">
                                    <div class="col-md-12">
                                    <style type="text/css">
                                    .region{float:left;}
                                    </style>
                                        <div class="region" ng-repeat="region in regions" ng-show="region.id == profile.region_l1_code" >{{region.name}} &nbsp;..........</div>
                                        <div class="region" ng-repeat="region in regions_level_2" ng-show="region.id == profile.region_l2_code">{{region.name}} &nbsp;..........</div>
                                        <div class="region" ng-repeat="region in regions_level_3" ng-show="region.id == profile.region_l3_code">{{region.name}} &nbsp;..........</div>
                                        <div class="region" ng-repeat="region in regions_level_4" ng-show="region.id == profile.region_l4_code">{{region.name}} </div>
                                        <div class="pull-right">{{profile.hex_code}}</div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-3">     
                                    </div>
                                    <div class="col-md-3">        
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="profile panel-body tab-pane active" id="stb_card" ng-show="!tabs.stb_card">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Attached Devices</h4>
                                        <hr/>
                                 </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="lightgreen">Device ID</th>
                                            <!--<th class="lightgreen">STB Provider</th>
                                            <th class="lightgreen">STB Type</th>
                                            <th class="lightgreen">STB Number</th>
                                            <th class="lightgreen">SmartCard Provider</th>
                                            <th class="lightgreen">SmartCard Type</th>
                                            <th class="lightgreen">SmartCard Number</th>
                                             <th class="lightgreen">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="item in stb_cards">
                                            <td>{{item.device_number}}</td>
                                            <!--<td>{{item.stb_provider}}</td>
                                            <td>{{item.stb_type}}</td>
                                            <td>{{item.stb_number}}</td>
                                            <td>{{item.smart_card_provider}}</td>
                                            <td>{{item.smart_card_type}}</td>
                                            <td>{{item.smart_card_number}}</td>
                                             <td>
                                                <span ng-if="!item.pairing_id">
                                                    <a  ng-click="deleteStbSmartCard($index)" class="btn btn-danger btn-xs">Delete</a>
                                                    <a ng-click="confirmStbSmartCard($index)" class="btn btn-success btn-xs">Confirm</a>
                                                </span>
                                            </td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="profile panel-body tab-pane active" id="package_assign" ng-show="!tabs.package_assign">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Package Assigned</h4>
                                    <hr/>
                                </div>
                            </div>
                            <div class="row" ng-repeat="ap in assigned_package_list">
                    
                                <div class="col-md-12">    
                                    <div class="col-md-12">
                                        <h6 style="border-bottom:1px solid #dedede;">Device ID: {{ap.pairing_id}} [ <small>No of days: {{ap.no_of_days}}</small> ] [ <small>Charge Type: {{((ap.charge_type=='1')?  'By Amount':'By Package')}}</small> ]
                                            [ <small>Total Price: {{ap.total_price}}</small> ] 
                                        </h6>
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
                    </div>
                    <div class="row text-center" ng-show="loader">
                        <h3>Loading</h3>
                        <img  src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                    </div>
                </div>
            </div>
        </div>







