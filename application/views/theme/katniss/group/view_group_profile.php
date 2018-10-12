<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo PROJECT_PATH; ?>public/theme/katniss/css/lightbox.min.css" type="text/css" />

<script type="text/javascript">
    var token = "<?php echo $token; ?>";
</script>
<div id="container" ng-controller="ViewGroupProfile" ng-cloak>

    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> View Group <?php echo ($user_info->user_type == 'MSO')? 'Admin' : 'User'; ?> [ {{profile.group_name}} ]

                        <a href="<?php echo site_url('groups/edit/{{profile.token}}' ) ?>" class="btn btn-success btn-sm pull-right" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>

                        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                    </h4>
                    <hr/>
                    <span class="clearfix"></span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <style type="text/css">
                        .profile{background: rgba(202, 255, 202, 0.15) none repeat scroll 0 0;border-radius: 5px;box-shadow: 0 0 2px;margin-bottom: 20px; }
                        hr {border-color: teal;}
                        .profile h4{color:#008000;}
                    </style>
                    <div class="tab-content" ng-if="!loader">
                        <div class="profile panel-body tab-pane active" id="profile" class="tab-pane active">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Profile Information</h4>
                                    <hr/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputEmail1">Full Name <span class="text-danger"></span></label>
                                        : {{profile.group_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="email">Email</label>                       
                                        : {{profile.email}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="address1"> Address Line 1</label>                        
                                        : {{profile.address1}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="address2"> Address Line 2</label>                        
                                        : {{profile.address2}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="address2"> Contact</label>                       
                                        : {{profile.contact}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="address2"> Billing Contact</label>                       
                                        : {{profile.billing_contact}}<br/>
                                    </div>
                                </div>
                                <div class="col-md-4">                          
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputPassword1">Country</label>                       
                                         : {{profile.country_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputPassword1">Divisions</label>                     
                                         : {{profile.division_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputPassword1">District</label>                      
                                        : {{profile.district_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputPassword1">Area</label>                      
                                        : {{profile.area_name}}<br/>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputPassword1">Sub Area</label>                     
                                         : {{profile.sub_area_name}}<br/>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-6 control-label" for="exampleInputPassword1">Road</label>                     
                                         : {{profile.road_name}}
                                    </div>
                                </div>
                                <style type="text/css">.lb-caption a{color: white;}</style>
                                <div class="col-md-2">
                                    <label id="view" class="col-md-4 control-label">Photo</label>
                                        <!--<a href="<?php /*echo base_url('/'); */?>{{profile.photo}}" data-lightbox="example-2"  data-title="<a href='<?php /*echo site_url('lco/image_download')*/?>/{{profile.token}}/?id=lco_profile'>Download</a>">-->
                                         <img id="pic" ng-show="profile.photo" ng-src="<?php echo base_url('/'); ?>{{profile.photo}}"/></a>
                                 
                                        <img class="thumbnail" width="100" height="100" ng-if="!profile.photo" src="<?php echo base_url();?>public/profile.jpg">
                                </div>
                            </div> 
                            
                        </div>
                        <div class="profile panel-body tab-pane active" id="login">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Login Information: </h4>
                                    <hr/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ng-binding">
                                        <label id="view" class="col-md-3 control-label" for="username">Username</label>                      
                                          : {{profile.username}}
                                    </div>
                                </div>    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="view" class="col-md-4 control-label" for="username">Is Active:</label>
                                        <span ng-if="profile.user_status" class='label label-success'>Active</span>
                                        <span ng-show="!profile.user_status" class="label label-danger">Inactive</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label id="view" class="col-sm-8 control-label" for="username">Is Remote Access Enabled:</label>
                                        <span ng-if="profile.is_remote_access_enabled==0" class='label label-success'>Active</span>
                                        <span ng-if="profile.is_remote_access_enabled==1" class="label label-danger">Inactive</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile panel-body tab-pane active" id="identity_verify">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>Attach Identity Verification Document</h4>
                                    <hr/>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-md-4">
                                    <div class="table-responsive">          
                                        <table class="table">     
                                            <tbody>
                                                <tr>
                                                    <th >Type of Document</th>
                                                    <td>: {{identity.type}}</td>
                                                </tr>
                                                <tr>
                                                    <th >Identity Number</th>
                                                    <td>: {{identity.id}}</td>
                                                </tr>
                                                <tr>
                                                    <th >Identity Document</th>
                                                    <td>
                                                         <!--<a href="<?php /*echo base_url('/'); */?>{{identity.identity_attachment}}" data-lightbox="example-2"  data-title="<a href='<?php /*echo site_url('mso/image_download')*/?>/{{profile.token}}/?id=mso_identity'>Download</a>">-->
                                                        <img id="pic" width="100" height="100" ng-show="identity.identity_attachment"  ng-src="<?php echo base_url('/'); ?>{{identity.identity_attachment}}"/></a>
                                                         <img width="100" height="100" class="example-image" ng-if="!identity.identity_attachment"  src="<?php echo base_url();?>public/sub.jpg"/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile panel-body" id="list">
                            <div class="widgettitle">
                                <div class="col-md-12">
                                    <h4>List of LCO</h4>
                                    <hr/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <kendo-grid options="mainGridOptions">
                                    </kendo-grid>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center" ng-show="loader">
                        <h3>Loading</h3>
                        <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>








