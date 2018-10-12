<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="EditMSOProfile" ng-cloak>

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
                    
                    <h4 class="widgettitle"> Edit MSO User [<small>{{profile.mso_name}}</small>]

                        <a href="<?php echo site_url('mso/view/{{profile.token}}' ) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
                        <a href="<?php echo site_url('mso'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
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
                            <li ng-class="{active:tabs.login}"><a class="tab_top" ng-click="setTab('login')">Login</a></li>
                            <!-- <li ng-class="{active:tabs.contract}"><a ng-click="setTab('contract')">Contacts</a></li> -->
                            <li ng-class="{active:tabs.photo}"><a class="tab_top" ng-click="setTab('photo')">Photo</a></li>
                            <li ng-class="{active:tabs.identity_verify}"><a class="tab_top" ng-click="setTab('identity_verify')">Identity Verification</a></li>
                        </ul>
                    </div>
                    

                    <div class="tab-content">
                        <div id="profile" class="tab-pane active" ng-show="tabs.profile">
                            <form class="form-horizontal" name="mso" id="mso" method="post">
                                <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                                    <h4>Profile Information</h4>
                                </div>
                                <div class="row" ng-if="!loader">
                                    <div class="col-md-12">
                                        <div class="col-md-5 col-md-offset-1">

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="full_name">Full Name <span class="text-danger">*</span></label>						
                                                <div class="col-sm-8">
                                                    <input type="hidden" name="created_by" value="<?php echo $user_info->id; ?>">
                                                    <input type="text" class="form-control" ng-model="profile.mso_name" placeholder="Full Name" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="email">Email <span style="color:red">*</span></label>                       
                                                <div class="col-sm-8">
                                                    <input type="email" class="form-control" id="email" ng-model="profile.email" placeholder="Enter Your Email" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="address1"> Address Line 1</label>						
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" ng-model="profile.address1" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="address2"> Address Line 2</label>						
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" ng-model="profile.address2" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="address2"> Mobile No</label>                       
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" maxlength="11" ng-model="profile.contact" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="address2"> Billing Mobile No</label>                       
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" maxlength="11" ng-model="profile.billing_contact" />
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-5">							

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="country_id">Country</label>						
                                                <div class="col-sm-8">
                                                    
                                                    <select class="form-control" id="country_id" ng-model="profile.country_id" >
                                                        <option value="">---Select Country---</option>
                                                        <option ng-repeat="country in countries" ng-attr-selected={{profile.country_id == country.id}} value="{{country.id}}">{{country.country_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="division_id">Divisions</label>						
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="division_id" ng-model="profile.division_id" disabled>
                                                        <option value="">--Select Division--</option>
                                                        <option ng-repeat="division in divisions" ng-attr-selected={{profile.division_id == division.id}} value="{{division.id}}">{{division.division_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="district_id">District</label>						
                                                <div class="col-sm-8">
                                                    
                                                    <select class="form-control" id="district_id" ng-model="profile.district_id" disabled>
                                                        <option value="">--Select District--</option>
                                                        <option ng-repeat="district in districts" ng-attr-selected={{profile.district_id == district.id}} value="{{district.id}}">{{district.district_name}}</option>
                                                    </select>
                                                    
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="area_id">Area</label>						
                                                <div class="col-sm-8">

                                                    <select class="form-control" id="area_id" ng-model="profile.area_id" disabled>
                                                        <option value="">--Select Area--</option>
                                                        <option ng-repeat="area in areas" ng-attr-selected={{profile.area_id == area.id}} value="{{area.id}}">{{area.area_name}}</option>
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="sub_area_id">Sub Area</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="sub_area_id" ng-model="profile.sub_area_id" disabled>
                                                        <option value="">--Select Sub Area--</option>
                                                        <option ng-repeat="sub_area in sub_areas" ng-attr-selected={{profile.sub_area_id == sub_area.id}} value="{{sub_area.id}}">{{sub_area.sub_area_name}}</option>
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="road_id">Road</label>                     
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="road_id" ng-model="profile.road_id" disabled>
                                                        <option value="">--Select Road--</option>
                                                        <option ng-repeat="road in roads" ng-attr-selected={{profile.road_id == road.id}} value="{{road.id}}">{{road.road_name}}</option>
                                                    </select>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-11 text-right">
                                            <input type="submit"  ng-disabled="mso.$invalid" ng-click="updateProfile()" class="btn btn-success btnNext" value="Update Profile"/>
                                            
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
                            <div  class="row">
                                <div class="form-horizontal">
                                    
                                    <div class="col-md-12">
                                        <div class="panel-heading" style="padding-bottom: 20px">
                                            <h4>Login Information: </h4>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="username">Username <!-- <span style="color:red">*</span> --></label>                      
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="username" ng-model="profile.username" value="" placeholder="Enter Username" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="password">Password <!-- <span style="color:red">*</span> --></label>                      
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" id="password" ng-model="profile.password" ng-change="checkPassWordStrength()" value="" placeholder="Enter Your Password" required="required">
                                                    <div ng-if="notStrongPass" style="color: red;">{{pass_message}}</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label style="text-align: right" class="col-sm-4 control-label" for="password">Retype Password <!-- <span style="color:red">*</span> --></label>                      
                                                <div class="col-sm-8">
                                                    <input type="password" class="form-control" id="re_password" ng-model="profile.re_password" ng-change="checkRePassword()" value="" onchange ="checkPass()" placeholder="Enter Your Password" required="required">
                                                    <div ng-if="check_re_password" style="color: red">{{re_pass_message}}</div>
                                                </div>                                            
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Role<span style="color:red">*</span></label>
                                                <div class="col-sm-8">
                                                    <select ng-model="profile.role_id" class="form-control" required="required">
                                                        <option ng-repeat="role in roles" ng-selected="role.id == profile.role_id" value="{{role.id}}">{{role.role_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-2 col-md-offset-2">
                                            <a id="buttonsuccess" id="btnNext" ng-disabled="isSaveLoginDisabled()" ng-click="updateLogin()" class="btn btn-success btnNext" >Save Login Info</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <style type="text/css">
                                        .form-control{height: 34px !important;}
                                        .progress {height: 40px !important;}
                                        .progress-bar {font-size: 18px; line-height: 38px; color: springgreen;}
                                        .input-file { position: relative;} /* Remove margin, it is just for stackoverflow viewing */
                                        .input-file .input-group-addon { border: 0px; padding: 0px; }
                                        .input-file .input-group-addon .btn { border-radius: 0 4px 4px 0 }
                                        .input-file .input-group-addon input { cursor: pointer; position:absolute; width: 72px; z-index:2;top:0;right:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0; background-color:transparent; color:transparent; }
                                    </style>
                        <div id="photo" class="tab-pane active" ng-show="tabs.photo"  style="padding-top: 10px">
                            <form class="form-horizontal" name="mso_photo" id="mso_photo" class="form-horizontal" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel-heading">
                                            <h5>Attach Photo:</h5>
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
                                               <div style="margin-top:5px"  class="progress">
                                                    <div ng-style="{ 'width': fileUploadPhotoProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadPhotoProgress" class="ng-binding ng-hide">{{fileUploadPhotoProgress}} %</div></div>
                                                </div>
                                            </div>      
                                        </div>
                                        <div class="col-md-3 col-md-offset-3 ">
                                             <img width="100" height="100" ng-src="<?php echo base_url('/'); ?>{{profile.photo}}"/>
                                        </div> 
                                        <div class="col-md-12">
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
                                            <h5 style="padding-bottom: 20px;">Attach Identity Verfication Document [<small>{{profile.mso_name}}</small>]</h5>
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
                                                     <input type="text" class="form-control" maxlength="20" ng-model="identity.id"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" ng-if="identity.type">
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
                                                          <input type="file" style="height: 34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="identityUploader" nv-file-select="">
                                                      </a>
                                                    </span>
                                                </div>
                                                <div style="margin-top:5px"  class="progress">
                                                    <div ng-style="{ 'width': fileUploadIdentityProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadIdentityProgress" class="ng-binding ng-hide">{{fileUploadIdentityProgress}} %</div></div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-md-3 col-md-offset-3">
                                              <img width="150" height="150" ng-src="<?php echo base_url('/'); ?>{{identity.identity_attachment}}"/>
                                        </div> 
                                        <div class="col-md-12">
                                             <button id="buttonsuccess" ng-disabled="!identityUploader.getNotUploadedItems().length" ng-click="identityUploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                                <span class="glyphicon glyphicon-upload"></span> Upload
                                            </button>
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



