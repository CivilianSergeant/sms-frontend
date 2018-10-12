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
                    <h4 class="widgettitle"> VIEW Content Provider
                        <a href="<?php echo site_url('content-provider'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        <a ng-if="permissions.edit_permission=='1'" href="<?php echo site_url('content-provider/edit'); ?>/{{formData.id}}" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-pencil"></i> Edit </a>
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

                            <form method="POST" name="epgAdd" class="form-horizontal" ng-submit="saveEPG()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">


                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price"> Company Name </label>
                                            <div class="col-sm-4">
                                                <span style="position:relative;top:5px;">{{formData.company_name}}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Address </label>
                                            <div class="col-sm-4">
                                                <span style="position:relative;top:5px;">{{formData.address}}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price"> Phone </label>
                                            <div class="col-sm-4">
                                                <span style="position:relative;top:5px;">{{formData.phone}} </span>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Mobile </label>
                                            <div class="col-sm-4">
                                                <span style="position:relative;top:5px;">{{formData.mobile}}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Lat</label>
                                            <div class="col-sm-4">
                                                <span style="position:relative;top:5px;">{{formData.lat}}</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">lon </label>
                                            <div class="col-sm-4">
                                                <span style="position:relative;top:5px;">{{formData.lon}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <fieldset class="contact_person_1">
                                                <legend>Contact Person - 1</legend>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Name </label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_1}}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Email </label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_1_email}}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Phone </label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_1_phone}}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Designation </label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_1_designation}}</span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-12">
                                            <fieldset class="contact_person_2">
                                                <legend>Contact Person - 2</legend>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Name</label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_2}}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Email</label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_2_email}}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Phone </label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_2_phone}}</span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="price">Designation </label>
                                                <div class="col-sm-4">
                                                    <span style="position:relative;top:5px;">{{formData.contact_person_2_designation}}</span>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="price">Content Aggregator Type: </label>
                                        <div class="col-sm-4">
                                            <span style="position:relative;top:5px;" ng-repeat="type in content_aggregator">{{type.id==formData.content_aggregator_type_id ? type.content_aggregator_type : ''}}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="price">Rmarks :</label>
                                        <div class="col-sm-4">
                                            <span style="position:relative;top:5px;">{{formData.remarks}}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="price">Is Active </label>
                                        <div class="col-sm-4">
                                            <span style="position:relative;top:5px;">{{formData.is_active==1 ? 'Active' : 'Inactive'}}</span>
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
    .contact_person_1 legend {margin-left: 95px; font-size: 17px;}
    .contact_person_2 legend {margin-left: 95px; font-size: 17px;}
    .contact_person_2 {margin-top: 10px;} 
</style>


