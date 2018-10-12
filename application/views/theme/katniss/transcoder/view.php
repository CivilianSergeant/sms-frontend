<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    fieldset{padding: 10px}
    legend{margin-top: 15px; font-size: 16px}
</style>
<script>
    var transcoderId = "<?php echo $id; ?>";
</script>

<div id="container" ng-controller="EditTranscoder" ng-cloak>

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
                    <h4 class="widgettitle"> View Transcoder
                        <a href="<?php echo site_url('transcoder'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        <a href="<?php echo site_url('transcoder/edit'); ?>/{{formData.id}}" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-edit"></i>  Edit </a>
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

                            <form method="POST" name="updateTranscoder" class="form-horizontal" ng-submit="transcoderUpdate()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="transcoder_name">Name</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="transcoder_name" ng-model="formData.transcoder_name" placeholder="Enter Transcoder Name" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="number">Number</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="number" ng-model="formData.number" placeholder="Enter Transcoder Number" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="vendor_id">Vendor </label>
                                            <div class="col-sm-4">
                                                <select kendo-combo-box
                                                        k-placeholder="'Select Vendor'"
                                                        k-data-text-field="'name'"
                                                        k-data-value-field="'id'"

                                                        k-data-source="vendors"
                                                        style="width: 100%" ng-model="formData.vendor_id" disabled="disabled">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="data_ip">Data IP </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="data_ip" ng-model="formData.data_ip" placeholder="Enter Data IP" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="out_ip">Out IP </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="out_ip" ng-model="formData.out_ip" placeholder="Enter Out IP" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="impl_ip">IMPL IP </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="impl_ip" ng-model="formData.impl_ip" placeholder="Enter IMPL IP" disabled="disabled"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="transcoder_user_name">Transcoder User Name </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="transcoder_user_name" ng-model="formData.transcoder_user_name" placeholder="Enter Transcoder User Name" disabled="disabled"/>
                                            </div>
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




