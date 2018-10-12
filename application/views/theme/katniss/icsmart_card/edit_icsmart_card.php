			<?php
			defined('BASEPATH') OR exit('No direct script access allowed');
			?>
			<!-- <link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.common-material.min.css" />
			<link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.material.min.css" /> -->
			
			<div id="container">
				
				<div class="panel panel-default">

					<div class="row">

						<div class="col-md-12">
							<div class="panel-heading">

								<h4 class="widgettitle"> Update IC or Smartcard

									<a href="<?php echo site_url('icsmart-card/view/' . $ic_smartcards->smart_card_id ) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> Edit</a>
									<a href="<?php echo site_url('icsmart-card'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
								</h4>

								<span class="clearfix"></span>
							</div>
							<hr/>
						</div>

						<!-- Form Left Part -->
						<div class="panel-body">
						<form action="<?php echo site_url('icsmart-card/update-action'); ?>" method="POST" class="form-horizontal">
								<div class="col-md-12">
									<div class="col-md-10">
										<div class="form-group">
											<label class="col-sm-4 control-label" for="internal_card_number"> Internal Card Number <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="hidden" name="id" value="<?php echo $ic_smartcards->smart_card_id; ?>">
												<input type="text" class="form-control" id="internal_card_number" name="internal_card_number" value="<?php echo $ic_smartcards->internal_card_number; ?>"  required="required" readonly="readonly">
											</div>
										</div>			
										<div class="form-group">
											<label class="col-sm-4 control-label" for="external_card_number">External Card Number <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="text" pattern=".{16,16}" title="16 characters" class="form-control" id="external_card_number" name="external_card_number" value="<?php echo $ic_smartcards->external_card_number; ?>" required="required" readonly="readonly">
											</div>
										</div>							
										<div class="form-group">
											<label class="col-sm-4 control-label" for="smart_card_provider">Smart Card Provider <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<select class="form-control" id="smart_card_provider" name="smart_card_provider" required="required">
													<option value="">---Select Provider---</option>

													<?php if ($card_providers) { ?>
													<?php foreach ($card_providers as $value) { ?> 
													<option <?php if($ic_smartcards->smart_card_provider == $value->id) { echo "selected"; } ?> value="<?php echo $value->id; ?>"><?php echo $value->stb_provider; ?></option>
													<?php } ?>
													<?php } ?>
												</select>										
											</div>
										</div>									
										<div class="form-group">
											<label class="col-sm-4 control-label" for="price"> Price <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="number" class="form-control" id="price" name="price" value="<?php echo $ic_smartcards->price; ?>" required="required">
											</div>
										</div>
										<div class="col-md-4 col-md-offset-4">									
											<button type="submit" id="buttonsuccess" class="btn btn-success btnNext"> Update IC or Smartcard </button> 
										</div>
									</div>
								</div>
							</form> 
						</div>
					</div>
				</div>
				</div>  

