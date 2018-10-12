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
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="EditSubscriberProfile" ng-cloak>


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

                    <h4 class="widgettitle"> Edit Subscriber <span
                            ng-if="profile.id">[ {{profile.subscriber_name}} ]</span>
                        <!--<span ng-if="profile.id">[Available Balance:
                                    <span ng-if="balance==0" class="text-danger"><strong>{{balance}}</strong></span>
                                    <span ng-if="balance!=0" class="text-success"><strong>{{balance}}</strong></span>
                                    ]</span>-->

                        <a href="<?php echo site_url('subscriber/view/{{profile.token}}'); ?>"
                           class="btn btn-success btn-sm pull-right" style="margin-right: 10px"><i
                                class="fa fa-search"></i> View</a>
                        <a href="<?php echo site_url('subscriber'); ?>" id="buttoncancel"
                           class="btn btn-danger btn-sm pull-right" style="margin-right: 10px"><i
                                class="fa fa-arrow-left"></i> Back</a>
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
                            <form name="subscriber_profile" id="subscriber_profile" method="post">
                                <div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
                                    <h4>Profile Information</h4>
                                </div>
                                <div class="row" ng-if="!loader">
                                    <div class="col-md-12">
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Full Name <span
                                                        class="text-danger">*</span></label>

                                                <div class="margin-bottom-sm">
                                                    <input type="text" class="form-control"
                                                           ng-model="profile.subscriber_name" placeholder="Full Name"
                                                           required="required">
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
                                                <label for="address2"> Mobile Number <span class="text-danger">*</span></label>

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
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Divisions</label>

                                                <div class="margin-bottom-sm">
                                                    <select class="form-control" id="division_id"
                                                            ng-model="profile.division_id" disabled>
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
                                                            ng-model="profile.district_id" disabled>
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
                                                            disabled>
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
                                                            ng-model="profile.sub_area_id" disabled>
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
                                                            disabled>
                                                        <option value="">--Select Road--</option>
                                                        <option ng-repeat="road in roads"
                                                                ng-selected="road.id==profile.road_id"
                                                                value="{{road.id}}">{{road.road_name}}
                                                        </option>

                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Type of Identity Number</label>
                                                <select class="form-control" ng-model="profile.identity_type">
                                                    <option value="">--Select Type ---</option>
                                                    <option ng-repeat="type in identity_verify_types"
                                                            ng-selected="type==profile.identity_type" value="{{type}}">
                                                        {{type}}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group" ng-if="profile.identity_type">
                                                <label>Identity Number</label>
                                                <input type="text" class="form-control" maxlength="20"
                                                       ng-model="profile.identity_number"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <a id="buttonsuccess" id="btnNext" ng-disabled="subscriber_profile.$invalid"
                                               ng-click="updateProfile()" class="btn btn-success btnNext">Update
                                                Profile</a>

                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center" ng-show="loader">

                                    <h3>Loading</h3>
                                    <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF'); ?>"/>
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
                                                <input type="text" class="form-control" id="username"
                                                       ng-model="profile.username" value="" placeholder="Enter Username"
                                                       required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password</label>

                                            <div class="margin-bottom-sm">
                                                <input type="password" class="form-control" id="password"
                                                       ng-model="profile.password" ng-change="checkPassWordStrength()"
                                                       value="" placeholder="Enter Your Password" required="required">

                                                <div ng-if="notStrongPassFlag" style="color: red;">{{pass_message}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Retype Password</label>

                                            <div class="margin-bottom-sm">
                                                <input type="password" class="form-control" id="password"
                                                       ng-model="profile.re_password" ng-change="checkRePassword()"
                                                       value="" placeholder="Enter Your Password" required="required">

                                                <div ng-if="checkRePasswordFlag" style="color: red">
                                                    {{re_pass_message}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="password"> <input type="checkbox"
                                                                          ng-model="profile.is_remote_access_enabled"
                                                                          ng-checked="profile.is_remote_access_enabled=='1'"
                                                                          ng-true-value="'1'" ng-false-value="'0'"/>
                                                Remote Login Access Disabled</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <a id="btnNext" ng-disabled="isSaveLoginDisabled()" ng-click="updateLogin()"
                                           class="btn btn-success btnNext">Save Profile</a>
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
    function checkPass() {
        var re_pass = document.getElementById('re_password').value;
        var pass = document.getElementById('password').value;
        if (re_pass != pass) {
            document.getElementById("errMsg").innerHTML = "Password Didn't Match!";
        }
    }
</script>




