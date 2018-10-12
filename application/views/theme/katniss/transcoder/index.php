<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    fieldset{padding: 10px}
    legend{margin-top: 15px; font-size: 16px}
</style>

<div id="container" ng-controller="CreateTranscoder" ng-cloak>

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
                    <h4 class="widgettitle"> New Transcoder
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

                            <form method="POST" name="transcoderAdd" class="form-horizontal" ng-submit="saveTranscoder()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="transcoder_name">Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="transcoder_name" ng-model="formData.transcoder_name" placeholder="Enter Transcoder Name" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="number">Number<span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="number" ng-model="formData.number" placeholder="Enter Transcoder Number" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="vendor_id">Vendor <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <select kendo-combo-box
                                                        k-placeholder="'Select Vendor'"
                                                        k-data-text-field="'name'"
                                                        k-data-value-field="'id'"

                                                        k-data-source="vendors"
                                                        style="width: 100%" ng-model="formData.vendor_id" required="required">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="data_ip">Data IP <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="data_ip" ng-model="formData.data_ip" placeholder="Enter Data IP" required="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="out_ip">Out IP <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="out_ip" ng-model="formData.out_ip" placeholder="Enter Out IP" required="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="impl_ip">IMPL IP <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="impl_ip" ng-model="formData.impl_ip" placeholder="Enter IMPL IP" required="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="transcoder_user_name">Transcoder User Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="transcoder_user_name" ng-model="formData.transcoder_user_name" placeholder="Enter Transcoder User Name" required="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="transcoder_password">Transcoder Password <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="password" class="form-control" id="transcoder_password" ng-model="formData.transcoder_password" placeholder="Enter Transcoder Password" required="required"/>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="col-md-3" style="padding-bottom: 20px">

                                            </div>
                                        </div>
                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" ng-disabled="!transcoderAdd.$valid" class="btn btn-success btnNext"> Save Transcoder </button>
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
                            Transcoder LIST
                            <a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New Transcoder </a>
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
                        <p><strong>Are you sure to delete this transcoder</strong></p>
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




