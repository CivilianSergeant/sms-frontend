<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .permissions{margin:0px;padding:0px;}
    .permissions li{list-style:none;margin:0px;}
    .permissions li div{height:40px; float:left;width:25%; border:1px solid #efefef;padding:10px;}
    .permissions li div.action{width:12.5%}

    .submenu li div:first-child{padding-left:30px;}

</style>
<script type="text/javascript">
    var userRole = "<?php echo $user_role; ?>";
</script>
<div id="container" ng-controller="Permission" ng-cloak>

    <div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>

    <div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{success_messages}}
    </div>

    <div class="panel panel-default">
        <div class="row" >
            <!-- <div class="col-md-12">
                <div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
            </div> -->
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">Permissions
                            <!--<a ng-click="hideForm(); removeAlert()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>-->
                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <label class="control-label col-md-2" style="width:10%;">Select Role  </label>
                    <div class="col-md-2">
                        <select class="form-control" ng-model="role_id" ng-change="getMenuRoutes()">
                            <option value="0">--SELECT ROLE--</option>
                            <option ng-repeat="role in roles"  value="{{role.id}}" style="text-transform: uppercase">{{role.user_type}}-{{role.role_name}}</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle"> Menu And Routes
                            <!--<a ng-click="hideForm(); removeAlert()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>-->
                        </h4>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12">

                    <ul class="permissions" ng-if="!loading">
                        <li>
                            <div>Menu</div>
                            <div>Route</div>
                            <div class="action">View</div>
                            <div class="action">Create</div>
                            <div class="action">Edit</div>
                            <div class="action">Delete</div>
                        </li>
                        <!--<li ng-if="user_type=='lco' && role_type=='staff'" ng-repeat="item in parent_routes" ng-init="mIndex=$index">
                            <div><strong>{{item.main_menu}}</strong></div>
                            <div>{{((item.route)? item.route : ' ')}}</div>
                            <div class="action">
                                <input ng-show="item.permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].permission" ng-click="togglePermission(menu_routes[item.main_menu_id].main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <div class="action">
                                <input ng-show="item.create_permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].create_permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].create_permission" ng-click="toggleCreatePermission(menu_routes[item.main_menu_id].main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <div class="action">
                                <input ng-show="item.edit_permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].edit_permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].edit_permission" ng-click="toggleEditPermission(menu_routes[item.main_menu_id].main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <div class="action">
                                <input ng-show="item.delete_permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].delete_permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].delete_permission" ng-click="toggleDeletePermission(menu_routes[item.main_menu_id].main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <ul class="submenu permissions">
                                <li ng-repeat="submenu in item.submenus" ng-init="sIndex=$index">
                                    <div>{{submenu.name}}</div>
                                    <div>{{((submenu.route)? '/'+submenu.route : ' ')}}</div>
                                    <div class="action">
                                        <input ng-show="submenu.permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].submenus[sIndex].permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].submenus[sIndex].permission" ng-click="togglePermission(item.main_menu_id,sIndex)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                    <div class="action">
                                        <input ng-show="submenu.create_permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].submenus[sIndex].create_permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].submenus[sIndex].create_permission" ng-click="toggleCreatePermission(item.main_menu_id,sIndex)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                    <div class="action">
                                        <input ng-show="submenu.edit_permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].submenus[sIndex].edit_permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].submenus[sIndex].edit_permission" ng-click="toggleEditPermission(item.main_menu_id,sIndex)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                    <div class="action">
                                        <input ng-show="submenu.delete_permission=='1'" type="checkbox" ng-checked="menu_routes[item.main_menu_id].submenus[sIndex].delete_permission=='1'" ng-disabled="isDisabled()" ng-model="menu_routes[item.main_menu_id].submenus[sIndex].delete_permission" ng-click="toggleDeletePermission(item.main_menu_id,sIndex)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                </li>
                            </ul>
                        </li>-->
                        <li  ng-repeat="item in menu_routes" ng-init="mIndex=$index">
                            <div><strong>{{item.main_menu}}</strong></div>
                            <div>{{((item.route)? item.route : ' ')}}</div>
                            <div class="action">
                                <input  type="checkbox" ng-checked="item.permission=='1'" ng-disabled="isDisabled()" ng-model="item.permission" ng-click="togglePermission(item.main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <div class="action">
                                <input  type="checkbox" ng-checked="item.create_permission=='1'" ng-disabled="isDisabled()" ng-model="item.create_permission" ng-click="toggleCreatePermission(item.main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <div class="action">
                                <input  type="checkbox" ng-checked="item.edit_permission=='1'" ng-disabled="isDisabled()" ng-model="item.edit_permission" ng-click="toggleEditPermission(item.main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <div class="action">
                                <input  type="checkbox" ng-checked="item.delete_permission=='1'" ng-disabled="isDisabled()" ng-model="item.delete_permission" ng-click="toggleDeletePermission(item.main_menu_id)" ng-true-value="'1'" ng-false-value="'0'"/>
                            </div>
                            <ul class="submenu permissions">
                                <li ng-repeat="submenu in item.submenus">
                                    <div>{{submenu.name}}</div>
                                    <div>{{((submenu.route)? '/'+submenu.route : ' ')}}</div>
                                    <div class="action">
                                        <input  type="checkbox" ng-checked="submenu.permission=='1'" ng-disabled="isDisabled()" ng-model="submenu.permission" ng-click="togglePermission(item.main_menu_id,$index)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                    <div class="action">
                                        <input  type="checkbox" ng-checked="submenu.create_permission=='1'" ng-disabled="isDisabled()" ng-model="submenu.create_permission" ng-click="toggleCreatePermission(item.main_menu_id,$index)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                    <div class="action">
                                        <input  type="checkbox" ng-checked="submenu.edit_permission=='1'" ng-disabled="isDisabled()" ng-model="submenu.edit_permission" ng-click="toggleEditPermission(item.main_menu_id,$index)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                    <div class="action">
                                        <input  type="checkbox" ng-checked="submenu.delete_permission=='1'" ng-disabled="isDisabled()" ng-model="submenu.delete_permission" ng-click="toggleDeletePermission(item.main_menu_id,$index)" ng-true-value="'1'" ng-false-value="'0'"/>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <p ng-if="loading" class="text-center">
                        <strong class="text-success">Loading Permissions... </strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>