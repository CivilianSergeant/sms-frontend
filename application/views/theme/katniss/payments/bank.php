<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                        <h4 class="widgettitle"> Bank Receive </h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="profile" class="tab-pane active" >
                        <form class="form-horizontal" method="post" action="#">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="bank_receipt">Bank Receipt Number<span style="color:red">*</span></label>						
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control"  placeholder="Bank Receipt Number" required="required">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="bank_name">Bank Name <span style="color:red">*</span></label>                       
                                            <div class="col-sm-7">
                                                <select class="form-control" id="district_id">
                                                    <option value="">--Select Bank--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-md-5 control-label" for="branch_name">Branch Name</label>                        
                                            <div class="col-sm-7">
                                                <select class="form-control" id="district_id">
                                                    <option value="">--Select Branch--</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="subscriber_name">Subscriber Name <span style="color:red">*</span></label>                       
                                            <div class="col-sm-7">
                                               <select class="form-control" id="district_id">
                                                    <option value="">--Select Subscriber--</option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label  class="col-md-5 control-label" for="pairing_ID">Pairing ID</label>                      
                                            <div class="col-sm-7">
                                                <select class="form-control" id="district_id">
                                                    <option value="">--Select Pairing--</option>
                                                </select>
                                            </div>
                                        </div>
                    
                                    </div>

                                    <div class="col-md-6">	
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="receive_date">Receive Date</label>                       
                                            <div class="col-sm-7">
                                                <select class="form-control" id="district_id">
                                                    <option value="">--Select Receive--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  class="col-md-5 control-label" for="amount">Amount</label>                       
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>						
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="discount">Discount</label>                       
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="vat">VAT</label>						
                                            <div class="col-sm-7">
                                               <input type="text" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="total_amount">Total Amount</label>						
                                            <div class="col-sm-7">
                                                 <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                <hr/>
                                    <div class="col-md-11 text-right">
                                        <a id="buttonsuccess" id="btnNext" ng-click="saveProfile()" class="btn btn-success btnNext" >Save</a>
                                        <a id="buttoncancel" id="btnNext" ng-click="hideForm()"  class="btn btn-warning btnNext" >Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






