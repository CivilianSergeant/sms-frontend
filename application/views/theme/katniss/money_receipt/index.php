<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
	var token = "<?php echo $user_info->token; ?>";
</script>
<div id="container" ng-controller="MoneyReceipt" ng-cloak>

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
	                
	                    <h4 class="widgettitle"> Assign Money Receipt
	                       <!--  <a href="<?php echo site_url('subscriber'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back</a> -->
	                    </h4>
	                    
	             
	                <span class="clearfix"></span>
	            </div>
	            <hr/>
	        </div>
	        <div class="col-md-12">
	            <div class="panel-body">
	            	<div class="col-md-12">
		                <ul class="tab_nav nav nav-tabs">
		                    <li ng-class="{active:tabs.bulk}"><a class="tab_top" ng-click="setTab('bulk')">Bulk Insert</a></li>
		                    <li ng-class="{active:tabs.single}"><a class="tab_top" ng-click="setTab('single')">Single Insert</a></li>
		                </ul>
		            </div>
		            <div class="tab-content">
		            	<div id="bulk" class="tab-pane active" ng-show="tabs.bulk">
		            		<form name="bulk_money_receipt" id="bulk_money_receipt" method="post" ng-submit="saveBulkMoneyReceipt()">
	            				<div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
		                            <h4>Bulk Money Receipt Assign</h4>
		                        </div>
		                        <div class="row">
		                            <div class="col-md-12">
		                            	<div class="col-md-4">
			                            	<div class="form-group">
			                            		<label class="control-label">Collector <span class="text-danger">*</span></label>
			                            		 <div class="margin-bottom-sm">
			                            		 	<select kendo-combo-box
											                k-placeholder="'Select Collector'"
											                k-data-text-field="'name'"
											                k-data-value-field="'id'"
											                
											                k-data-source="collectors"
											                style="width: 100%" ng-model="formData.collector_id" required="required">
											        </select>
		                            		 	 </div>
		                            		</div>
		                            		<div class="form-group">
		                            			<label>Receipt Book Number <span class="text-danger">*</span></label>
		                            			<div class="margin-bottom-sm">
		                            				<input type="text" class="form-control" ng-model="formData.book_number" required="required"/>
	                            				</div>
	                            			</div>
		                            		<div class="form-group">
		                            			<label class="control-label">Receipt Number <span class="text-danger">*</span></label>
		                            			<div class="margin-bottom-sm">
		                            				<div class="col-md-6" style="padding-left:0px;">
		                            					<input type="text" class="form-control" placeholder="From" ng-model="formData.from" required="required"/>
		                            				</div>
		                            				<div class="col-md-6">
		                            					<input type="text" class="form-control" placeholder="To" ng-model="formData.to" required="required"/>
	                            					</div>
	                            				</div>
	                            			</div>
	                            			<br/><br/>
	                            			<div class="form-group">
	                            				<div class="margin-bottom-sm">
	                            					<input type="submit" class="btn btn-success btn-sm" ng-disabled="isBulkInputValid()" value="Assign Receipts"/>
                            					</div>
                            				</div>

		                            	</div>
		                            </div>
	                            </div>
	            			</form>
	            		</div>
	            		<div id="single" class="tab-pane active" ng-show="tabs.single">
	            			<form name="single_money_receipt" id="single_money_receipt" method="post" ng-submit="saveSingleMoneyReceipt()">
	            				<div class="col-md-12" style="padding-bottom: 20px; margin-top:20px;">
		                            <h4>Single Money Receipt Assign</h4>
		                        </div>
		                        <div class="row">
		                            <div class="col-md-12">
		                            	<div class="col-md-4">
			                            	<div class="form-group">
			                            		<label class="control-label">Collector <span class="text-danger">*</span></label>
			                            		 <div class="margin-bottom-sm">
			                            		 	<select kendo-combo-box
											                k-placeholder="'Select Collector'"
											                k-data-text-field="'name'"
											                k-data-value-field="'id'"
											                
											                k-data-source="collectors"
											                style="width: 100%" ng-model="singleFormData.collector_id" required="required">
											        </select>
		                            		 	 </div>
		                            		</div>
		                            		<div class="form-group">
		                            			<label>Receipt Book Number <span class="text-danger">*</span></label>
		                            			<div class="margin-bottom-sm">
		                            				<input type="text" class="form-control" ng-model="singleFormData.book_number" required="required"/>
	                            				</div>
	                            			</div>
		                            		<div class="form-group">
		                            			<label class="control-label">Receipt Number <span class="text-danger">*</span></label>
		                            			<div class="margin-bottom-sm">
	                            					<input type="text" class="form-control" ng-model="singleFormData.receipt_number" required="required"/>
	                            				</div>
	                            			</div>
	                            			
	                            			<div class="form-group">
	                            				<div class="margin-bottom-sm">
	                            					<input type="submit" class="btn btn-success btn-sm" ng-disabled="isSingleInputValid()" value="Assign Receipts"/>
                            					</div>
                            				</div>

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
</div>