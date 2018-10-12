<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var package = <?php echo json_encode($package->get_attributes()); ?>;
    var uri = '<?php echo $uri; ?>';
</script>
<?php $packageType = preg_match('/(catchup|vod)/',$uri); ?>
<style type="text/css">
    .push-bottom-20{margin-bottom:20px;}
</style>
<div id="container" ng-controller="EditIptvPackage" ng-cloak>

    <div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        <span>{{warning_messages}}</span>
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
                        <h4 class="widgettitle pull-left">Edit Package</h4>

                        <a href="<?php echo site_url($uri.'/view/' . $package->get_attribute('id')); ?>"
                           class="btn btn-success btn-sm pull-right" style="margin-left: 10px;"><i
                                class="fa fa-search"></i> View</a>
                        <a href="<?php echo site_url($uri); ?>" class="btn btn-danger btn-sm pull-right"><i
                                class="fa fa-arrow-circle-left"></i> Back</a>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <form name="package" id="package" method="POST" ng-submit="saveIptvPackage()">
                    <div class="col-md-<?php echo ($packageType)? '10':'5';  ?>">
                        <div class="col-md-11">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Package Name <span style="color:red">*</span></label>

                                <input type="text" readonly class="form-control" id="exampleInputEmail1" ng-model="formData.package_name"
                                       placeholder="Enter Package Name" required="required"/>
                            </div>
                        </div>
                        <div class="col-md-<?php echo ($packageType)? '2':'5';  ?>">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Package Price <span
                                        style="color:red">*</span></label>

                                <div class="input-group margin-bottom-sm">

                                    <input type="number" maxlength="5" class="form-control" string-to-number ng-model="formData.price"
                                           placeholder="Enter Prices" required="required"/>

                                </div>
                            </div>
                        </div>
                        <div class="package_duration">
                            <div id="packageduration" class="col-md-<?php echo ($packageType)? '2':'4';  ?>">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Package Duration <span
                                            style="color:red">*</span></label>

                                    <div class="input-group margin-bottom-sm">

                                        <input type="number" class="form-control" min="1" string-to-number ng-model="formData.duration"
                                               placeholder="Enter Duration" required="required"/>
                                    </div>
                                </div>

                            </div>
                            <label style="padding:0px;margin-top:27px; margin-left:10px" class="col-md-1" for="exampleInputEmail1"> Day(s)</label>

                        </div>

                        <div style="clear:both;" class="col-md-11">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="formData.is_commercial" ng-checked="formData.is_commercial=='1'"
                                               ng-true-value="'1'" ng-false-value="'0'"/>
                                        <strong>Is Commercial</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both;" class="col-md-11">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="formData.is_active" ng-checked="formData.is_active" ng-true-value="'1'" ng-false-value="'0'"/>
                                        <strong>Is Active</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both;" class="col-md-11">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="formData.not_deleteable" ng-checked="formData.not_deleteable" ng-true-value="'1'" ng-false-value="'0'"/>
                                        <strong>Is Base Package</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php

                    if(!$packageType){
                    ?>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <label class="control-label">Program List</label>
                                <select id="select-from" ng-model="formData.selected_item" style="width:200px;min-height:100px;"
                                        multiple="multiple" size="15">
                                    <option ng-repeat="p in programs" style="font-size:13px" value="{{p.id}}">
                                        {{p.program_name}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1" style="margin-top:25px;margin-right:13px;margin-left:53px;">
                                <button type="button" ng-click="IncludeItems()" class="btn btn-primary"><i
                                        class="fa fa-arrow-right"></i></button>
                                <button type="button" ng-click="ExcludeItems()" class="btn btn-primary"
                                        style="margin-top:20px;"><i class="fa fa-arrow-left"></i></button>
                            </div>
                            <div class="col-md-5">
                                <label class="control-label">Assigned Program List</label>
                                <select id="select-from" ng-model="formData.included_item" style="width:200px;min-height:100px;"
                                        multiple="multiple" size="15">
                                    <option ng-repeat="p in assigned_programs" style="font-size:13px" value="{{p.id}}">
                                        {{p.program_name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                    <div  class="col-md-12 push-bottom-20">
                        <div  class="form-group">
                            <!--<label for="exampleInputEmail1">Package Logo <small>(STB)</small></label>
                            <div class="col-md-12" style="padding-left: 0px;">
                                <input type="text" class="form-control" ng-model="formData.package_stb_logo"/>
                            </div>-->
                            <label class="control-label col-md-3">Package Logo <small>(STB)</small></label>

                            <div class="col-md-8">
                                <div ng-hide="messageState">
                                    <div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
                                                    Browse
                                                    <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderLogoSTB" nv-file-select="">
                                                </a>
											  </span>
                                    </div>

                                </div>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span>
                            </div>
                            <div class="col-md-3" ng-if="formData.package_stb_logo">
                                <img style="margin:0px 70px;" ng-src="<?php echo base_url(); ?>{{formData.package_stb_logo}}"/>
                            </div>
                        </div>
                    </div>
                    <div  class="col-md-12 push-bottom-20">
                        <div  class="form-group">
                            <!--<label for="exampleInputEmail1">Package Logo <small>(Mobile)</small> </label>
                            <div class="col-md-12" style="padding-left: 0px;">
                                <input type="text" class="form-control" ng-model="formData.package_mobile_logo"/>
                            </div>-->
                            <label class="control-label col-md-3">Package Logo <small>(Mobile)</small></label>

                            <div class="col-md-8">
                                <div ng-hide="messageState">
                                    <div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
                                                    Browse
                                                    <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderLogoMobile" nv-file-select="">
                                                </a>
											  </span>
                                    </div>

                                </div>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span>
                            </div>
                            <div class="col-md-3" ng-if="formData.package_mobile_logo">
                                <img style="margin:0px 70px;" ng-src="<?php echo base_url(); ?>{{formData.package_mobile_logo}}"/>
                            </div>
                        </div>
                    </div>
                    <div  class="col-md-12 push-bottom-20">
                        <div  class="form-group">
                            <!--<label for="exampleInputEmail1">Package Poster <small>(STB)</small> </label>
                            <div class="col-md-12" style="padding-left: 0px;">
                                <input type="text" class="form-control" ng-model="formData.package_poster_stb"/>
                            </div>-->
                            <label class="control-label col-md-3">Poster URL <small>(STB)</small></label>

                            <div class="col-md-8">
                                <div ng-hide="messageState">
                                    <div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
                                                    Browse
                                                    <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderPosterSTB" nv-file-select="">
                                                </a>
											  </span>
                                    </div>

                                </div>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span>
                            </div>
                            <div class="col-md-3" ng-if="formData.package_poster_stb">
                                <img style="margin:0px 70px;" ng-src="<?php echo base_url(); ?>{{formData.package_poster_stb}}"/>
                            </div>
                        </div>
                    </div>
                    <div  class="col-md-12 push-bottom-20">
                        <div  class="form-group">
                            <!--<label for="exampleInputEmail1">Package Poster <small>(Mobile)</small> </label>
                            <div class="col-md-12" style="padding-left: 0px;">
                                <input type="text" class="form-control" ng-model="formData.package_poster_mobile"/>
                            </div>-->
                            <label class="control-label col-md-3">Poster URL <small>(Mobile)</small></label>

                            <div class="col-md-8">
                                <div ng-hide="messageState">
                                    <div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
                                                    Browse
                                                    <input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderPosterMobile" nv-file-select="">
                                                </a>
											  </span>
                                    </div>

                                </div>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span>
                            </div>
                            <div class="col-md-3" ng-if="formData.package_poster_mobile">
                                <img style="margin:0px 70px;" ng-src="<?php echo base_url(); ?>{{formData.package_poster_mobile}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-10">
                            <div class="form-group">
                                <button type="submit" id="buttonsuccess" class="btn btn-success"
                                        ng-disabled="package.$invalid">Update
                                </button>
                                <a href="<?php echo site_url('iptv-packages'); ?>" class="btn btn-default"> Back </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('#btn-add').click(function () {
            $('#select-from option:selected').each(function () {
                $('#select-to').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
                $(this).remove();
                $("#select-to").children().attr('selected', 'selected');
            });
        });
        $('#btn-remove').click(function () {
            $('#select-to option:selected').each(function () {
                $('#select-from').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
                $(this).remove();
                $("#select-to").children().attr('selected', 'selected');
            });
        });

    });
</script>