<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?php echo PROJECT_PATH; ?>public/theme/katniss/css/lightbox.min.css" type="text/css" />
<script type="text/javascript" src="<?php echo PROJECT_PATH.'public/theme/katniss/'; ?>js/lightbox-plus-jquery.js"></script>
<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>
<script type="text/javascript">
    var token = "<?php echo $user_info->token; ?>";
    var user_id = "<?php echo $user_info->id; ?>";
    var user_type = "<?php echo $user_info->user_type; ?>";
</script>
<div id="container" ng-controller="SubscriberDashboard" ng-cloak>

    <div class="panel panel-default">

        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> Welcome to PLAAS




                    </h4>
                    <span class="clearfix"></span>
                </div>
                <hr style="border-color:#ddd;"/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <a href="<?php echo site_url('subscriber-packages/'.$user_info->token);?>">
                                    <div class="small-box bg-aqua">
                                        <div class="top">
                                            <h6>LCO</h6>
                                        </div>
                                        <div class="inner">
                                            <h3><span>Total </span><?php echo $total_lco;?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-bar-chart"></i>
                                        </div>
                                        <div class="small_box_footer small-box-footer">
                                            <!--<p >Active <?php /*echo $program_active->activeprogram;*/?></p>
                                            <p style="float:right;">Deactive <?php /*echo $program_deactive->deactiveprogram;*/?></p>-->
                                        </div>
                                    </div></a>
                            </div>
                            <!--<div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <!--<a href="<?php //echo site_url('subscriber-addon-packages/'.$user_info->token);*/*/?>">
                                    <div class="small-box bg-green">
                                        <div class="top">
                                            <h6>Subscribes</h6>
                                        </div>
                                        <div class="inner">
                                            <h3><span>Total </span><?php //echo $add_on_package_count;*/*/?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fa fa-bar-chart"></i>
                                        </div>
                                        <div class="small_box_footer small-box-footer">
                                            <!--<p >Active <?php //echo $program_active->activeprogram;*/*/*/?></p>
                                            <p style="float:right;">Deactive <?php //echo $program_deactive->deactiveprogram; ?></p>-->
                                        <!--</div>
                                    </div></a>-->
                            <!--</div>-->
                        </div>
                    </div>

                    <div class="row text-center" ng-show="loader">
                        <h3>Loading</h3>
                        <img  src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
                    </div>
                </div>
            </div>
        </div>







