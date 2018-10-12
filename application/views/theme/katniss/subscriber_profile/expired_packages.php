<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>
<div id="container">

    <?php if ($this->session->flashdata('success')) { ?>

        <div class="alert alert-success"> 
            <button class="close" aria-label="close" data-dismiss="alert">×</button>
            <p><?php echo $this->session->flashdata('success') ?></p>
        </div>

    <?php } ?>
    
    

    <div class="panel panel-default">

        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    
                        <h4 class="widgettitle"> Expired Package List of Subscriber: [<?php echo $subscriber_profile->get_attribute('subscriber_name'); ?>]
                            
                            <a href="<?php echo site_url('subscriber'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                        </h4>
                        
                 
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="lightgreen">Package Name</th>
                                <th class="lightgreen">Package Price</th>
                                <th class="lightgreen">Duration</th>
                                <th class="lightgreen">Start Date</th>
                                <th class="lightgreen">Expire Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($packages)) { ?>
                            <?php foreach($packages as $package){ ?> 
                            <tr>
                                <td><?php echo $package->package_name; ?></td>
                                <td><?php echo $package->price; ?></td>
                                <td><?php echo $package->duration; ?></td>
                                <td><?php echo $package->package_start_date; ?></td>
                                <td><?php echo $package->package_expire_date; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="#" id="buttoncancel" class="btn btn-primary btn-sm">Display All Packages</a>
                </div>
            </div>
        </div>
    </div>
</div>