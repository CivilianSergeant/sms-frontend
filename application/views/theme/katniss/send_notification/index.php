<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
var sign = "<?php echo $sign; ?>";
</script>
<div id="container" ng-controller="notificationCTL" ng-cloak>

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


                    <h4 class="widgettitle"> Send Notification

                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>
                    

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal" ng-submit="sendNotification()">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="col-md-7">
                        
                            <div class="form-group">
                                
                                <label class="control-label col-md-3 ">
                                    Device Group
                                </label>
                                <div class="col-md-6">
                                    <select
                                            
                                    ng-model="formData.device_group_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Device Group'"
                                    k-data-text-field="'group_name'"
                                    k-data-value-field="'id'"
                                    k-filter="'contains'"
                                    k-auto-bind="false"
                                    k-change = "'loadDevice()'"
                                    k-min-length="5"
                                    k-data-source="device_groups"
                                    
                                    style="width: 100%"></select>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                
                                <label class="control-label col-md-3">
                                    Subscriber
                                </label>
                                <div class="col-md-6">
                                    <select
                                         
                                    ng-model="formData.device_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Subscriber'"
                                    k-data-text-field="'subscriber_name'"
                                    k-data-value-field="'fcm_token'"
                                    k-filter="'contains'"
                                    k-auto-bind="false"
                                    k-min-length="5"
                                    k-data-source="fcm_tokens"
                                    
                                    style="width: 100%" ></select>
                                    
                                </div>
                            </div>
                            

                            

                            
                        
                    </div>
                    <!--<div class="col-md-5">
                        <div class="form-group">
                            <label class="col-md-3">From Date</label>
                            <div class="col-md-5">
                                <input kendo-date-picker class="form-control" 

                                k-ng-model="from_day"
                                k-value="from_day"
                                k-min="minDate"
                                k-format="'yyyy-MM-dd HH:mm:ss'"
                                style="width: 100%;" required/>

                            </div>
                            <!--<div class="col-md-4">
                            <input kendo-time-picker
                                   ng-model="start_time_min"
                                   k-min="start_time_min"
                                   k-max="start_time_max"
                                   k-ng-model="startTimeObj"
                                   style="width: 100%;" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">To Date</label>
                            <div class="col-md-5">
                                <input kendo-date-picker class="form-control"
                                k-ng-model="end_day"
                                k-value="end_day"
                                k-min="end_day"
                                k-format="'yyyy-MM-dd 23:59:59'"
                                style="width: 100%;" required/>

                            </div>
                            <!--<div class="col-md-4">
                                <input kendo-time-picker
                                       ng-model="end_time_min"
                                       k-min="end_time_min"

                                       k-ng-model="endTimeObj"
                                       style="width: 100%;" />
                            </div>--
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-12">
                    <hr/>
                </div>
            </div>
            <div class="col-md-12" style="margin-left:-6px;">
                
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-2">Notification Type</label>
                            <div class="col-md-3">
                                <select class="form-control" ng-model="formData.type">
                                    <option value="">--SELECT TYPE--</option>
                                    <option ng-repeat="nType in notificationTypes" value="{{nType}}">{{nType}}</option>
                                </select>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-2">Notification Header</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" maxlength="15" ng-model="formData.title"/>
                                <small>(Maximum 15 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-2">Notification Text</label>
                            <div class="col-md-8">
                                <textarea rows="8" cols="7" class="form-control" maxlength="400" ng-model="formData.content"></textarea>
                                <small>(Maximum 400 characters)</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-2">Image URL</label>
                            <div class="col-md-4">
                                <input id="file" onchange="imageView(this,720,420);" type="file" class="form-control"  name="file"/>
                                
                            </div>
                            <div class="col-md-5">
                                <img id="img" width="50" src="" alt="img" class="hidden"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label col-md-2">Resource URL</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control"  ng-model="formData.resource_url" />
                                
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-6 col-md-offset-2">
                                <input type="submit" class="btn btn-primary" value="Send Mail"/>
                            </div>
                        </div>
                    </div>

                
            </div>
            </form>
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
               /* if(img.width<=w && img.height<=h){*/
                    $('#img').attr('src', img.src);
                    $('#img').attr('data-type',input.files[0].type)
                    $('#img').removeClass('hidden');

                /*}else{
                    alert("width and height must be "+w+"x"+h);
                    input.files[0].name='';
                    $(input).next().attr('src','');
                    $(input).next().addClass('hidden');
                    $(input).val('');

                }*/
            };

        }
        
    }
</script>