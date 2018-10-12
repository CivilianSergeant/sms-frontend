<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    fieldset{padding: 10px}
    legend{margin-top: 15px; font-size: 16px}
</style>
<script>
    var vendorId = "<?php echo $id; ?>";
</script>

<div id="container" ng-controller="EditVendor" ng-cloak>

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
                    <h4 class="widgettitle"> View Vendor
                        <a href="<?php echo site_url('vendor'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        <a href="<?php echo site_url('vendor/edit'); ?>/{{formData.id}}" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-edit"></i>  Edit </a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="row">
                <div class="col-md-12">

                </div>
                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-3" style="padding-bottom: 20px">

                                </div>
                            </div>

                            <form method="POST" name="updateVendor" class="form-horizontal" ng-submit="vendorUpdate()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="name">Name </label>
                                            <div class="col-sm-4">
                                                <input type="hidden" id="id" ng-model="formData.id">
                                                <input type="text" class="form-control" id="name" ng-model="formData.name" placeholder="Enter Vendor Name" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address">Address </label>
                                            <div class="col-sm-4">
                                                <textarea type="text" class="form-control" id="address" placeholder="Enter Vendor Address"  ng-model="formData.address"  disabled="disabled"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="phone_number">Contact Number (Land Phone)</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="phone_number" ng-model="formData.phone_number" placeholder="Enter Vendor Phone Number" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="email">Contact Email </label>
                                            <div class="col-sm-4">
                                                <input type="email" class="form-control" id="email" ng-model="formData.email" placeholder="Enter Vendor Email" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="mobile_number">Contact Cell Phone </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="mobile_number" ng-model="formData.mobile_number" placeholder="Enter Vendor Contact Cell Phone Number" disabled="disabled"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="contact_person_1">
                                                <legend>Contact Person - 1</legend>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1">Name</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1" ng-model="formData.contact_person_1" placeholder="Enter Contact Name" disabled="disabled"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_email">Email</label>
                                                    <div class="col-sm-4">
                                                        <input type="email" class="form-control" id="contact_person_1_email" ng-model="formData.contact_person_1_email" placeholder="Enter Contact Email" disabled="disabled"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_phone">Cell Phone</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_phone" ng-model="formData.contact_person_1_phone" placeholder="Enter Contact Cell Phone Number" disabled="disabled"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_designation">Designation</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_designation" ng-model="formData.contact_person_1_designation" placeholder="Enter Contact Designation" disabled="disabled"/>
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
                                                        <input type="text" class="form-control" id="contact_person_2" ng-model="formData.contact_person_2" placeholder="Enter Contact Name" disabled="disabled"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2_email">Email</label>
                                                    <div class="col-sm-4">
                                                        <input type="email" class="form-control" id="contact_person_2_email" ng-model="formData.contact_person_2_email" placeholder="Enter Contact Email" disabled="disabled"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2_phone">Cell Phone</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_2_phone" ng-model="formData.contact_person_2_phone" placeholder="Enter Contact Cell Phone Number" disabled="disabled"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_2_designation">Designation</label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_2_designation" ng-model="formData.contact_person_2_designation" placeholder="Enter Contact Designation" disabled="disabled"/>
                                                    </div>
                                                </div>
                                            </fieldset>
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
<style type="text/css">
    .contact_person_1 legend {margin-left: 95px;     font-size: 17px;}
    .contact_person_2 legend {margin-left: 95px;     font-size: 17px;}
    .contact_person_2 {margin-top: 10px;} 
</style>



