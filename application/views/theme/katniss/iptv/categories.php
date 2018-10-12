<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var uri = "<?php echo $uri; ?>";
</script>
<div id="container" ng-controller="IptvCategories" ng-cloak>


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
                    <?php 
                        if(preg_match('/live/',$uri)){
                            $uri = 'channel categories';
                        }
                    ?>          
                    <h4 class="widgettitle"><?php echo ucwords(str_replace('-',' ',$uri)); ?></h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body" ng-if="!delete_item">
                <div class="col-md-12">

                    <div class="col-md-12">

                        <form class="form-horizontal" ng-submit="saveCategory()">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">


                                    <label for="iptv_category_id" class="text-right">Categories  </label>
                                    <a ng-click="showCategoryFrm()" class="btn btn-info btn-xs pull-right"> <i class="fa fa-plus"></i> Add Category</a>
                                    <div class="col-md-12" style="border:1px solid gray; overflow-y:scroll;height:250px;">
                                        <ul class="tree">
                                            <li ng-repeat="c in categories">
                                                <a style="cursor:pointer;" ng-click="SelectCategories($index)">{{c.category_name}}</a>
                                    <span>
                                        <a class="text-danger" style="cursor:pointer;font-size:12px;padding:10px;" ng-click="deleteCategory($index)">X</a>
                                    </span>
                                            </li>
                                        </ul>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3" style="margin-left:10px;">
                                <div class="form-group">


                                    <label for="iptv_category_id" class="text-right">Sub Categories </label>
                                    <a ng-click="showSubCategoryFrm()" class="btn btn-info btn-xs pull-right"> <i class="fa fa-plus"></i> Add Sub Category</a>
                                    <div class="col-md-12" style="border:1px solid gray; overflow-y:scroll;height:250px;">
                                        <ul class="tree">
                                            <li ng-repeat="sc in showSubcategories">
                                                <a style="cursor:pointer;" ng-click="selectSubCategory($index)">{{sc.sub_category_name}}</a>
                                    <span>
                                        <a class="text-danger" style="cursor:pointer;font-size:12px;padding:10px;" ng-click="deleteSubCategory($index)">X</a>
                                    </span>
                                            </li>
                                        </ul>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-5" style="background:#f7f7f7;margin-left:50px;height:275px;">
                                <label style="height:25px;line-height:40px;">Add New {{formName}}</label>
                                <hr/>    
                                <div class="form-group" ng-if="showCategoryFrmFlag==1">
                                    <label for="category_name" class="col-md-4 text-right">Category</label>
                                    <div class="col-md-6">

                                        <input type="text" ng-disabled="showSubCategoryFrmFlag==1"  class="form-control" ng-model="formData.category_name"/>
                                    </div>
                                </div>
                                <div class="form-group" ng-if="showSubCategoryFrmFlag==1">
                                    <label for="sub_category_name" class="col-md-4 text-right">Sub Category</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" ng-model="formData.sub_category_name"/>
                                    </div>
                                </div>
                                <div class="form-group" ng-if="showCategoryFrmFlag==1 || showSubCategoryFrmFlag == 1">
                                    <div class="col-md-6 col-md-offset-4">
                                        <input type="submit" class="btn btn-success" value="Save"/>
                                        <input type="button" ng-click="resetForm()" class="btn btn-danger" value="Clear"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr/>
                                    <span>To add New Item Clear Form, <br/>To update select item from the list then change value and click save button</span>
                                </div>
                            </div>


                        </form>
                    </div>
                    <div class="col-md-12">
                        <hr/>
                    </div>
                    <h5 style="padding-left:14px;font-size:18px;">Assign Program to Category</h5><br/>
                    <div class="col-md-3">
                        <h6>Categories [{{(categoryObj != '')?categoryObj.category_name : 'Not Selected' }}]</h6>
                        <div class="col-md-12" style="border:1px solid gray; overflow-y:scroll;height:250px;">
                            <ul class="tree">
                                <li ng-repeat="c in categories">
                                    <a style="cursor:pointer;" ng-click="loadSubCategories($index)">{{c.category_name}}</a>
                                    <span>
                                        <!--<a class="text-danger" style="cursor:pointer;font-size:12px;padding:10px;" ng-click="deleteCategory($index)">X</a>-->
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h6>Sub Categories [{{(subCategoryObj != '')?subCategoryObj.sub_category_name : 'Not Selected' }}]</h6>
                        <div class="col-md-12" style="border:1px solid gray; overflow-y:scroll;height:250px;">
                            <ul class="tree">
                                <li ng-repeat="sc in sub_categories">
                                    <a style="cursor:pointer;" ng-click="loadSelectedPrograms($index)">{{sc.sub_category_name}}</a>
                                    <span>
                                        <!--<a class="text-danger" style="cursor:pointer;font-size:12px;padding:10px;" ng-click="deleteSubCategory($index)">X</a>-->
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="col-md-10 no-padding">
                            <select id="program" kendo-combo-box
                                    ng-model="listData.program"
                                    k-placeholder="'Select Program'"
                                    k-data-text-field="'program_name'"
                                    k-data-value-field="'id'"

                                    k-data-source="programs"
                                    class="form-control" style="width:100%;">

                            </select>
                        </div>
                        <div class="col-md-2 no-padding">
                            <button ng-click="addProgram()" class="btn btn-default btn-sm" style="width:45px;height:28px;"><i class="fa fa-plus-circle"></i></button>
                        </div>
                        <div class="col-md-12" style="border:1px solid gray; overflow-y:scroll;height:250px;">
                            <ul class="tree">
                                <li ng-repeat="ap in assigned_programs">
                                    <span>{{ap.program_name}}
                                        <a class="text-danger" style="cursor:pointer;font-size:12px;padding:10px;" ng-click="deleteProgram($index)">X</a>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--<div class="col-md-12" ng-if="assigned_programs.length>0">
                        <button ng-click="assignProgramCategory()" class="btn btn-success"><i class="fa fa-save"></i> &nbsp; Assign Program To Category</button>
                    </div>-->
                </div>
            </div>
            <div class="col-md-12 text-center" ng-if="delete_item">
                <form class="form-horizontal text-center">
                <div class="col-md-12">
                    <p><strong>Are you sure to delete this Category</strong></p>
                    <hr/>

                        <div class="form-group">
                            <label class="col-md-1 col-md-offset-4">Password</label>
                            <div class="col-md-3">
                                <input id="password" type="password" name="password" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group" style="margin-left:12px">
                            <div class="col-md-3 col-md-offset-4">
                                <input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
                                <input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>

</div>