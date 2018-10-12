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
var id = "<?php echo $id; ?>";
</script>
<div id="container" ng-controller="editEPGProvider" ng-cloak>

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
                    <h4 class="widgettitle"> New EPG Provider

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
                                </div>
                            </div>

                            <form method="POST" name="epgEditProvider" class="form-horizontal" ng-submit="editEPGProvider()" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="col-md-10">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="provider_name">Provider Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="hidden" ng-model="formData.id" />
                                                <input type="text" class="form-control" ng-model="formData.provider_name" required="required" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="address">Address </label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" ng-model="formData.address"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="email"> Email </label>
                                            <div class="col-md-3">
                                                <input type="email" class="form-control" ng-model="formData.email" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="phone"> Phone</label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" ng-model="formData.phone" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="contact_person_name">Contact Person Name </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" ng-model="formData.contact_person_name" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="contact_person_phone">Contact Person Phone </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" ng-model="formData.contact_person_phone" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="web_url">Web Site </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" ng-model="formData.web_url" />
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" ng-disabled="!epgEditProvider.$valid" class="btn btn-success btnNext"> Save EPG Provider </button>
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
