<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="CreatePayments" ng-cloak>

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
    

<div class="panel panel-default" ng-show="!showFrm">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-heading">
                    <h4 class="widgettitle"> Payments </h4>
                <span class="clearfix"></span>
            </div>
            <hr/>
        </div>
        <div class="col-md-12">
            <div class="panel-body">
            <div class="col-md-12">
                <ul class="tab_nav nav nav-tabs">
                    <li ng-class="{active:tabs.cash}"><a class="tab_top" ng-click="setTab('cash')">Cash</a></li>
                    <li ng-class="{active:tabs.bank}"><a class="tab_top" ng-click="setTab('bank')">Bank</a></li>
                    <li ng-class="{active:tabs.pos}"><a class="tab_top" ng-click="setTab('pos')">POS</a></li>
                    <li ng-class="{active:tabs.bkash}"><a class="tab_top" ng-click="setTab('bkash')">Baksh</a></li>
                </ul>
            </div>
                <div class="tab-content">
                    <div id="profile" class="tab-pane active" ng-show="tabs.cash">
                        <form class="form-horizontal" name="mso" id="mso" method="post" action="<?php echo site_url('profile/get_mso_profile_data'); ?>">
                            <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                                <h4>Cash Information</h4>
                            </div>
                           <!--  <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-5 col-md-offset-1">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="created_by">Full Name <span style="color:red">*</span></label>						
                                            <div class="col-sm-8">
                                                <input type="hidden" name="created_by" value="<?php echo $user_info->id; ?>">
                                                <input type="text" class="form-control" ng-model="profile.full_name" placeholder="Full Name" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="email">Email <span style="color:red">*</span></label>                       
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" id="email" ng-model="profile.email" placeholder="Enter Your Email" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label  class="col-sm-4 control-label" for="address1"> Address Line 1</label>						
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" ng-model="profile.address1" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address2"> Address Line 2</label>						
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" ng-model="profile.address2" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-sm-4 control-label" for="address2"> Mobile No</label>                       
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" maxlength="11" ng-model="profile.contact" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address2"> Billing Mobile No</label>                       
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" maxlength="11" ng-model="profile.billing_contact" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">							

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="country_id">Country</label>						
                                            <div class="col-sm-8">
                                                <select class="form-control" id="country_id" ng-model="profile.country_id" >
                                                    <option value="">---Select Country---</option>
                                                    <?php if ($countries) { ?>
                                                        <?php foreach ($countries as $country) { ?> 
                                                            <option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="division_id">Divisions</label>						
                                            <div class="col-sm-8">
                                                <select class="form-control" id="division_id" ng-model="profile.division_id" disabled>
                                                    <option value="">--Select Division--</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="exampleInputPassword1">District</label>						
                                            <div class="col-sm-8">
                                                <select class="form-control" id="district_id" ng-model="profile.district_id" disabled>
                                                    <option value="">--Select District--</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="area_id">Area</label>						
                                            <div class="col-sm-8">
                                                <select class="form-control" id="area_id" ng-model="profile.area_id" disabled>
                                                    <option value="">--Select Area--</option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="sub_area_id">Sub Area</label>                     
                                            <div class="col-sm-8">
                                                <select class="form-control" id="sub_area_id" ng-model="profile.sub_area_id" disabled>
                                                    <option value="">--Select Sub Area--</option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="road_id">Road</label>                     
                                            <div class="col-sm-8">
                                                <select class="form-control" id="road_id" ng-model="profile.road_id" disabled>
                                                    <option value="">--Select Road--</option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-11 text-right">
                                        <a id="buttonsuccess" id="btnNext" ng-click="saveProfile()" class="btn btn-success btnNext" >Save Profile</a>
                                        <a id="buttoncancel" id="btnNext" ng-click="hideForm()"  class="btn btn-warning btnNext" >Cancel</a>
                                    </div>
                                </div>
                            </div> -->
                        </form> 
                    </div>
                    <div id="login" class="tab-pane active" ng-show="tabs.login">
                        <form class="form-horizontal" name="mso_contract" id="mso_contract" method="post" ng-submit="saveContract()">
                            <div class="row">
                                <hr/>
                                <div class="col-md-12">
                                    <div class="panel-heading" style="padding-bottom: 20px">
                                        <h4>Bank Information</h4>
                                    </div>

                                    <!-- <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="username">Username <span style="color:red">*</span></label>                      
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="username" ng-model="profile.username" value="" placeholder="Enter Username" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="password">Password <span style="color:red">*</span></label>                      
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control" id="password" ng-model="profile.password" value="" placeholder="Enter Your Password" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="password">Retype Password <span style="color:red">*</span></label>                      
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control" id="re_password" ng-model="profile.re_password" onchange ="checkPass()" placeholder="Enter Your Password" required="required">
                                                <div id="errMsg" style="color: red"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <a id="buttonsuccess" id="btnNext" ng-click="saveLogin()" class="btn btn-success btnNext" >Save Login Info</a>
                                    </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="photo" class="tab-pane active" ng-show="tabs.photo">
                        <form class="form-horizontal" name="mso_photo" id="mso_photo" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                        <h5>POS</h5>
                                    </div>

                                    <!-- <div class="col-md-12">
                                            <label>Upload Photo</label>
                                        </div>
                                        <div class="col-md-4">
                                            <div ng-hide="messageState">
                                                <div class="input-group input-file">
                                                    <div class="form-control"> 
                                                   
                                                    </div>
                                                    <span class="input-group-addon">
                                                        <a href="javascript:;" class="btn btn-primary">
                                                          Browse
                                                          <input type="file" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="uploader" nv-file-select="">
                                                        </a>
                                                      </span>
                                                </div>
                                               <div style="margin-top:5px"  class="progress">
                                                    <div ng-style="{ 'width': uploader.progress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="uploader.progress" class="ng-binding ng-hide">{{uploader.progress}} %</div></div>
                                                </div>
                                            </div>      
                                        </div>
                                        <div class="col-md-3 col-md-offset-3 ">
                                            <img width="100" height="100" ng-src="data:image/png;base64,{{profile.photo}}"/>
                                        </div> 
                                        <div class="col-md-12">
                                            <button id="buttonsuccess" ng-disabled="!uploader.getNotUploadedItems().length" ng-click="uploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                                <span class="glyphicon glyphicon-upload"></span> Upload
                                            </button>
                                        </div> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="identity_verify" class="tab-pane active" ng-show="tabs.identity_verify">
                        <form class="form-horizontal" name="mso_photo" id="mso_photo" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-heading" style="padding-bottom: 20px">
                                        <h5>Baksh</h5>
                                    </div>
                                    <!-- <div class="form-group">
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
                                    <div class="form-group" ng-if="identity.type">
                                        <div class="col-md-12">

                                            <div class="col-md-12">
                                                <label>Upload Identity Document</label>
                                            </div>
                                            <div class="col-md-4">
                                                <div ng-hide="messageState">
                                                    <div class="input-group input-file">
                                                        <div class="form-control"> 
                                                       
                                                        </div>
                                                       <span class="input-group-addon">
                                                            <a href="javascript:;" class="btn btn-primary">
                                                                Browse
                                                            <input type="file" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="identityUploader" nv-file-select="">
                                                            </a>
                                                        </span>
                                                    </div>
                                                    <div style="margin-top:5px"  class="progress">
                                                        <div ng-style="{ 'width': identityUploader.progress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="identityUploader.progress" class="ng-binding ng-hide">{{identityUploader.progress}} %</div></div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="col-md-3 col-md-offset-3">
                                                  <img width="150" height="150" ng-src="data:image/png;base64,{{identity.identity_attachment}}"/>
                                            </div> 
                                            <div class="col-md-12">
                                                <button id="buttonsuccess" ng-disabled="!identityUploader.getNotUploadedItems().length" ng-click="identityUploader.uploadAll()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                                    <span class="glyphicon glyphicon-upload"></span> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div> -->
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
        else if(re_pass == pass)
        {
            document.getElementById("errMsg").style.display = "none";
        }
    }
</script>





