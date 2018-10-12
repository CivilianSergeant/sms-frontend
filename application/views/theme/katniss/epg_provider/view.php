<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="CreateEPGProvider" ng-cloak>


    <div class="panel panel-default">

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle">View EPG Provider 
                        <a href="<?php echo site_url('manage-epg-provider'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        <a ng-if="permissions.edit_permission=='1'" href="<?php echo site_url('manage-epg-provider/edit'); ?>/<?php echo $provider->id; ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-pencil"></i> Edit </a>
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

                            <div class="col-md-12">
                                <div class="col-md-10">

                                    <div class="form-group col-md-12">
                                        <label class="col-sm-4 control-label" for="provider_name">Provider Name </label>
                                        <div class="col-sm-8">
                                            <?php echo $provider->provider_name; ?>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="col-sm-4 control-label" for="address">Address </label>
                                        <div class="col-sm-8">
                                            <?php echo $provider->address; ?>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="control-label col-md-4" for="email"> Email </label>
                                        <div class="col-md-3">
                                            <?php echo $provider->email; ?>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="control-label col-md-4" for="phone"> Phone</label>
                                        <div class="col-md-3">
                                            <?php echo $provider->phone; ?>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="col-sm-4 control-label" for="contact_person_name">Contact Person Name </label>
                                        <div class="col-sm-4">
                                            <?php echo $provider->contact_person_name; ?>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="col-sm-4 control-label" for="contact_person_phone">Contact Person Phone </label>
                                        <div class="col-sm-4">
                                            <?php echo $provider->contact_person_phone; ?>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label class="col-sm-4 control-label" for="web_url">Web Site </label>
                                        <div class="col-sm-4">
                                            <?php echo $provider->web_url; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
