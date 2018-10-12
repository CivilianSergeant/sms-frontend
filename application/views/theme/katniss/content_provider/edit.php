<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">

    var epgId = "<?php echo $id; ?>";

</script>
<div id="container" ng-controller="EditContentProvider" ng-cloak>

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

    <div class="panel panel-default" >

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> EDIT Content Provider
                        <a href="<?php echo site_url('content-provider'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        <a href="<?php echo site_url('content-provider/view'); ?>/{{formData.id}}" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-search"></i>  View </a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="row">

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-3" style="padding-bottom: 20px">
                                    <!--<h4 class="widgettitle">Add New EPG</h4>-->
                                </div>
                            </div>

                            <form method="POST" name="cntent_providerAdd" class="form-horizontal" ng-submit="saveContent_provider()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="company_name">Company Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="company_name" ng-model="formData.company_name" placeholder="Enter Company Name" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address">Address <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="address" ng-model="formData.address" placeholder="Enter Address" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="phone">Phone <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="phone" ng-model="formData.phone" placeholder="Enter Phone" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="mobile">Mobile <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="mobile" ng-model="formData.mobile" placeholder="Enter Mobile" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="lat">Lat </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="lat" ng-model="formData.lat" placeholder="Enter Lat" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="lon">Lon </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="lon" ng-model="formData.lon" placeholder="Enter Lon" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <fieldset class="contact_person_1">
                                                <legend>Contact Person - 1</legend>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1">Name <span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1" ng-model="formData.contact_person_1" placeholder="Enter Nmae" required="required"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_phone">Phone<span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_phone" ng-model="formData.contact_person_1_phone" placeholder="Enter Phone" required="required"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_email"> Email<span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_email" ng-model="formData.contact_person_1_email" placeholder="Enter Email" required="required"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="contact_person_1_designation">Designation<span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" id="contact_person_1_designation" ng-model="formData.contact_person_1_designation" placeholder="Enter Designation" required="required"/>
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
                                                            <input type="text" class="form-control" id="contact_person_2" ng-model="formData.contact_person_2" placeholder="Enter Name" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label" for="contact_person_2_phone"> Phone</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="contact_person_2_phone" ng-model="formData.contact_person_2_phone" placeholder="Enter Phone" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label" for="contact_person_2_email"> Email</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="contact_person_2_email" ng-model="formData.contact_person_2_email" placeholder="Enter Email" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label" for="contact_person_2_designation"> Designation</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" id="contact_person_2_designation" ng-model="formData.contact_person_2_designation" placeholder="Enter Designation" />
                                                        </div>
                                                    </div>
                                            </fieldset>
                                        </div>

                                        <div class="form-group">
                                          <label class="col-sm-4 control-label" for="content_aggregator_type">Content Aggregator Type:</label>
                                          <div class="col-sm-4">
                                              <select class="form-control" id="content_aggregator_type" ng-model="formData.content_aggregator_type_id">
                                                <option value="">Please Select</option>
                                                <option ng-repeat="type in content_aggregator"  value="{{type.id}}" >{{type.content_aggregator_type}}</option>

                                              </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="remarks">Remarks</label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" ng-model="formData.remarks" placeholder="Enter Remarks"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Is Acive</label>
                                            <div class="col-sm-7">
                                                <div class="checkbox">
                                                    <label id="is_active"><input type="checkbox" ng-model="formData.is_active" ng-checked="formData.is_active=='1'" ng-true-value="'1'" ng-false-value="'0'"/><label>
                                                </div>
                                            </div>
                                        </div>


        
                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" ng-disabled="!cntent_providerAdd.$valid" class="btn btn-success btnNext"> Save Content Provider </button>
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
<style type="text/css">
    .contact_person_1 legend {margin-left: 95px;     font-size: 17px;}
    .contact_person_2 legend {margin-left: 95px;     font-size: 17px;}
    .contact_person_2 {margin-top: 10px;margin-bottom: 15px} 
    #is_active {margin-left: 5px;}
</style>




