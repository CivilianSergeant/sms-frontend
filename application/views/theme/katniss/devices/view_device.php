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

								<h4 class="widgettitle"> Device Detail

									<a href="<?php echo site_url('devices/edit/' . $stbs->stb_id) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>
									<a href="<?php echo site_url('devices'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
								</h4>

								<span class="clearfix"></span>
							</div>
							<hr/>
						</div>

						<!-- Form Left Part -->
						<div class="panel-body">
						<div class="col-md-6">
							<div class="table-responsive">          
								<table class="table">
									<tbody>

										<tr>
											<th>Device Number</th>
											<td>: <?php echo $stbs->device_number; ?></td>
										</tr>
										<tr>
											<th>Price</th>
											<td>: <?php echo $stbs->price; ?></td>
										</tr>
										<tr>
											<th>Status</th>
											<td>
											<?php if($stbs->is_used == 0) { ?>
											<span class="label label-success">Not Used</span>
											<?php } else { ?>
											<span class='label label-danger'>Used</span>
											<?php } ?>
											</td>
										</tr>
										<tr>
											<th>Assigned Date</th>
											<td><?php echo date("M d, Y", strtotime($stbs->used_date)); ?></td>
										</tr>
										<?php if($stbs->subscriber_name) { ?>
										<tr>
											<th>Assigned Subscriber</th>
											<td><?php echo $stbs->subscriber_name; ?></td>
										</tr>
										<?php } if($stbs->lco_name) { ?>
										<tr>
											<th>Assigned LCO</th>
											<td><?php echo $stbs->lco_name; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>  
		</div>

