<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">

    var epgId = "<?php echo $id; ?>";

    tinymce.init({
        selector:'textarea',
        fontsize_formats:'8pt 10pt 12pt 14pt 16pt 18pt',
        toolbar: 'bold italic font_size forecolor fontsizeselect',
        plugins: "textcolor colorpicker",

        menubar:false
    });
</script>
<div id="container" ng-controller="CreateEPG" ng-cloak>

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
                    <h4 class="widgettitle"> EDIT EPG
                        <a href="<?php echo site_url('manage-epg'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                        <a href="<?php echo site_url('manage-epg/view'); ?>/{{formData.id}}" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-search"></i>  View </a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="row">
               <!-- <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="panel-heading">
                            <ul class="tab_nav nav nav-tabs">
                                <li class="active"><a data-toggle="tab" class="tab_top" href="#home">Add New</a></li>
                                <!--<li><a data-toggle="tab" class="tab_top" href="#menu1">Export</a></li>
                                <li><a data-toggle="tab" class="tab_top" href="#menu2">Import</a></li>
                            </ul>
                        </div>
                    </div>
                </div>-->
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
                                            <label class="col-sm-4 control-label" for="external_card_number">Channel Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <select id="channelList" kendo-combo-box
                                                        k-placeholder="'Select Channel'"
                                                        k-data-text-field="'program_name'"
                                                        k-data-value-field="'id'"

                                                        k-data-source="channels"
                                                        style="width: 100%" ng-model="formData.program_id" required="required">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Program Name <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="program_name" ng-model="formData.program_name" placeholder="Enter Program Name" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-4"> EPG Type <span class="text-danger">*</span></label>
                                            <div class="col-md-3">
                                                <select class="form-control" ng-model="formData.epg_type" required="required">
                                                    <option value="">--SELECT TYPE--</option>
                                                    <option value="FIXED">FIXED DATE</option>
                                                    <option value="RECURRING">WEEK DAY</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Duration <span style="color:red">*</span></label>
                                            <div class="col-sm-1">
                                                <input type="text" ng-change="resetTime()" class="form-control" ng-model="formData.duration"  required="required"/>
                                            </div>
                                            <span style="position:relative;top:5px;">(in minutes)</span>
                                        </div>

                                        <div class="form-group" ng-if="formData.epg_type=='FIXED'">
                                            <label class="col-sm-4 control-label" for="price">Show Date <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" kendo-datepicker k-format="'yyyy-MM-dd'"  placeholder="Enter Show Date"  ng-model="formData.show_date"  required="required"/>
                                            </div>
                                        </div>
                                        <div class="form-group" ng-if="formData.epg_type=='RECURRING'">
                                            <label class="control-label col-md-4">Week days</label>
                                            <div class="col-md-8">
                                                <a id="sat" ng-click="setDay('sat')" ng-class="['btn', (weekDays.indexOf('sat')!= -1)?'btn-success':'btn-info', 'btn-xs']">SAT</a>
                                                <a id="sun" ng-click="setDay('sun')" ng-class="['btn', (weekDays.indexOf('sun')!= -1)?'btn-success':'btn-info' ,'btn-xs']">SUN</a>
                                                <a id="mon" ng-click="setDay('mon')" ng-class="['btn', (weekDays.indexOf('mon')!= -1)?'btn-success':'btn-info' ,'btn-xs']">MON</a>
                                                <a id="tue" ng-click="setDay('tue')" ng-class="['btn', (weekDays.indexOf('tue')!= -1)?'btn-success':'btn-info' ,'btn-xs']">TUE</a>
                                                <a id="wed" ng-click="setDay('wed')" ng-class="['btn', (weekDays.indexOf('wed')!= -1)?'btn-success':'btn-info' ,'btn-xs']">WED</a>
                                                <a id="thu" ng-click="setDay('thu')" ng-class="['btn', (weekDays.indexOf('thu')!= -1)?'btn-success':'btn-info' ,'btn-xs']">THU</a>
                                                <a id="fri" ng-click="setDay('fri')" ng-class="['btn', (weekDays.indexOf('fri')!= -1)?'btn-success':'btn-info' ,'btn-xs']">FRI</a>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Start Time <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" id="startTime" kendo-timepicker k-interval="1" k-format="'HH:mm:ss'" k-change="'setStartTime()'" k-ng-model="start_time" ng-model="formData.start_time"  placeholder="Enter Start Time" class="form-control" required="required"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">End Time </label>
                                            <div class="col-sm-4">
                                                <input type="text" id="endTime" kendo-timepicker readonly class="form-control" k-interval="1" k-format="'HH:mm:ss'"  k-ng-model="end_time" ng-model="formData.end_time" placeholder="Enter End Time" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price">Description</label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control" ng-model="formData.program_description" placeholder="Enter Program Description"></textarea>
                                            </div>
                                        </div>

                                        <div  class="form-group">

                                            <label class="control-label col-md-4"> Program Logo</label>
                                            <div class="col-md-8">
                                                <input type="file" name="program_logo" style="height:34px;" onchange="imageView(this,600,600);" >
                                                <img class="hidden" style="width:100px; height:100px;"  src=""/><br/>
                                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                                <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                            </div>

                                        </div>

                                        <div  class="form-group">

                                            <label class="control-label col-md-4"> Program Poster</label>
                                            <div class="col-md-8">
                                                <input type="file" name="program_poster" style="height:34px;" onchange="imageView(this,600,600);" >
                                                <img class="hidden" style="width:100px; height:100px;"  src=""/><br/>
                                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                                <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                            </div>

                                        </div>

                                        <div class="form-group" ng-if="formData.epg_type=='FIXED'">
                                            <label class="control-label col-md-4">Repeat Date Time:</label>
                                            <div class="col-md-8">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Repeat Date</th>
                                                            <th>Repeat Start Time</th>
                                                            <th>Repeat End Time</th>
                                                            <th><a class="btn btn-primary btn-xs" ng-click="addRow()"><i class="fa fa-plus"></i> ADD</a></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="r in formData.repeats">
                                                            <td>
                                                                <input type="text" class="form-control" kendo-datepicker k-format="'yyyy-MM-dd'"  placeholder="Enter Show Date"  ng-model="r.repeat_date"  />
                                                            </td>
                                                            <td>
                                                                <input type="text" kendo-timepicker k-format="'HH:mm:ss'" class="form-control repeatStartTime" ng-model="r.repeat_start_time" placeholder="Enter Start Time" >
                                                            </td>
                                                            <td>
                                                                <input type="text" kendo-timepicker k-format="'HH:mm:ss'" class="form-control repeatEndTime" ng-model="r.repeat_end_time" placeholder="Enter End Time" >
                                                            </td>
                                                            <td><a ng-click="deleteRepeatRow($index)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="form-group" ng-if="formData.epg_type=='RECURRING'">
                                            <label class="control-label col-md-4">Repeat Time:</label>
                                            <div class="col-md-8">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Repeat Start Time</th>
                                                        <th>Repeat End Time</th>
                                                        <th><a class="btn btn-primary btn-xs" ng-click="addRow()"><i class="fa fa-plus"></i> ADD</a></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr ng-repeat="r in formData.repeats">
                                                        <td>
                                                            <input type="text" kendo-timepicker k-format="'HH:mm:ss'" class="form-control repeatStartTime" ng-model="r.repeat_start_time" placeholder="Enter Start Time" >
                                                        </td>
                                                        <td>
                                                            <input type="text" kendo-timepicker k-format="'HH:mm:ss'" class="form-control repeatEndTime" ng-model="r.repeat_end_time" placeholder="Enter End Time" >
                                                        </td>
                                                        <td><a ng-click="deleteRepeatRow($index)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit"  class="btn btn-success btnNext"> Save EPG </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--<div id="menu1" class="tab-pane fade">
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="col-md-3" style="padding-bottom: 20px">
                                    <h4 class="widgettitle">Export Tempate</h4>
                                </div>
                            </div>
                            <form action="<?php /*echo site_url('set-top-box/export-stb'); */?>" method="post" class="form-horizontal">
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                         <div class="form-group">
                                            <label class="col-sm-4 control-label" for="external_card_number">External Card Number <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="number" maxlength="16" class="form-control" id="external_card_number" ng-model="stb.external_card_number" placeholder="Enter External Number" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label" for="price"> Price <span style="color:red">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" required="required">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-md-offset-4">
                                            <button type="submit" class="btn btn-success">Export</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>-->

                </div>
            </div>

        </div>
    </div>


</div>
<script type="text/javascript">
    var imageView = function(input,w,h){
        var img = null;
        var obj = $(input);
        if(input.files && input.files[0]){

            var fileReader = new FileReader();
            fileReader.readAsDataURL(input.files[0]);
            fileReader.onload= function(e){
                img = new Image();
                img.src= e.target.result;
                //$scope.formData.img[] = img.src;
                //if(img.width<=w && img.height<=h){
                    $(input).next().attr('src', img.src);
                    $(input).next().removeClass('hidden');

                /*}else{
                    alert("width and height of image out of range");
                    input.files[0].name='';
                    $(input).next().attr('src','');
                    $(input).next().addClass('hidden');
                    $(input).val('');

                }*/
            };
        }
    }
</script>



