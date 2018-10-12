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

						<h4 class="widgettitle"> Update STB Provider

							<a href="<?php echo site_url('stb-provider/view/' . $provider->get_attribute('id') ) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
							<a href="<?php echo site_url('stb-provider'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
						</h4>

						<span class="clearfix"></span>
					</div>
					<hr/>
				</div>
				<!-- Form Left Part -->
				<div class="panel-body">
					<form action="<?php echo site_url('stb-provider/update-action'); ?>" method="POST" class="form-horizontal">
						<div class="col-md-8">
								<div class="form-group">
									<label class="col-sm-4 control-label" for="stb_type"> Type <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="hidden" id="id" name="id" value="<?php echo $provider->get_attribute('id'); ?>">
										<input type="text" class="form-control" id="stb_type" name="stb_type" value="<?php echo $provider->get_attribute('stb_type'); ?>" required>
									</div>
								</div>			
								<div class="form-group">
									<label class="col-sm-4 control-label" for="stb_supplier">STB Supplier <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="stb_provider" name="stb_provider" value="<?php echo $provider->get_attribute('stb_provider'); ?>" required="required">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-sm-4 control-label" for="description">Description</label>						
									<div class="col-sm-5">
										<textarea class="form-control" id="description" name="description"><?php echo $provider->get_attribute('description'); ?></textarea>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="address1">Address Line 1 <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="address1" name="address1" value="<?php echo $provider->get_attribute('address1'); ?>" required="required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="address2">Address Line 2</label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="address2" name="address2" value="<?php echo $provider->get_attribute('address2'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="country">Country </label>						
									<div class="col-sm-5">
										<select class="form-control" id="country" name="country">
											<option value="">---Select Country---</option>
											<?php if ($countries) { ?>
											<?php foreach ($countries as $country) { ?> 
											<option <?php if($provider->get_attribute('country') == $country->country_name) { echo "selected"; } ?> value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
											<?php } ?>
											<?php } ?>
										</select>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="state"> State </label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="state" name="state" value="<?php echo $provider->get_attribute('state'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="city"> City </label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="city" name="city" value="<?php echo $provider->get_attribute('city'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="zip"> Zip Code </label>						
									<div class="col-sm-5">
										<input type="text" class="form-control" id="zip" name="zip" value="<?php echo $provider->get_attribute('zip'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="email"> Email </label>						
									<div class="col-sm-5">
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $provider->get_attribute('email'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label" for="phone"> Phone <span style="color:red">*</span></label>						
									<div class="col-sm-5">
										<input type="number" pattern=".{11,15}" title="11 to 15 Digits" class="form-control" id="phone" name="phone" value="<?php echo $provider->get_attribute('phone'); ?>" required="required">
									</div>
								</div>
								<div class="col-md-4 col-md-offset-4">
									<button type="submit" id="buttonsuccess" class="btn btn-success btnNext"> Update Provider </button >
								</div>
						</div>
					</form> 
				</div>
			</div>
		</div>
	</div>  

