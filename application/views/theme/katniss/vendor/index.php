<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    fieldset{padding: 10px}
    legend{margin-top: 15px; font-size: 16px}
</style>

<div id="container" ng-controller="CreateVendor" ng-cloak>

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

    <div class="panel panel-default" ng-show="showFrm">

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> New Vendor
                        <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="panel-heading">
                            <ul class="tab_nav nav nav-tabs">
                                <li class="active"><a data-toggle="tab" class="tab_top" href="#home">Add New</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-3" style="padding-bottom: 20px">

                                </div>
                            </div>

                            <form method="POST" name="vendorAdd" class="form-horizontal" ng-submit="saveVendor()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="name">Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="name" ng-model="formData.name" placeholder="Enter Vendor Name" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address">Address <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <textarea type="text" class="form-control" id="address" placeholder="Enter Vendor Address"  ng-model="formData.address"  required="required"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="phone_number">Contact Number (Land Phone)<span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="phone_number" ng-model="formData.phone_number" placeholder="Enter Vendor Phone Number" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="email">Contact Email <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="email" class="form-control" id="email" ng-model="formData.email" placeholder="Enter Vendor Email" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="mobile_number">Contact Cell Phone <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="mobile_number" ng-model="formData.mobile_number" placeholder="Enter Vendor Contact Cell Phone Number" required="required"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="contact_person_1">
                                                <legend>Contact Person - 1</legend>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1">Name</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1" ng-model="formData.contact_person_1" placeholder="Enter Contact Name"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_email">Email</label>
                                                    <div class="col-sm-4">
                                                        <input type="email" class="form-control" id="contact_person_1_email" ng-model="formData.contact_person_1_email" placeholder="Enter Contact Email"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_phone">Cell Phone</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_phone" ng-model="formData.contact_person_1_phone" placeholder="Enter Contact Cell Phone Number"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_designation">Designation</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_designation" ng-model="formData.contact_person_1_designation" placeholder="Enter Contact Designation"/>
                                                    </div>
                                                </div>

                                            </fieldset>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <fieldset class="contact_person_2">
                                                <legend>Contact Person - 2</legend>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2">Name</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_2" ng-model="formData.contact_person_2" placeholder="Enter Contact Name"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2_email">Email</label>
                                                    <div class="col-sm-4">
                                                        <input type="email" class="form-control" id="contact_person_2_email" ng-model="formData.contact_person_2_email" placeholder="Enter Contact Email"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2_phone">Cell Phone</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_2_phone" ng-model="formData.contact_person_2_phone" placeholder="Enter Contact Cell Phone Number"/>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2_designation">Designation</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_2_designation" ng-model="formData.contact_person_2_designation" placeholder="Enter Contact Designation"/>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="col-md-3" style="padding-bottom: 20px">

                                            </div>
                                        </div>
                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" ng-disabled="!vendorAdd.$valid" class="btn btn-success btnNext"> Save Vendor </button>
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
                    <div class="col-md-12">
                        <h4 class="widgettitle">
                            Vendor LIST
                            <a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New Vendor </a>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12" ng-if="!delete_flag">
                    <kendo-grid options="mainGridOptions" id="stp-grid">
                    </kendo-grid>
                </div>
                <div class="col-md-12 text-center" ng-if="delete_flag">
                    <form>
                        <p><strong>Are you sure to delete this vendor</strong></p>
                        <p>
                            <input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
                            <input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .contact_person_1 legend {margin-left: 95px;     font-size: 17px;}
    .contact_person_2 legend {margin-left: 95px;     font-size: 17px;}
    .contact_person_2 {margin-top: 10px;} 
</style>