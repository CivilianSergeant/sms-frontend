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
<div id="container" ng-controller="Subscribers" ng-cloak>

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
    <div class="row" >

        <div class="col-md-12">
            <div class="panel-heading">
                
                    <h4 class="widgettitle"> Add-on Subscribers</h4>
                    
             
                <span class="clearfix"></span>
            </div>
            <hr/>
        </div>

        <div class="col-md-12" ng-if="!loader">
            <div class="panel-body">
                <kendo-grid options="mainGridOptions">
                </kendo-grid>
            </div>
        </div>
        <div class="col-md-12 text-center" ng-show="loader">

            <h3>Loading</h3>
            <img src="<?php echo base_url('public/theme/katniss/img/loading_48.GIF');?>"/>
        </div>
    </div>
    
</div>
</div>
<script type="text/javascript">
    function checkPass(){
        var re_pass = document.getElementById('re_password').value;
        var pass = document.getElementById('password').value;
        if(re_pass != pass)
        {
            document.getElementById("errMsg").innerHTML = "Password Didn't Match!";
        }
    }
</script>






