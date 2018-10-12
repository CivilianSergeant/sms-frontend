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
<div id="container" ng-controller="CashStatement" ng-cloak>

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


                    <h4 class="widgettitle"> Cash Statement
                        <!--<a class="btn btn-primary btn-sm pull-right" href="<?php /*echo $_SERVER['REQUEST_URI']; */?>"><i class="fa fa-refresh"></i> Reset</a>-->
                        <!-- <a ng-click="hideForm()" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close</a> -->
                    </h4>
                    

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <form class="form-horizontal">

                <!--<div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="formData.lco_id"
                                kendo-combo-box
                                k-placeholder="'Select LCO'"
                                k-data-text-field="'lco_name'"
                                k-data-value-field="'user_id'"
                                k-filter="'contains'"
                                k-auto-bind="false"
                                k-change = "'loadSubscriber()'"
                                k-min-length="5"
                                k-data-source="lco"                                
                                style="width: 100%"></select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="formData.subscriber_id"
                                kendo-combo-box
                                k-placeholder="'Select Subscriber'"
                                k-data-text-field="'subscriber_name'"
                                k-data-value-field="'user_id'"
                                k-filter="'contains'"
                                k-auto-bind="false"
                                k-change = "'loadPairings()'"
                                k-min-length="5"
                                k-data-source="subscribers"                               
                                style="width: 100%" ></select>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2" style="padding: 0px; margin-left: 15px">
                        <div class="form-group">
                            <div class="col-md-12">
                                <select

                                ng-model="formData.pairing_id"
                                kendo-combo-box
                                k-placeholder="'Select Pairing ID'"
                                k-data-text-field="'pairing_id'"
                                k-data-value-field="'pairing_id'"
                                k-filter="'contains'"
                                k-min-length="5"
                                k-data-source="pairings"
                                style="width: 100%" ></select>
                                
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="col-md-12" >
                    <div class="form-group">
                        <div class="col-md-3">
                            <input style="margin-left:15px;" type="text" ng-model="formData.from_date" kendo-datepicker k-format="'yyyy-MM-dd'"/>
                        </div>
                        <div class="col-md-3" style="margin-left:-65px;">
                            <input type="text" ng-model="formData.to_date" kendo-datepicker k-format="'yyyy-MM-dd'"/>
                        </div>
                    </div>
                </div>
                    <!--<div class="col-md-2" style="padding: 0px; margin-left: 15px">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text"
                                    ng-model="formData.from_date"
                                    placeholder="From Date"
                                    kendo-datepicker

                                    k-format="'yyyy-MM-dd'"
                                />
                            </div>
                        </div>
                    </div>-->
                    <div class="col-md-1" style="padding-left: 35px;">
                        <div class="form-group">
                            <div class="col-md-3">
                                <div class="margin-bottom-sm">
                                    <button class="btn btn-default" ng-click="getStatements()"> Show </button>
                                </div>
                            </div>
                        </div>
                    </div>

               
                <div class="panel-body">
                     <div class="col-md-12">
                         <table class="table table-bordered ">
                             <thead>
                             <tr>
                                 <th class="lightgreen">SL</th>
                                 <th class="lightgreen">Description</th>
                                 <th class="lightgreen">Transaction Date</th>
                                 <th class="lightgreen">Credit</th>
                                 <th class="lightgreen">Debit</th>
                             </tr>
                             </thead>
                             <tbody>
                             <tr ng-repeat="transaction in transactions">
                                 <td>{{($index+1)}}</td>
                                 <td>{{transaction.description}}</td>
                                 <td>{{transaction.transaction_date}}</td>
                                 <td>{{transaction.credit}}</td>
                                 <td>{{transaction.debit}}</td>
                             </tr>
                             </tbody>
                             <tfoot>
                             <tr>
                                 <td colspan="3"></td>
                                 <td><strong>{{totalCredit}}</strong></td>
                                 <td><strong>{{totalDebit}}</strong></td>
                             </tr>
                             <tr>
                                 <td colspan="4">Total Credit</td>
                                 <td><strong>{{totalCredit}}</strong></td>
                             </tr>
                             <tr>
                                 <td colspan="4">Total Debit <span class="pull-right"><strong>-</strong></span></td>
                                 <td><strong>{{totalDebit}}</strong></td>
                             </tr>
                             <tr>
                                 <td colspan="4">Total Balance</td>
                                 <td><strong>{{totalBalance}}</strong></td>
                             </tr>
                             </tfoot>
                         </table>

                    </div>
                </div>
                
        </form>
    </div>
</div>
</div>