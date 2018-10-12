<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
    var from = "<?php echo $from; ?>";
    var to  = "<?php echo $to; ?>";
    var uri = "<?php echo $this->uri->segment(1); ?>";
</script>
<div id="container" ng-controller="AvailableCardList" ng-cloak>
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
                    <div class="col-md-12">
                        <h4 class="widgettitle">
                            Scratch Cards Batch Info Detail

                            <a href="<?php echo site_url($this->uri->segment(1)); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-arrow-left"></i> Back </a>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">


                <div class="col-md-12" ng-if="!showDownloadFrm">
                    <kendo-grid options="mainGridOptions" id="cardInfoGrid"></kendo-grid>
                </div>

            </div>
        </div>
    </div>

</div>



