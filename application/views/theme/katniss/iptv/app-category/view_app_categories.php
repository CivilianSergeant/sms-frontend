<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
    .modal-backdrop{display: none !important}
    .program-lists{display: table-row}
    .program-lists li{width: 540px; padding: 5px; margin-bottom: 5px; background: #CCFF}

    #sortable{display: inline-block;list-style: none;}
    #sortable li{width: 458px; padding: 5px; margin-bottom: 5px; background: #CCFF}
    #sortable li i{display: none}
</style>
<script>
    var cat_id = "<?php echo $cat_id; ?>";
</script>     

<div id="container" ng-controller="EditAppCategories" ng-cloak>
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle">
                        View App Category
                        <a href="<?php echo site_url('app-categories/edit/' . $cat_id); ?>" class="btn btn-success btn-sm pull-right" style="margin-left: 5px;"> Edit</a>
                        <a href="<?php echo site_url('app-categories'); ?>" class="btn btn-danger btn-sm pull-right"> Back</a>
                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                        <div class="col-md-6">
                            <div class="form-group col-md-12">
                                <label>Category Name <span style="color:red">*</span></label>
                                <input type="text" class="form-control" ng-model="formData.category_name" readonly="readonly"/>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Order Index <span style="color:red">*</span></label>						
                                <input type="text" class="form-control" ng-model="formData.order_index" readonly="readonly"/>
                            </div>
                        </div>

                        <div id="sortable-area" class="col-md-6">
                            <div class="col-md-12" style="padding-left: 40px;">
                                <label>Program List</label>
                            </div>
                             <ul id="sortable"></ul>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

