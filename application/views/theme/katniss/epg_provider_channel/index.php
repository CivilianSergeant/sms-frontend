<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
//    tinymce.init({
//        selector: 'textarea',
//        fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt',
//        toolbar: 'bold italic font_size forecolor fontsizeselect',
//        plugins: "textcolor colorpicker",
//        menubar: false
//    });
</script>
<div id="container" ng-controller="epgProviderChannel" ng-cloak>

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
                    <h4 class="widgettitle"> New EPG Provider Channel
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
<!--                                <li><a data-toggle="tab" class="tab_top" href="#menu2">Import EPG Provider Channel</a></li>-->
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

                            <form method="POST" name="epgAddProviderChannel" class="form-horizontal" ng-submit="saveEPGProviderChannel()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="provider_channel_id">Channel ID <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="provider_channel_id" ng-model="formData.provider_channel_id" placeholder="Channel ID" required="required" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="provider_id">Provider Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <select ng-model="formData.provider_id" class="form-control" required="required">
                                                    <option value="">---Select Provider---</option>
                                                    <?php foreach ($epg_providers as $val){ ?>
                                                        <option value="<?php echo $val->id; ?>"><?php echo $val->provider_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="provider_channel_name">Provider Channel Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" name="provider_channel_name" class="form-control" ng-model="formData.provider_channel_name" placeholder="Provider Channel Name" required="required" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="lang"> Lang <span class="text-danger">*</span></label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="lang" ng-model="formData.lang" required="required">
                                                    <option value="">---Select Lan---</option>
                                                    <option value="en">English</option>
                                                    <option value="bn">Bangla</option>
                                                </select>
<!--                                                <input type="text" class="form-control" name="lang" ng-model="formData.lang" placeholder="Lang" required="required" />-->
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" ng-disabled="!epgAddProviderChannel.$valid" class="btn btn-success btnNext"> Save EPG Provider </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div id="menu2" class="tab-pane fade">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-12" style="padding-bottom: 20px; margin-bottom:20px;margin-top:20px;">
                                    <h4 class="widgettitle">Import EPG Provider Channel<a class="btn btn-info btn-sm pull-right">Download Template</a></h4>

                                </div>
                            </div>
                            <form action="<?php echo site_url(); ?>" method="post" class="form-horizontal">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="external_card_number">Import CSV <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="file" class="form-control" id="epg_import"  required="required">
                                            </div>
                                        </div>


                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" class="btn btn-success">Import</button>
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
                            EPG Provider Channel LIST
                            <a ng-if="permissions.create_permission == '1'" ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus-circle"></i> Add New EPG </a>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12" ng-if="!delete_flag">
                    <kendo-grid options="mainGridOptions" id="epg-grid">
                    </kendo-grid>
                </div>
                <div class="col-md-12 text-center" ng-if="delete_flag">
                    <form>
                        <p><strong>Are you sure to delete this EPG Provider Channel</strong></p>
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
