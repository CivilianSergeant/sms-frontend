<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="EditLCOProfile" ng-cloak>

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

                    <h4 class="widgettitle"> Edit LSP <?php echo ($user_info->user_type == 'MSO')? 'Admin' : 'User'; ?>  [ {{profile.lco_name}} ]

                        <a href="<?php echo site_url('lco/view/{{profile.token}}' ) ?>" class="btn btn-success btn-sm pull-right" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
                        <a href="<?php echo site_url('lco'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
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
                            <?php if($user_info->role_id != 3){ // if creator of profile is Lco admin then it will hide while creating of lco staff ?>
                            <li ng-class="{active:tabs.billing_address}"><a class="tab_top" ng-click="setTab('billing_address')">Billing Address</a></li>
                            <?php } ?>
                            <li ng-class="{active:tabs.photo}"><a class="tab_top" ng-click="setTab('photo')">Photo</a></li>
                            <li ng-class="{active:tabs.identity_verify}"><a class="tab_top" ng-click="setTab('identity_verify')">Identity Verification</a></li>
                            <?php if($user_info->role_id != 3){ // if creator of profile is Lco admin then it will hide while creating of lco staff ?>
<!--                                <li ng-class="{active:tabs.business_region}" ng-if="billing_address_id != null"><a class="tab_top" ng-click="setTab('business_region')">Business Region</a></li> -->
                                <li ng-class="{active:tabs.business_modality}"><a class="tab_top" ng-click="setTab('business_modality')">Business Modality</a></li>
                            <?php } ?>
                        </ul>
                    </div>


                    <div class="tab-content">
                        <div id="profile" class="tab-pane active" ng-show="tabs.profile">
                            <form class="form-horizontal" name="lco_profile" id="mso" method="post" action="<?php echo site_url('profile/get_mso_profile_data'); ?>">
                                <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                                    <h4>Profile Information</h4>
                                </div>
                                <div class="row" ng-if="!loader">
                                    <div class="col-md-12">
                                        <div class="col-md-6 ">

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="full_name">Full Name <span class="text-danger">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="created_by" value="<?php echo $user_info->id; ?>">
                                                    <input type="text" class="form-control" ng-model="profile.lco_name" placeholder="Full Name" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="email">Email <span style="color:red">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="email" class="form-control" id="email" ng-model="profile.email" placeholder="Enter Your Email" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="address1"> Address Line 1</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" ng-model="profile.address1" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="address2"> Address Line 2</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" ng-model="profile.address2" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="contact"> Mobile No </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" maxlength="11" ng-model="profile.contact" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="billing_contact"> 2<sup>nd</sup> Mobile Number <br/>( Same as above <input type="checkbox" ng-checked="profile.is_same_as_contact=='1'" ng-model="profile.is_same_as_contact" ng-change="changeBillingContact()" ng-true-value="'1'" ng-false-value="'0'"/>)</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" maxlength="11" ng-model="profile.billing_contact" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="message_sign">Message Sign</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" maxlength="10" style="text-transform:uppercase;" ng-model="profile.message_sign" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">                          

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="country_id">Country</label>                      
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="country_id" ng-model="profile.country_id" ng-change="changeCountry('profile')">
                                                        <option value="">---Select Country---</option>
                                                        <option ng-repeat="country in countries" ng-attr-selected={{profile.country_id == country.id}} value="{{country.id}}">{{country.country_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="division_id">Divisions</label>                        
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="division_id" ng-model="profile.division_id" ng-disabled="!profile.country_id" ng-change="changeDivision('profile')">
                                                        <option value="">--Select Division--</option>
                                                        <option ng-repeat="division in divisions" ng-selected="profile.division_id == division.id" value="{{division.id}}">{{division.division_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="district_id">District</label>                     
                                                <div class="col-sm-8">       
                                                    <select class="form-control" id="district_id" ng-model="profile.district_id" ng-disabled="!profile.division_id" ng-change="changeDistrict('profile')">
                                                        <option value="">--Select District--</option>
                                                        <option ng-repeat="district in districts" ng-selected="profile.district_id == district.id" value="{{district.id}}">{{district.district_name}}</option>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="area_id">Area</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="area_id" ng-model="profile.area_id" ng-disabled="!profile.district_id" ng-change="changeArea('profile')">
                                                        <option value="">--Select Area--</option>
                                                        <option ng-repeat="area in areas" ng-selected="profile.area_id == area.id" value="{{area.id}}">{{area.area_name}}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="sub_area_id">Sub Area</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="sub_area_id" ng-model="profile.sub_area_id" ng-disabled="!profile.area_id" ng-change="changeSubArea('profile')">
                                                        <option value="">--Select Sub Area--</option>
                                                        <option ng-repeat="sub_area in sub_areas" ng-selected="profile.sub_area_id == sub_area.id" value="{{sub_area.id}}">{{sub_area.sub_area_name}}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="road_id">Road</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="road_id" ng-model="profile.road_id" ng-disabled="!profile.sub_area_id">
                                                        <option value="">--Select Road--</option>
                                                        <option ng-repeat="road in roads" ng-selected="profile.road_id == road.id" value="{{road.id}}">{{road.road_name}}</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-11 text-right">
                                            <a id="buttonsuccess" id="btnNext" ng-disabled="lco_profile.$invalid" ng-click="updateProfile()" class="btn btn-success btnNext" >Update Profile</a>
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
                                <div class="form-horizontal">
                                    <div class="col-md-12">
                                        <div class="panel-heading" style="padding-bottom: 20px">
                                            <h4>Login Information: </h4>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="username">Username <span style="color:red">*</span></label>                      
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="username" ng-model="profile.username" value="" placeholder="Enter Username" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="password">Password </label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" id="password" ng-model="profile.password" ng-change="checkPassWordStrength()" value="" placeholder="Enter Your Password" required="required">
                                                    <div ng-if="notStrongPassFlag" style="color: red;">{{pass_message}}</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="re_password">Retype Password </label>
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" id="re_password" ng-model="profile.re_password" ng-change="checkRePassword()" placeholder="Enter Your Password" required="required">
                                                    <div ng-if="checkRePasswordFlag" style="color: red">{{re_pass_message}}</div>
                                                </div>
                                            </div>

                                            <div class="form-group" ng-if="user_type!='mso'">
                                                <label class="col-sm-4 control-label" for="re_password">Role<span style="color:red">*</span></label>
                                                <div class="col-sm-8">
                                                    <select ng-model="profile.role_id" class="form-control" required="required">
                                                        <option ng-repeat="role in roles" ng-selected="role.id == profile.role_id" value="{{role.id}}">{{role.role_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="margin-left:190px;" for="password"> <input type="checkbox" ng-model="profile.is_remote_access_enabled" ng-checked="profile.is_remote_access_enabled=='1'" ng-true-value="'1'" ng-false-value="'0'"/> Remote Login Access Disabled</label>
                                            </div>
                                            <div class=" form-group">
                                                <label class="col-sm-4 control-label" for="lsp_type">LSP TYPE</label>
                                                <div class="col-sm-8">
                                                    <select ng-model="profile.lsp_type_id" class="form-control" required="required">
                                                        <option ng-repeat="lsp in lsp_types" value="{{lsp.id}}">{{lsp.type_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-11">
                                            <a id="btnNext" ng-disabled="isSaveLoginDisabled()" ng-click="updateLogin()" ng-disabled="isSaveLoginDisabled()" class="btn btn-success btnNext" >Save Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="billing_address" class="tab-pane active" ng-show="tabs.billing_address" style="padding-top:10px;">
                            <form class="form-horizontal" name="lco_billing_address" id="lco_billing_address" method="post" ng-submit="saveBillingAddress()" >
                                <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                                    <h4>Billing Address</h4>
                                    ( Same as Profile <input type="checkbox" ng-checked="billing_address.is_same_as_profile=='1'" ng-model="copy_profile" ng-change="setBillingAddress()" ng-true-value="'1'" ng-false-value="'0'"/>)
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="full_name">Full Name <span style="color:red">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" ng-model="billing_address.name" placeholder="Full Name" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="email">Email <span style="color:red">*</span></label>
                                                <div class="col-sm-7">
                                                    <input type="email" class="form-control" id="email" ng-model="billing_address.email" placeholder="Enter Your Email" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="address1"> Address Line 1</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" ng-model="billing_address.address1" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="address2"> Address Line 2</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" ng-model="billing_address.address2" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="contact"> Mobile No </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" maxlength="11" ng-model="billing_address.contact" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label" for="billing_contact"> 2<sup>nd</sup> Mobile No <br/>( Same as above <input type="checkbox" ng-checked="billing_address.is_same_as_contact=='1'" ng-model="is_same_as_billing_contact" ng-change="setBillingContact()" ng-true-value="'1'" ng-false-value="'0'"/>)</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" maxlength="11" ng-model="billing_address.billing_contact" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">                          

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="country_id">Country</label>                      
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="country_id" ng-model="billing_address.country_id" ng-change="changeCountry('billing')">
                                                        <option value="">---Select Country---</option>
                                                        <option ng-repeat="country in countries" ng-attr-selected={{billing_address.country_id == country.id}} value="{{country.id}}">{{country.country_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="division_id">Divisions</label>                        
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="division_id" ng-model="billing_address.division_id" ng-change="changeDivision('billing')" ng-disabled="!billing_address.country_id">
                                                        <option value="">--Select Division--</option>
                                                        <option ng-repeat="division in divisions" ng-attr-selected={{billing_address.division_id == division.id}} value="{{division.id}}">{{division.division_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="district_id">District</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="district_id" ng-model="billing_address.district_id" ng-change="changeDistrict('billing')" ng-disabled="!billing_address.division_id">
                                                        <option value="">--Select District--</option>
                                                        <option ng-repeat="district in districts" ng-attr-selected={{billing_address.district_id == district.id}} value="{{district.id}}">{{district.district_name}}</option>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="area_id">Area</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="area_id" ng-model="billing_address.area_id" ng-change="changeArea('billing')" ng-disabled="!billing_address.district_id">
                                                        <option value="">--Select Area--</option>
                                                        <option ng-repeat="area in areas" ng-attr-selected={{billing_address.area_id == area.id}} value="{{area.id}}">{{area.area_name}}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="sub_area_id">Sub Area</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="sub_area_id" ng-model="billing_address.sub_area_id" ng-change="changeSubArea('billing')" ng-disabled="!billing_address.area_id">
                                                        <option value="">--Select Sub Area--</option>
                                                        <option ng-repeat="sub_area in sub_areas" ng-attr-selected={{billing_address.sub_area_id == sub_area.id}} value="{{sub_area.id}}">{{sub_area.sub_area_name}}</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="road_id">Road</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="road_id" ng-model="billing_address.road_id" ng-disabled="!billing_address.sub_area_id">
                                                        <option value="">--Select Road--</option>
                                                        <option ng-repeat="road in roads" ng-attr-selected={{billing_address.road_id == road.id}} value="{{road.id}}">{{road.road_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-11 text-right">
                                          <!-- <a id="buttonsuccess" id="btnNext" ng-click="hideForm()" class="btn btn-warning btnNext pull-right" >Cancel</a> -->
                                          <input type="submit" id="buttonsuccess" ng-disabled="lco_billing_address.$invalid" class="btn btn-success pull-right" value="Save Profile"/>
                                      </div>
                                  </div>
                              </div>

                          </form> 
                      </div>
                <!-- <div id="business_region" class="tab-pane active" ng-show="tabs.business_region" style="padding-top:10px;">
                    <form name="business_region" id="business_region" method="post" >
                        <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                            <h4>Business Region</h4>
                        </div>
                        
                        
                    </form> 
                </div> -->
                <div id="photo" class="tab-pane active" ng-show="tabs.photo"  style="padding-top: 10px">
                    <form name="mso_photo" id="mso_photo" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                    <h5>Attach Photo: </h5>
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
                                  <div>
                                    <div style="margin-top:5px" class="progress">
                                        <div ng-style="{ 'width': fileUploadPhotoProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadPhotoProgress" class="ng-binding ng-hide">{{fileUploadPhotoProgress}} %</div></div>
                                    </div>
                                </div>  
                                
                                            <!-- <button ng-disabled="!uploader.isUploading" ng-click="uploader.cancelAll()" class="btn btn-warning btn-s" type="button" disabled="disabled">
                                                <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                                            </button> -->
                                        </div> 
                                    </div>
                                    <div class="col-md-3 col-md-offset-3">
                                        <img width="100" height="100" ng-show="profile.photo" ng-src="<?php echo base_url('/'); ?>{{profile.photo}}"/>
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-8">
                                        <button id="buttonsuccess" ng-disabled="!uploader.getNotUploadedItems().length" ng-click="uploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                            <span class="glyphicon glyphicon-upload"></span> Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div id="identity_verify" class="tab-pane active" ng-show="tabs.identity_verify"  style="padding-top: 10px">
                        <form name="mso_photo" id="mso_photo" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-heading">
                                        <h5 style="padding-bottom: 20px;">Attach Identity Verification Document </h5>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label>Type of Document</label>
                                                <select class="form-control" ng-model="identity.type">
                                                    <option value="">--Select Type ---</option>
                                                    <option ng-repeat="type in identity_verify_types" value="{{type}}">{{type}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="identity.type">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label>Identity Number</label>
                                                <input type="text" class="form-control" ng-model="identity.id"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" ng-if="identity.type">
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
                                        <div class="col-md-3 col-md-offset-3" > 
                                            <img width="150" height="150" ng-show="identity.identity_attachment" ng-src="<?php echo base_url('/'); ?>{{identity.identity_attachment}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                           <button id="buttonsuccess" ng-disabled="!identityUploader.getNotUploadedItems().length" ng-click="identityUploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                                <span class="glyphicon glyphicon-upload"></span> Upload
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                                        <option value="">--Select Region--</option>
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
            <div id="business_modality" class="tab-pane active" ng-show="tabs.business_modality" style="padding-top:10px;">
                <form name="lco_business_modality" id="lco_business_modality" method="post" ng-submit="saveModality()" class="form-horizontal">
                    <div class="col-md-12">
                        <div class="panel-heading">
                            <h5 style="padding-bottom: 20px;">Business Modality </h5>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Business Modality</label>
                                <textarea rows="10" id="business_modality" ng-model="profile.business_modality" class="form-control" style="height: 150px ! important;" required="required"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" id="buttonsuccess" class="btn btn-success btnNext" ng-disabled="lco_business_modality.$invalid" value="Save Business Modality"/>
                            </div>
                        </div>                          
                    </div> 
                </form>
            </div>
        </div> 
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




