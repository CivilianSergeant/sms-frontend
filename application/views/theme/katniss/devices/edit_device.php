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

								<h4 class="widgettitle"> Update Device

									<a href="<?php echo site_url('devices/view/' . $stbs->stb_id) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
									<a href="<?php echo site_url('devices'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
								</h4>

								<span class="clearfix"></span>
							</div>
							<hr/>
						</div>

						<!-- Form Left Part -->
						<div class="panel-body">
						<form action="<?php echo site_url('set-top-box/update-action'); ?>" method="POST" class="form-horizontal">
								<div class="col-md-12">
									<div class="col-md-10">
										<!--<div class="form-group">
											<label class="col-sm-4 control-label" for="internal_card_number"> Internal Card Number <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="hidden" name="id" value="<?php /*echo $stbs->stb_id; */?>">
												<input type="text" class="form-control" id="internal_card_number" name="internal_card_number" value="<?php /*echo $stbs->internal_card_number; */?>"  required="required" readonly="readonly">
											</div>
										</div>-->
										<div class="form-group">
											<label class="col-sm-4 control-label" for="external_card_number">Device Number <span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="external_card_number" name="external_card_number" value="<?php echo $stbs->device_number; ?>" required="required" readonly="readonly">
											</div>
										</div>							
										<!--<div class="form-group">
											<label class="col-sm-4 control-label" for="stb_card_provider">STB Card Provider <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<select class="form-control" id="stb_card_provider" name="stb_card_provider" required="required">
													<option value="">---Select Provider---</option>
													<?php /*if ($stb_providers) { */?>
													<?php /*foreach ($stb_providers as $value) { */?>
													<option <?php /*if($stbs->stb_card_provider == $value->id) { echo "selected"; } */?> value="<?php /*echo $value->id; */?>"><?php /*echo $value->stb_provider; */?></option>
													<?php /*} */?>
													<?php /*} */?>
												</select>										
											</div>
										</div>	-->
										<div class="form-group">
											<label class="col-sm-4 control-label" for="price"> Price <span style="color:red">*</span></label>						
											<div class="col-sm-4">
												<input type="number" class="form-control" pattern="{11,16}" title="11 to 16 Digits" id="price" name="price" value="<?php echo $stbs->price; ?>" required="required">
											</div>
										</div>
										<div class="col-md-4 col-md-offset-4">
											<input type="hidden" name="id" value="<?php echo $stbs->stb_id; ?>"/>
											<button type="submit" id="buttonsuccess" class="btn btn-success btnNext"> Update Set-Top Box </button> 
										</div>
									</div>
								</div>
							</form> 
						</div>
					</div>
				</div>
				</div>  


