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
<div id="container" ng-controller="EditSubscriberProfile" ng-cloak>

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
                
                    <h4 class="widgettitle"> Edit Foc Subscriber <span ng-if="profile.id">[ {{profile.subscriber_name}} ]</span>
                         <span ng-if="profile.id">[Available Balance:
                                    <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                    <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                    ]</span>
                        <a href="<?php echo site_url('foc-subscriber/charge/'.$token.'/all'); ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-money"></i> Charge</a>
                        <a href="<?php echo site_url('foc-subscriber/migrate/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-exchange"></i> Migrate</a>
                        <a href="<?php echo site_url('foc-subscriber/subscriber-recharge/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-usd"></i> Recharge</a>
                        <a href="<?php echo site_url('foc-subscriber/view/'.$token); ?>" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-search"></i> View</a>
                        <a href="<?php echo site_url('foc-subscriber'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    
             
                <span class="clearfix"></span>
            </div>
            <hr/>
        </div>
        <div class="col-md-12">
            <div class="panel-body">
            <div class="col-md-12">
                <ul class="tab_nav nav nav-tabs">
                    <li ng-class="{active:tabs.profile}"><a class="tab_top" ng-click="setTab('profile')">Profile</a></li>
                    <li ng-class="{active:tabs.login}"><a class="tab_top" ng-click="setTab('login')">Login Info</a></li>
                     <li ng-class="{active:tabs.billing_address}"><a class="tab_top" ng-click="setTab('billing_address')">Billing Address</a></li>
                    <li ng-class="{active:tabs.business_region}" ng-if="billing_address_id != null"><a class="tab_top" ng-click="setTab('business_region')">Business Region</a></li>
                    <li ng-class="{active:tabs.stb_card}" ng-if="(profile.region_l1_code != undefined && profile.region_l1_code != 0)"><a class="tab_top" ng-click="setTab('stb_card')">STB & Card</a></li>
                    <li ng-class="{active:tabs.package_assign}" ng-if="stb_cards.length>0 && stb_cards[0].id != null"><a class="tab_top" ng-click="setTab('package_assign')">Package Assign</a></li>
                    <li ng-class="{active:tabs.documents}"><a class="tab_top" ng-click="setTab('documents')">Document Upload</a></li>
                    <li ng-class="{active:tabs.invoice}"><a class="tab_top" ng-click="setTab('invoice')">Invoice</a></li>
                    <li ng-class="{active:tabs.tools}"><a class="tab_top" ng-click="setTab('tools')">Tools</a></li>
                </ul>
            </div>
            

                <div class="tab-content" >
                    <div id="profile" class="tab-pane active" ng-show="tabs.profile">
                        <form name="subscriber_profile" id="subscriber_profile" method="post">
                        <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                            <h4>Profile Information</h4>
                        </div>
                        <div class="row" ng-if="!loader">
                            <div class="col-md-12">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Full Name <span class="text-danger">*</span></label>                        
                                        <div class="margin-bottom-sm">                                           
                                            <input type="text" class="form-control" ng-model="profile.subscriber_name" placeholder="Full Name" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>                       
                                        <div class="margin-bottom-sm">

                                            <input type="email" class="form-control" id="email" ng-model="profile.email" placeholder="Enter Your Email" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address1"> Address Line 1</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" ng-model="profile.address1" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address2"> Address Line 2</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" ng-model="profile.address2" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address2"> Mobile Number <span class="text-danger">*</span></label>
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" maxlength="11" ng-model="profile.contact"  required="required"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address2"> 2<sup>nd</sup> Mobile Number ( Same as above <input type="checkbox" ng-model="profile.is_same_as_contact"  ng-change="changeBillingContact()" ng-true-value="1" ng-false-value="0"/>)</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" maxlength="11" ng-model="profile.billing_contact" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">                          

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Country</label>                      
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="country_id" ng-model="profile.country_id" >
                                                <option value="">---Select Country---</option>
                                                <option ng-repeat="country in countries" ng-attr-selected={{profile.country_id == country.id}} value="{{country.id}}">{{country.country_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Divisions</label>                        
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="division_id" ng-model="profile.division_id" disabled>
                                                <option value="">--Select Division--</option>
                                                <option ng-repeat="division in divisions" ng-attr-selected={{profile.division_id == division.id}} value="{{division.id}}">{{division.division_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">District</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="district_id" ng-model="profile.district_id" disabled>
                                                <option value="">--Select District--</option>
                                                <option ng-repeat="district in districts" ng-attr-selected={{profile.district_id == district.id}} value="{{district.id}}">{{district.district_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Area</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="area_id" ng-model="profile.area_id" disabled>
                                                <option value="">--Select Area--</option>
                                                <option ng-repeat="area in areas" ng-attr-selected={{profile.area_id == area.id}} value="{{area.id}}">{{area.area_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Sub Area</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="sub_area_id" ng-model="profile.sub_area_id" disabled>
                                                <option value="">--Select Sub Area--</option>
                                                <option ng-repeat="sub_area in sub_areas" ng-attr-selected={{profile.sub_area_id == sub_area.id}} value="{{sub_area.id}}">{{sub_area.sub_area_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Road</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="road_id" ng-model="profile.road_id" disabled>
                                                <option value="">--Select Road--</option>
                                                <option ng-repeat="road in roads" ng-attr-selected={{profile.road_id == road.id}} value="{{road.id}}">{{road.road_name}}</option>
                                            </select>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type of Identity Number</label>
                                        <select class="form-control" ng-model="profile.identity_type">
                                            <option value="">--Select Type ---</option>
                                            <option ng-repeat="type in identity_verify_types" value="{{type}}">{{type}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" ng-if="profile.identity_type">
                                        <label>Identity Number</label>
                                        <input type="text" class="form-control" maxlength="20" ng-model="profile.identity_number"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" style="margin-bottom:0px;">
                                        <label><input type="checkbox" ng-checked="profile.foc_control_room=='1'" ng-model="profile.foc_control_room" ng-true-value="'1'" ng-false-value="'0'" /> <span style="position:relative;top:-2px;">For Control Room</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><input type="checkbox" ng-checked="profile.foc_others=='1'" ng-model="profile.foc_others" ng-true-value="'1'" ng-false-value="'0'" /> <span style="position:relative;top:-2px;">Others</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <hr/>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Reference Type</label>
                                        <select class="form-control" ng-model="profile.reference_type">
                                            <option value="">--Select Type ---</option>
                                            <option ng-repeat="type in ref_types" value="{{type}}">{{type}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" ng-show="profile.reference_type=='OTHER'">
                                    <div class="form-group">
                                        <label>Reference</label>
                                        <select class="form-control" ng-model="profile.reference_id">
                                            <option value="">--Select Type ---</option>
                                            <option ng-repeat="reference in references" value="{{reference.id}}">{{reference.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" ng-show="profile.reference_type=='LCO'">
                                    <div class="form-group">
                                        <label>Reference</label>
                                        <select class="form-control" ng-model="profile.reference_id">
                                            <option value="">--Select Type ---</option>
                                            <option ng-repeat="reference in lco_profiles" value="{{reference.user_id}}">{{reference.lco_name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <div class="col-md-12" style="padding:0px;">
                                            <textarea ng-model="profile.remarks" cols="30" rows="10" style="width:100%;height:82px;resize:none;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <a id="buttonsuccess" id="btnNext" ng-disabled="subscriber_profile.$invalid" ng-click="updateProfile()" class="btn btn-success btnNext" >Update Profile</a>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row text-center" ng-show="loader">

                            <h3>Loading</h3>
                            <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                        </div>
                    </form> 
                        
                </div>
                <div id="login" class="tab-pane active" ng-show="tabs.login">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="panel-heading" style="padding-bottom: 20px">
                                <h4>Login Information</h4>
                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label for="username">Username</label>                      
                                    <div class="margin-bottom-sm">
                                        <input type="text" class="form-control" id="username" ng-model="profile.username" value="" placeholder="Enter Username" required="required">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>                      
                                    <div class="margin-bottom-sm">
                                        <input type="password" class="form-control" id="password" ng-model="profile.password" ng-change="checkPassWordStrength()" value="" placeholder="Enter Your Password" required="required">
                                        <div ng-if="notStrongPassFlag" style="color: red;">{{pass_message}}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Retype Password</label>                      
                                    <div class="margin-bottom-sm">
                                        <input type="password" class="form-control" id="password" ng-model="profile.re_password" ng-change="checkRePassword()" value="" placeholder="Enter Your Password" required="required">
                                        <div ng-if="checkRePasswordFlag" style="color: red">{{re_pass_message}}</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password"> <input type="checkbox" ng-model="profile.is_remote_access_enabled" ng-checked="profile.is_remote_access_enabled=='1'"  ng-true-value="'1'" ng-false-value="'0'"/> Remote Login Access Disabled</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a id="btnNext" ng-disabled="isSaveLoginDisabled()" ng-click="updateLogin()" class="btn btn-success btnNext" >Save Profile</a>
                            </div>
                        </div>

                    </div>
                </div>
                 <div id="billing_address" class="tab-pane active" ng-show="tabs.billing_address" style="padding-top:10px;">
                    <form name="subscriber_billing_address" id="subscriber_billing_address" method="post" ng-submit="saveBillingAddress()">
                        <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                            <h4>Billing Address</h4>
                            <input type="checkbox" ng-model="billing_address.is_same_as_profile"  ng-true-value="1" ng-false-value="0" ng-change="setBillingAddress()"  /> Same as Profile
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Full Name</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" ng-model="billing_address.name" placeholder="Full Name" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="email" class="form-control" id="email" ng-model="billing_address.email" placeholder="Enter Your Email" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address1"> Address Line 1</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" ng-model="billing_address.address1" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address2"> Address Line 2</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" ng-model="billing_address.address2" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address2"> Mobile Number</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" maxlength="11" ng-model="billing_address.contact" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address2"> 2<sup>nd</sup> Mobile Number ( Same as above <input type="checkbox" ng-model="billing_address.is_same_as_contact" ng-change="changeBillingAddressContact()" ng-true-value="1" ng-false-value="0"/>)</label>                       
                                        <div class="margin-bottom-sm">
                                            <input type="text" class="form-control" maxlength="11" ng-model="billing_address.billing_contact" />
                                        </div>
                                    </div> 
                                </div>

                                <div class="col-md-4">                          

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Country</label>                      
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="country_id" ng-model="billing_address.country_id" >
                                                <option value="">---Select Country---</option>
                                                <option ng-repeat="country in countries" ng-attr-selected={{billing_address.country_id == country.id}} value="{{country.id}}">{{country.country_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Divisions</label>                        
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="division_id" ng-model="billing_address.division_id" ng-disabled="!billing_address.country_id">
                                                <option value="">--Select Division--</option>
                                                <option ng-repeat="division in divisions" ng-attr-selected={{billing_address.division_id == division.id}} value="{{division.id}}">{{division.division_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">District</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="district_id" ng-model="billing_address.district_id" ng-disabled="!billing_address.division_id">
                                                <option value="">--Select District--</option>
                                                <option ng-repeat="district in districts" ng-attr-selected={{billing_address.district_id == district.id}} value="{{district.id}}">{{district.district_name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Area</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="area_id" ng-model="billing_address.area_id" ng-disabled="!billing_address.district_id">
                                                <option value="">--Select Area--</option>
                                                <option ng-repeat="area in areas" ng-attr-selected={{billing_address.area_id == area.id}} value="{{area.id}}">{{area.area_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Sub Area</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="sub_area_id" ng-model="billing_address.sub_area_id" ng-disabled="!billing_address.area_id">
                                                <option value="">--Select Sub Area--</option>
                                                <option ng-repeat="sub_area in sub_areas" ng-attr-selected={{billing_address.sub_area_id == sub_area.id}} value="{{sub_area.id}}">{{sub_area.sub_area_name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Road</label>                     
                                        <div class="margin-bottom-sm">
                                            <select class="form-control" id="road_id" ng-model="billing_address.road_id" ng-disabled="!billing_address.sub_area_id">
                                                <option value="">--Select Road--</option>
                                                <option ng-repeat="road in roads" ng-attr-selected={{billing_address.road_id == road.id}} value="{{road.id}}">{{road.road_name}}</option>
                                            </select>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" id="buttonsuccess" ng-disabled="subscriber_billing_address.$invalid" class="btn btn-success" value="Save Billing Address"/>
                                    <a id="buttoncancel" id="btnNext" ng-click="hideForm()" class="btn btn-warning btnNext" >Cancel</a>
                                </div>
                            </div>
                        </div>
                        
                    </form> 
                </div>
                
                <div id="documents" class="tab-pane active" ng-show="tabs.documents"  style="padding-top: 10px">
                    <form name="subscriber_photo" id="subscriber_photo" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <style type="text/css">
                                .form-control{height: 34px !important;}
                                .progress {height: 40px !important;}
                                .progress-bar {font-size: 18px; line-height: 38px; color: springgreen;}
                                .input-file { position: relative;} /* Remove margin, it is just for stackoverflow viewing */
                                .input-file .input-group-addon { border: 0px; padding: 0px; }
                                .input-file .input-group-addon .btn { border-radius: 0 4px 4px 0 }
                                .input-file .input-group-addon input { cursor: pointer; position:absolute; width: 72px; z-index:2;top:0;right:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0; background-color:transparent; color:transparent; }
                            </style>
                            <div class="col-md-12">
                                <div class="panel-heading" style="padding-bottom: 20px">
                                    <h5>Attach Photo</h5>
                                </div>
                                <div class="col-md-12">
                                        <label>Upload Photo</label>
                                </div>
                                <div class="col-md-4">
                                    <div ng-hide="messageState">
                                        <div class="input-group input-file">
                                            <div class="form-control">
                                                <!-- <a href="/path/to/your/current_file_name.pdf" target="_blank">current_file_name.pdf</a> -->
                                            </div>
                                            <span class="input-group-addon">
                                                <a href="javascript:;" class="btn btn-primary">
                                                  Browse
                                                <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="uploader" nv-file-select="">
                                                </a>
                                            </span>
                                        </div>
                                        <div style="margin-top:5px" class="progress">
                                            <div ng-style="{ 'width': fileUploadPhotoProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadPhotoProgress" class="ng-binding ng-hide">{{fileUploadPhotoProgress}} %</div></div>
                                        </div> 
                                        
                                        <!-- <button ng-disabled="!uploader.isUploading" ng-click="uploader.cancelAll()" class="btn btn-warning btn-s" type="button" disabled="disabled">
                                            <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                                        </button> -->
                                    </div> 
                                </div>
                                <div class="col-md-3 pull-right">
                                    <img width="100" height="100" ng-show="profile.photo" ng-src="<?php echo base_url('/'); ?>{{profile.photo}}"/>
                                </div> 
                            </div>
                            <div class="col-md-12"> 
                                <div class="col-md-12">
                                    <button id="buttonsuccess" ng-disabled="!uploader.getNotUploadedItems().length" ng-click="uploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                        <span class="glyphicon glyphicon-upload"></span> Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr/>
                    <form name="subscriber_identity" id="subscriber_identity" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-heading">
                                    <h5 style="padding-bottom: 20px;">Attach Identity Verification Document</h5>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <label>Upload Identity Document</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div ng-hide="messageState">
                                                <div class="input-group input-file">
                                                    <div class="form-control">
                                                        <!-- <a href="/path/to/your/current_file_name.pdf" target="_blank">current_file_name.pdf</a> -->
                                                    </div>
                                                    <span class="input-group-addon">
                                                        <a href="javascript:;" class="btn btn-primary">
                                                        Browse
                                                        <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="identityUploader" nv-file-select="">
                                                        </a>
                                                    </span>
                                                </div>
                                                <div style="margin-top:5px" class="progress">
                                                    <div ng-style="{ 'width': fileUploadIdentityProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadIdentityProgress" class="ng-binding ng-hide">{{fileUploadIdentityProgress}} %</div></div>
                                                </div>     
                                            </div> 
                                        </div>
                                        <div class="col-md-3 col-md-offset-5">
                                            <img width="150" height="150" ng-show="identity.identity_attachment" ng-src="<?php echo base_url('/'); ?>{{identity.identity_attachment}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button id="buttonsuccess" ng-disabled="!identityUploader.getNotUploadedItems().length" ng-click="identityUploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                        <span class="glyphicon glyphicon-upload"></span> Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr/>
                    <form name="subscription" id="subscription" method="post" >
                        <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                            <h4>Subscription Copy</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <label>Upload Subscription Document Copy</label>
                                </div>
                                <div class="col-md-4">
                                    <div ng-hide="messageState">
                                        <div class="input-group input-file">
                                            <div class="form-control">
                                                <!-- <a href="/path/to/your/current_file_name.pdf" target="_blank">current_file_name.pdf</a> -->
                                            </div>
                                            <span class="input-group-addon">
                                            <a href="javascript:;" class="btn btn-primary">
                                              Browse
                                            <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="subuscriptionUploader" nv-file-select="">
                                            </a>
                                            </span>
                                        </div>
                                        <div style="margin-top:5px" class="progress">
                                            <div ng-style="{ 'width': fileUploadSubscriptionProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadSubscriptionProgress" class="ng-binding ng-hide">{{fileUploadSubscriptionProgress}} %</div></div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-3 pull-right">
                                    <img ng-show="profile.subscription_copy" ng-src="<?php echo base_url('/'); ?>{{profile.subscription_copy}}"/>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <button id="buttonsuccess" ng-disabled="!subuscriptionUploader.getNotUploadedItems().length" ng-click="subuscriptionUploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                        <span class="glyphicon glyphicon-upload"></span> Upload
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="stb_card" class="tab-pane active" ng-show="tabs.stb_card"  style="padding-top: 10px">
                    <form name="stb-card" id="stb-card" method="post" ng-submit="addStbCard()" class="form-horizontal">
                        <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                            <h4>STB & Card Pairing
                                <!-- <a ng-click="addSTBForm()" class="btn btn-success btn-sm pull-right">Add New</a> -->
                            </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="form-group" style="padding-top:20px;">
                                    <label class="control-label col-md-4">Set-Top Box ID:</label>
                                    <div class="col-md-6">
                                        <select kendo-combo-box
                                                k-placeholder="'Select STB'"
                                                k-data-text-field="'stb_number'"
                                                k-data-value-field="'stb_number'"

                                                k-data-source="stbs"
                                                style="width: 100%" ng-model="stb_box_id" >
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group" style="padding-top:20px;">
                                    <label class="control-label col-md-4">Smart Card ID:</label>
                                    <div class="col-md-6">
                                        <select kendo-combo-box
                                                k-placeholder="'Select Smart Card'"
                                                k-data-text-field="'smart_card_number'"
                                                k-data-value-field="'smart_card_number'"

                                                k-data-source="smart_cards"
                                                style="width: 100%" ng-model="smart_card_id" >
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Free:</label>
                                    <div class="col-md-6">
                                        <label class="control-label" style="margin-right:10px;">
                                            <input type="checkbox" ng-model="free_stb" ng-true-value="'1'" ng-false-value="'0'"/>
                                            <span style="position:relative;top:-2px;">Set-Top Box</span>
                                        </label>
                                        <label class="control-label" style="margin-right:10px;">
                                            <input type="checkbox" ng-model="free_card" ng-true-value="'1'" ng-false-value="'0'"/>
                                            <span style="position:relative;top:-2px;">Smart Card</span>
                                        </label>
                                        <label class="control-label">
                                            <input type="checkbox" ng-model="free_subscription_fee" ng-true-value="'1'" ng-false-value="'0'"/>
                                            <span style="position:relative;top:-2px;">Subscription Fee</span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Remark</label>
                                <div style="padding:0px;">
                                    <textarea ng-model="remarks" cols="30" rows="10" style="width:90%;height:82px;resize:none;"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-2">
                                    <input type="Submit" id="buttonsuccess" class="btn btn-success" ng-disabled="!stb_box_id || !smart_card_id" value="Save" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">

                        <div class="col-md-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Pairing ID</th>
                                        <th>STB Provider</th>
                                        <th>STB Type</th>
                                        <th>STB Number</th>
                                        <th>SmartCard Provider</th>
                                        <th>SmartCard Type</th>
                                        <th>SmartCard Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in stb_cards">
                                        <td>{{item.pairing_id}}</td>
                                        <td>{{item.stb_provider}}</td>
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
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="business_region" class="tab-pane active" ng-show="tabs.business_region" style="padding-top:10px;">
                    <form name="business_region" id="business_region" method="post" ng-submit="saveBusinessRegion()" class="form-horizontal">
                        <div class="col-md-12" style="padding-bottom: 20px;">
                            <div class="panel-heading">
                            <h4>Business Region</h4>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <div class="col-md-3">
                                    <label>Level 1: </label>
                                    <select class="form-control" id="business_region_l1" ng-model="business_region_l1" ng-change="setRegionLevel2()">
                                       <!--  <option value="">--Select Region--</option> -->
                                        <option ng-repeat="region in regions" ng-selected="region.id == profile.region_l1_code" value="{{region.id}}">{{region.name}}</option>
                                    </select>
                                    
                                </div>
                                <div class="col-md-3">
                                    <label>Level 2: </label>
                                    <select class="form-control" id="business_region_l2" ng-model="business_region_l2" ng-change="setRegionLevel3()">
                                        <option value="">--Select Region--</option>
                                        <option ng-repeat="region in regions_level_2" ng-selected="region.id == profile.region_l2_code" value="{{region.id}}">{{region.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Level 3: </label>
                                    <select class="form-control" id="business_region_l3" ng-model="business_region_l3" ng-change="setRegionLevel4()">
                                        <option value="">--Select Region--</option>
                                        <option ng-repeat="region in regions_level_3" ng-selected="region.id == profile.region_l3_code" value="{{region.id}}">{{region.name}}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Level 4: </label>
                                    <select class="form-control" id="business_region_l4" ng-model="business_region_l4">
                                        <option value="">--Select Region--</option>
                                        <option ng-repeat="region in regions_level_4" ng-selected="region.id == profile.region_l4_code"  value="{{region.id}}">{{region.name}}</option>
                                    </select>
                                </div>
                            </div>
                            
                                <div class="form-group">
                                    <div class="col-md-12">
                                    <input type="submit" id="buttonsuccess" ng-disabled="business_region.$invalid" class="btn btn-success" value="Save Business Region"/>
                                   
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </form> 
                </div>
                <div id="package_assign" class="tab-pane active" ng-show="tabs.package_assign" style="padding-top:10px;">
                    <form name="package_assign" id="package_assign" ng-submit="assignPackages()" method="post" class="form-horizontal">
                        <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                            <h4>Package Assign</h4>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-5">
                                    <label>STB-Card:</label>
                                    <select  ng-model="stb_card_id" ng-change="setPairingID()" class="form-control">
                                        <option value="">--SELECT STB-CARD PAIR </option>
                                        <option ng-repeat="stb_card in unassigned_stb_cards"  value="{{stb_card.id}}">PairingID({{stb_card.pairing_id}})-STB({{stb_card.stb_number}})-Card({{stb_card.smart_card_number}})</option>
                                    </select>
                                </div>
                                <!--<div class="col-md-5" ng-if="stbCard && stbCard.free_subscription_fee == '0'">
                                    <label>Available Balance:</label>
                                    <span><strong>{{balance}}</strong></span>
                                </div>-->
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <label class="control-label">Package List</label> 
                                    <select id="select-from" ng-model="selected_item" ng-disabled="!unassigned_stb_cards.length" style="width:310px;min-height:100px;" ng-model="selected_package" multiple="multiple" >
                                        <option ng-repeat="p in packages"  style="font-size:13px" value="{{p.id}}" >{{p.package_name}} - [ Price:{{p.price}} - Duration:{{p.duration}} - Count:{{p.no_of_program}} ]</option>
                                    </select>
                                </div>
                                <div class="col-md-1" style="margin-top:25px;margin-left:5px;margin-right:-5px;">
                                    <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button>
                                    <button type="button" ng-click="ExcludeItems()" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Assigned Package List</label> 
                                    <select id="select-from" ng-model="included_item" ng-disabled="!unassigned_stb_cards.length" style="width:310px;min-height:100px;" ng-model="selected_package" multiple="multiple" >
                                        <option ng-repeat="p in assigned_packages"  style="font-size:13px" value="{{p.id}}" >{{p.package_name}} - [ Price:{{p.price}} - Duration:{{p.duration}} - Count:{{p.no_of_program}} ]</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div ng-if="stbCard && stbCard.free_subscription_fee == '1'" class="form-group">
                            <div class="col-md-10">
                                <div class="col-md-4">
                                    <label>Expire Date</label>
                                    <input kendo-date-picker
                                     ng-model="expire_date_string"
                                     k-ng-model="expire_date_object"
                                     k-format="'yyyy-MM-dd'"
                                     style="width: 100%;" />
                                </div>
                            </div>
                        </div>

                        <div ng-if="stbCard && stbCard.free_subscription_fee == '0'" class="form-group">
                            <div class="col-md-10">
                                <div class="col-md-5">
                                    <label>Total Price: {{package_price}}</label>
                                </div>
                            </div>
                        </div>
                        <div ng-show="stbCard && stbCard.free_subscription_fee == '0'" class="form-group">
                            <div class="col-md-10" ng-init="charge_type=0">
                                <div class="col-md-5">
                                    <label>Charge By {{charge_by}}</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="control-label"><input type="radio" ng-model="charge_type" ng-disabled="!unassigned_stb_cards.length" ng-value="1" /> Amount</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="control-label"><input type="radio" ng-model="charge_type" ng-disabled="!unassigned_stb_cards.length" ng-value="0" /> Package</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <input type="submit" ng-disabled="isDisabledAssignPackage()" id="buttonsuccess"  class="btn btn-success" value="Assign Package & Charge"/>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class="col-md-12"><hr/></div>
                    <div class="row" ng-repeat="ap in assigned_package_list">
                        
                        <div class="col-md-12">    
                            <div class="col-md-12">
                                <h4 style="border-bottom:1px solid #dedede;">Pairing ID: {{ap.pairing_id}} [ <small>No of days: {{ap.no_of_days}}</small> ]</h4>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="lightgreen">Package Name</th>
                                            <th class="lightgreen">Number Of Programs</th>
                                            <th class="lightgreen">Start Date</th>
                                            <th class="lightgreen">Expire Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="package in ap.packages">
                                            <td>{{package.package_name}}</td>
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
                <div id="invoice" class="tab-pane active" ng-show="tabs.invoice">
                    <div class="row" >

                        <div class="col-md-12" style="padding-top:10px;">
                            <div class="col-md-12" style="padding-bottom:10px;">
                                <h4>Invoice</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tools" class="tab-pane active" ng-show="tabs.tools" >
                    <div class="row" >

                        <div class="col-md-12" style="padding-top:10px;">
                            <div class="col-md-12" style="padding-bottom:10px;">
                                <h4>Apply CAS Instructions</h4>
                            </div>

                            <div class="col-md-6">
                                <select name="stb_card_id" ng-model="tools_stb_card_id" class="form-control">
                                    <option value="">--SELECT STB-CARD PAIR</option>
                                    <option ng-repeat="stb_card in stb_cards"  value="{{stb_card.id}}">PairingID({{stb_card.pairing_id}})-STB({{stb_card.stb_number}})-Card({{stb_card.smart_card_number}})</option>
                                </select>
                            </div>

                            <div class="col-md-12" style="padding-top:20px">
                                <input type="button" class="btn btn-info" style="width:120px;" ng-click="sendAuthorizationRequest()" value="Authorization"/>
                            </div>

                            <!--<div class="col-md-12" style="padding-top:20px">
                                <input type="button" class="btn btn-info" style="width:120px;" ng-click="sendChargeFeeRequest()" value="Charge Fee"/>
                            </div>-->

                            <div class="col-md-12" style="padding-top:20px">
                                <input type="button" class="btn btn-info" style="width:120px;" ng-click="sendPairRequest()" value="Pair"/>
                            </div>

                            <div class="col-md-12" style="padding-top:20px">
                                <input type="button" class="btn btn-info" style="width:120px;" ng-click="sendUnPairRequest()" value="Un-Pair"/>
                            </div>

                        </div>
                    </div>
                </div>
                
            </div> <!--end tab-content-->
            
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    function checkPass(){
        var re_pass = document.getElementById('re_password').value;
        var pass = document.getElementById('password').value;
        if(re_pass != pass)
        {
            document.getElementById("errMsg").innerHTML = "Password Didn't Match!";
        }
    }
</script>




