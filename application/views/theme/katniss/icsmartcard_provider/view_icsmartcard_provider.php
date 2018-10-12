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

						<h4 class="widgettitle"> IC or Smartcard Provider Detail

							<a href="<?php echo site_url('icsmartcard-provider/edit/' . $provider->get_attribute('id')) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>
							<a href="<?php echo site_url('icsmartcard-provider'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
						</h4>

						<span class="clearfix"></span>
					</div>
					<hr/>
				</div>
				<!-- Form Left Part -->
				<div class="panel-body">
					
					<div class="col-md-6" style="font-size: 15px">
						<div class="table-responsive">          
							<table class="table">     
								<tbody>
									<tr>
										<th style="text-align: right">Type</th>
										<td>: <?php echo $provider->get_attribute('stb_type'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Provide</th>
										<td>: <?php echo $provider->get_attribute('stb_provider'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Description</th>
										<td>: <?php echo $provider->get_attribute('description'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Address 1</th>
										<td>: <?php echo $provider->get_attribute('address1'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Address 2</th>
										<td>: <?php echo $provider->get_attribute('address2'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Country</th>
										<td>: <?php echo $provider->get_attribute('country'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">State</th>
										<td>: <?php echo $provider->get_attribute('state'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">City</th>
										<td>: <?php echo $provider->get_attribute('city'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Zip Code</th>
										<td><?php echo $provider->get_attribute('zip'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Email</th>
										<td>: <?php echo $provider->get_attribute('email'); ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Phone</th>
										<td>: <?php echo $provider->get_attribute('phone'); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  

