<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var token = "<?php echo $token; ?>";
    var id  = "<?php echo $id; ?>";
</script>
<div id="container" ng-controller="SubscriberImport" ng-cloak>
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
                    <div class="col-md-12">
                        <h4 class="widgettitle pull-left">Import Subscriber</h4>
                        <a href="<?php echo base_url('public/downloads/subscriber-template.xlsx'); ?>" class="btn btn-info btn-sm pull-right" style="margin-left:10px;"><i class="fa fa-plus-circle"></i> Download Template</a>
                        <a ng-click="refresh()" class="btn btn-warning btn-sm pull-right" >
                            <span class="fa fa-refresh"></span> Refresh Queue
                        </a>
                        <a href="<?php echo site_url('lco'); ?>" class="btn btn-danger btn-sm pull-right"  style="margin-right:10px;">
                            <span class="fa fa-arrow-left"></span> Back
                        </a>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <!-- <div id ="grid" kendo-grid k-options="mainGridOption" k-rebind="mainGridOption"/> -->
                <style type="text/css">
                    .form-control{height: 34px !important;}
                    .progress {height: 40px !important;}
                    .progress-bar {font-size: 18px; line-height: 38px; color: springgreen;}
                    .input-file { position: relative;} /* Remove margin, it is just for stackoverflow viewing */
                    .input-file .input-group-addon { border: 0px; padding: 0px; }
                    .input-file .input-group-addon .btn { border-radius: 0 4px 4px 0 }
                    .input-file .input-group-addon input {height:34px; cursor: pointer; position:absolute; width: 72px; z-index:2;top:0;right:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0; background-color:transparent; color:transparent; }
                </style>
                <div filters="queueLimit, customFilter" uploader="uploader" nv-file-drop="" class="col-md-6 col-md-offset-3">
                    <div style="margin-bottom: 40px">
                        <div ng-hide="messageState">
                            <div class="input-group input-file">
                                <div id="fileValue" class="form-control">
                                    <!-- <a href="/path/to/your/current_file_name.pdf" target="_blank">current_file_name.pdf</a> -->
                                </div>
									  <span class="input-group-addon">
									    <a href="javascript:;" class="btn btn-primary">
                                            Browse
                                            <input type="file" id="file" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="uploader" nv-file-select="">
                                        </a>
									  </span>
                            </div>
                            <div>
                                <div style="margin-top:5px" class="progress">
                                    <div ng-style="{ 'width': fileUploadProgress + '%' }" role="progressbar" class="progress-bar" style="width: 0%;"><div ng-show="fileUploadProgress" class="ng-binding ng-hide">{{fileUploadProgress}} %</div></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control" ng-model="business_region_l1" ng-change="setRegionLevel2()" ng-disabled="profile.region_l1_code > 0">
                                    <option value="">--SELECT-REGION-Level1--</option>
                                    <option ng-repeat="r1 in regions" ng-selected="r1.id == profile.region_l1_code" value="{{r1.id}}">{{r1.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" ng-model="business_region_l2" ng-change="setRegionLevel3()" ng-disabled="profile.region_l2_code > 0">
                                    <option value="">--SELECT-REGION-Level2--</option>
                                    <option ng-repeat="r2 in regions_level_2" value="{{r2.id}}">{{r2.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" ng-model="business_region_l3" ng-change="setRegionLevel4()" ng-disabled="profile.region_l3_code > 0">
                                    <option value="">--SELECT-REGION-Level3--</option>
                                    <option ng-repeat="r3 in regions_level_3" value="{{r3.id}}">{{r3.name}}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" ng-model="business_region_l4" ng-disabled="profile.region_l4_code > 0">
                                    <option value="">--SELECT-REGION-Level4--</option>
                                    <option ng-repeat="r4 in regions_level_4" value="{{r4.id}}">{{r4.name}}</option>
                                </select>
                            </div>
                            <div class="row text-center" ng-show="loader">
                                <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                            </div>
                            <button ng-disabled="!uploader.getNotUploadedItems()" ng-click="uploadFile()" class="btn btn-success btn-s" type="button" disabled="disabled">
                                <span class="glyphicon glyphicon-upload"></span> Upload
                            </button>
                            <button ng-disabled="!uploader.isUploading" ng-click="uploader.cancelAll()" class="btn btn-warning btn-s" type="button" disabled="disabled">
                                <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>