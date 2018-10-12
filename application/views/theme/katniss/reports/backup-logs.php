<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container" ng-controller="BackLogs" ng-cloak>

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

                    <h4 class="widgettitle"> DB Backup Log</h4>

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>



               
            <div class="panel-body">
                 <div class="col-md-12">
                    <kendo-grid id="grid" options="mainGridOptions"></kendo-grid>
                </div>
            </div>
                

    </div>
</div>
</div>