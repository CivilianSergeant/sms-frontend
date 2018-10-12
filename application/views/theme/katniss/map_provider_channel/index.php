<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container" ng-controller="mapProviderChannel" ng-cloak>

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

        <div class="panel panel-default">
            <div class="row">
                <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> Map Provider Channel
                        
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
                </div>
                <div class="col-md-12">
                    <div class="panel-body">
                        <div class="col-md-12" style="margin-bottom: 15px;">
                            <label class="col-md-3" style="padding: 0px">Provider List</label>
                            <div class="col-md-3 col-md-pull-1" style="padding: 0px">
                                <select ng-model="provider_id" ng-change="loadProviderChannels()" class="form-control">
                                    <option value="">---Select Provider---</option>
                                    <?php foreach ($epg_providers as $val){ ?>
                                        <option value="<?php echo $val->id; ?>"><?php echo $val->provider_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 form-group" style="padding-left:0px;">
                            <label class="col-md-3 control-label">Provider Channel List</label>
                            <div class="col-md-3 col-md-pull-1">
                                <select ng-model="providerChannel" class="form-control" >
                                    <option ng-repeat="pc in providerChannels" value="{{pc.provider_channel_id}}">{{pc.provider_channel_name}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12 form-group" >
                            <label class="col-md-3 control-label" style="padding-left:0px;">Channel List</label>
                            <div class="col-md-3 col-md-pull-1" style="padding-left: 0px;">

                                <select ng-model="channel" class="form-control" >
                                    <?php if($programs){ ?>
                                    <?php foreach($programs as $program){ ?>
                                        <option value="<?php echo $program->id; ?>"><?php echo $program->program_name; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-12" style="padding-top: 10px">
                            <button class="btn btn-info" ng-click="addRow()">Add</button>
                        </div>
                        

                        <div class="col-md-12" style="padding-top: 10px">
                            <form id="saveMappingFrm" ng-submit="saveMapping()">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Streaming Channel</th>
                                        <th>Provider Channel</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="map in mappings">
                                        <td>{{($index+1)}}</td>
                                        <td>{{map.channel}}</td>
                                        <td>{{map.providerChannel}}</td>
                                        <td><a ng-click="removeFromRow($index)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                                <button ng-disabled="mappings.length<=0" type="submit" class="btn btn-success">Save Mapping</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



