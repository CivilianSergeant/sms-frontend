<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .modal-backdrop{display: none !important}
    .program-lists{display: table-row}
    .program-lists li{width: 540px; padding: 5px; margin-bottom: 5px; background: #CCFF}

    #sortable{display: inline-block;list-style: none;}
    #sortable li{width: 458px; padding: 5px; margin-bottom: 5px; background: #CCFF}

</style>
<script>
    function programDelete(id)
    {
        $("#sortable #" + id).remove();
    };

    var cat_id = "<?php echo $cat_id; ?>";
</script>     

<div id="container" ng-controller="EditAppCategories" ng-cloak>

    <?php if ($this->session->flashdata('success')) { ?>

        <div class="alert alert-success">
            <button ng-click="closeAlert()" class="close" aria-label="close" data-dismiss="alert">×</button>
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
                    <h4 class="widgettitle">
                        Update App Category
                        <a href="<?php echo site_url('app-categories/view/' . $cat_id); ?>" class="btn btn-success btn-sm pull-right" style="margin-left: 5px;"> View</a>
                        <a href="<?php echo site_url('app-categories'); ?>" class="btn btn-danger btn-sm pull-right"> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <form name="category" id="category" method="POST" ng-submit="saveAppCategory()">
                        <div class="col-md-6">
                            <div class="form-group col-md-12">
                                <label>Category Name <span style="color:red">*</span></label>
                                <input type="hidden" class="form-control" ng-model="formData.id"/>
                                <input type="text" class="form-control" ng-model="formData.category_name" required="required"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Order Index <span style="color:red">*</span></label>						
                                <input type="text" class="form-control" ng-model="formData.order_index" required="required"/>
                            </div>
                        </div>

                        <div id="sortable-area" class="col-md-6">
                            <div class="form-group col-md-12 text-right" style="padding: 0px;">
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"> Add Program </button>
                            </div>
                            <ul id="sortable"></ul>
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <button type="submit" ng-disabled="category.$invalid" class="btn btn-success"> Update </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Program List</h4>
                </div>
                <div class="modal-body">
                    <select class="form-control" ng-model="formData.programType" ng-change="programGrid()">
                        <option value="0">Select Type</option>
                        <option value="LIVE">LIVE</option>
                        <option value="VOD">VOD</option>
                        <option value="CATCHUP">CATCHUP</option>
                    </select>
                    <input type="text" class="form-control" ng-model="formData.searchKey" ng-keyup="searchProgram()" placeholder="Type Program Name"/>
                    <div style="height: 300px; overflow-y: scroll;margin-top: 10px;">
                        <div class="col-md-12 text-center" id="loading" style="display: none">
                            <h3>Loading</h3>
                            <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF'); ?>"/>
                        </div>
                        <ul id="program-list" class="program-lists">
                            <li ng-repeat="x in programs" id="{{x.id}}"><input type="checkbox" ng-click="checkIfExist(x.id)" data-id="{{x.id}}" data-name="{{x.program_name}}"/>  {{x.program_name}}</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button ng-click="setProgram()" type="button" class="btn btn-sm btn-success" data-dismiss="modal"><i class="fa fa-ok"></i> Add</button>
                </div>
            </div>

        </div>
    </div>
</div>

