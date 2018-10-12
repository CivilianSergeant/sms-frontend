<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .col-md-5 {
        width: 42.66666667%;
    }

    .lightgreen {
        background: #92f4c7;
    }

    .table-bordered {
        border: 1px solid;
    }

    .table-bordered > tbody > tr > td, .table-bordered > thead > tr > th {
        border: 1px solid #347054;
    }

    .table-striped > tbody >

    tr
    (
    2
    n

    )
    {
        background: red
    ;
    }
</style>
<div id="container" ng-controller="CreateSubscriberProfile" ng-cloak>

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

    <div class="alert alert-success" ng-show="success_message" ng-model="success_message">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_message}}
    </div>


    <div class="panel panel-default" ng-if="showFrm">

        <div class="row">


            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle">
                        Add New Subscriber [ {{((token!=null)? profile.full_name:'Unsaved Profile')}} ]
                        <span ng-if="token">[Available Balance: 
                                    <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                    <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                    ]</span>
                        <a id="buttoncancel" ng-click="hideForm()" class="btn btn-danger btn-sm pull-right"><i
                                class="fa fa-close"></i> Close</a>
                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="col-md-12">
                        <ul class="tab_nav nav nav-tabs">
                            <li ng-class="{active:tabs.profile}"><a class="tab_top"
                                                                    ng-click="setTab('profile')">Profile</a></li>
                            <li ng-class="{active:tabs.login}"><a class="tab_top" ng-click="setTab('login')">Login
                                    Info</a></li>
                        </ul>
                    </div>


                    <div class="tab-content">
                        <div id="profile" class="tab-pane active" ng-show="tabs.profile">
                            <form name="subscriber_profile" id="subscriber_profile" method="post"
                                  ng-submit="saveProfile()">
                                <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                                    <h4>Profile Information</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Full Name <span
                                                        class="text-danger">*</span></label>

                                                <div class="margin-bottom-sm">
                                                    <input type="hidden" name="created_by"
                                                           value="<?php echo $user_info->id; ?>">
                                                    <input type="text" class="form-control" ng-model="profile.full_name"
                                                           placeholder="Full Name" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>

                                                <div class="margin-bottom-sm">
                                                    <input type="email" class="form-control" id="email"
                                                           ng-model="profile.email" placeholder="Enter Your Email"
                                                           required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address1"> Address Line 1</label>

                                                <div class="margin-bottom-sm">
                                                    <input type="text" class="form-control"
                                                           ng-model="profile.address1"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address2"> Address Line 2</label>

                                                <div class="margin-bottom-sm">
                                                    <input type="text" class="form-control"
                                                           ng-model="profile.address2"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="address2"> Mobile number <span class="text-danger">*</span></label>

                                                <div class="margin-bottom-sm">
                                                    <input type="text" class="form-control" maxlength="11"
                                                           ng-model="profile.contact" required="required"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="address2"> 2<sup>nd</sup> Mobile Number ( Same as above
                                                    <input type="checkbox" ng-model="profile.is_same_as_contact"
                                                           ng-change="changeBillingContact()" ng-true-value="1"
                                                           ng-false-value="0"/>)</label>

                                                <div class="margin-bottom-sm">
                                                    <input type="text" class="form-control" maxlength="11"
                                                           ng-model="profile.billing_contact"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Country</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="country_id"
                                                            ng-model="profile.country_id">
                                                        <option value="">---Select Country---</option>
                                                        <option ng-repeat="country in countries"
                                                                ng-attr-selected={{profile.country_id
                                                        == country.id}} value="{{country.id}}">
                                                        {{country.country_name}}</option>
                                                        <!-- <option ng-if="lco_profile.id" ng-repeat="country in countries" ng-selected="{{lco_profile.country_id == country.id}}" value="{{country.id}}">{{country.country_name}}s</option> -->
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Divisions</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="division_id"
                                                            ng-model="profile.division_id"
                                                            ng-disabled="!profile.country_id">
                                                        <option value="">--Select Division--</option>
                                                        <option ng-repeat="division in divisions"
                                                                ng-attr-selected={{profile.division_id
                                                        == division.id}} value="{{division.id}}">
                                                        {{division.division_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleInputPassword1">District</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="district_id"
                                                            ng-model="profile.district_id"
                                                            ng-disabled="!profile.division_id">
                                                        <option value="">--Select District--</option>
                                                        <option ng-repeat="district in districts"
                                                                ng-attr-selected={{profile.district_id
                                                        == district.id}} value="{{district.id}}">
                                                        {{district.district_name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Area</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="area_id" ng-model="profile.area_id"
                                                            ng-disabled="!profile.district_id">
                                                        <option value="">--Select Area--</option>
                                                        <option ng-repeat="area in areas"
                                                                ng-attr-selected={{profile.area_id
                                                        == area.id}} value="{{area.id}}">{{area.area_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Sub Area</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="sub_area_id"
                                                            ng-model="profile.sub_area_id"
                                                            ng-disabled="!profile.area_id">
                                                        <option value="">--Select Sub Area--</option>
                                                        <option ng-repeat="sub_area in sub_areas"
                                                                ng-attr-selected={{profile.sub_area_id
                                                        == sub_area.id}} value="{{sub_area.id}}">
                                                        {{sub_area.sub_area_name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Road</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="road_id" ng-model="profile.road_id"
                                                            ng-disabled="!profile.sub_area_id">
                                                        <option value="">--Select Road--</option>
                                                        <option ng-repeat="road in roads"
                                                                ng-attr-selected={{profile.road_id
                                                        == road.id}} value="{{road.id}}">{{road.road_name}}</option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Type of Identity Number</label>
                                                <select class="form-control" ng-model="profile.type">
                                                    <option value="">--Select Type ---</option>
                                                    <option ng-repeat="type in identity_verify_types" value="{{type}}">
                                                        {{type}}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group" ng-if="profile.type">
                                                <label>Identity Number</label>
                                                <input type="text" class="form-control" maxlength="20"
                                                       ng-model="profile.identity_number"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="submit" id="buttonsuccess" class="btn btn-success"
                                                   ng-disabled="subscriber_profile.$invalid" value="Save Profile"/>
                                            <a id="buttoncancel" id="btnNext" ng-click="hideForm()"
                                               class="btn btn-warning btnNext">Cancel</a>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                        <div id="login" class="tab-pane active" ng-show="tabs.login" style="padding-top:10px;">
                            <form name="subscriber_login" id="subscriber_login" method="post" ng-submit="saveLogin()">
                                <div class="row">
                                    <hr/>
                                    <div class="col-md-12">
                                        <div class="panel-heading" style="padding-bottom: 20px">
                                            <h4>Login Information:</h4>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="username">Username</label>

                                                <div class="margin-bottom-sm">
                                                    <input type="text" class="form-control" id="username"
                                                           ng-model="profile.username" value=""
                                                           placeholder="Enter Username" required="required">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Password</label>

                                                <div class="margin-bottom-sm">
                                                    <input type="password" class="form-control" id="password"
                                                           ng-model="profile.password"
                                                           ng-change="checkPassWordStrength()" value=""
                                                           placeholder="Enter Your Password" required="required">

                                                    <div ng-if="notStrongPassFlag" style="color: red;">
                                                        {{pass_message}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Retype Password</label>

                                                <div class="margin-bottom-sm">
                                                    <input type="password" class="form-control" id="password"
                                                           ng-model="profile.re_password" ng-change="checkRePassword()"
                                                           value="" placeholder="Enter Your Password"
                                                           required="required">

                                                    <div ng-if="checkRePasswordFlag" style="color: red">
                                                        {{re_pass_message}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="password"><input type="checkbox"
                                                                             ng-model="profile.is_remote_access_enabled"
                                                                             ng-true-value="'1'" ng-false-value="'0'"/>
                                                    Remote Login Access Disabled</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="submit" ng-disabled="isSaveLoginDisabled()" id="buttonsuccess"
                                                   ng-diabled="subscriber_login.$invalid" class="btn btn-success"
                                                   value="Save Login Info"/>
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


    <div class="panel panel-default" ng-if="!showFrm">
        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> Members

                        <a ng-click="showForm()" class="btn btn-success btn-sm pull-right"><i
                                class="fa fa-plus-circle"></i> Add New Member</a>
                    </h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="col-md-12" ng-if="!loader">
                <div class="panel-body">
                    <kendo-grid options="mainGridOptions">
                    </kendo-grid>
                </div>
            </div>
            <div class="col-md-12 text-center" ng-show="loader">

                <h3>Loading</h3>
                <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF'); ?>"/>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function checkPass() {
        var re_pass = document.getElementById('re_password').value;
        var pass = document.getElementById('password').value;
        if (re_pass != pass) {
            document.getElementById("errMsg").innerHTML = "Password Didn't Match!";
        }
    }
</script>






