<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .col-md-5{width:42.66666667%;}
    .lightgreen{background: #92f4c7;}
    .table-bordered{border:1px solid;}
    .table-bordered>tfoot>tr>td,.table-bordered>tbody>tr>td,.table-bordered>thead>tr>th{border:1px solid #347054;}
    .table-striped>tbody>tr(2n){
        background: red;
    }
</style>
<div id="container" ng-controller="parkingReport" ng-cloak>

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


                    <h4 class="widgettitle"> Parking Report</h4>


                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal" ng-submit="searchResult()">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                    ng-model="formData.stb_id"
                                    kendo-combo-box
                                    k-placeholder="'Select STB'"
                                    k-data-text-field="'external_card_number'"
                                    k-data-value-field="'id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="stb_cards"

                                    style="width: 100%" ></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                    ng-model="formData.card_id"
                                    kendo-combo-box
                                    k-placeholder="'Select CARD'"
                                    k-data-text-field="'external_card_number'"
                                    k-data-value-field="'id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="ic_cards"

                                    style="width: 100%" ></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select
                                    ng-model="formData.subscriber_id"
                                    kendo-combo-box
                                    k-placeholder="'Select Old Subscriber'"
                                    k-data-text-field="'subscriber_name'"
                                    k-data-value-field="'user_id'"
                                    k-filter="'contains'"
                                    k-min-length="5"
                                    k-data-source="subscribers"
                                    style="width: 100%" >
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input  placeholder="From Date" ng-model="formData.from_date" type="text" kendo-datepicker k-format="'yyyy-MM-dd'"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input placeholder="To Date" ng-model="formData.to_date" type="text" kendo-datepicker k-format="'yyyy-MM-dd'"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" class="btn btn-primary" value="Show Report"/>
                            </div>
                        </div>
                    </div>
                </div>

            </form>



        </div>

    </div>
    <div class="panel panel-default">
        <div class="col-md-12">
            <div class="panel-heading">


                <h4 class="widgettitle">Search Result: </h4>


                <span class="clearfix"></span>
            </div>
            <hr/>
        </div>

        <div class="panel-body">
            <div class="row">

                    <div class="col-md-12">
                        <kendo-grid id="grid" options="mainGridOptions"></kendo-grid>
                    </div>

            </div>
        </div>
    </div>
</div>