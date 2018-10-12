			<?php
			defined('BASEPATH') OR exit('No direct script access allowed');
			?>
			<!-- <link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.common-material.min.css" />
			<link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.material.min.css" /> -->
			
			<div id="container">

				<?php if ($this->session->flashdata('success')) { ?>

				<div class="alert alert-success"> 
					<button class="close" aria-label="close" data-dismiss="alert">×</button>
					<p><?php echo $this->session->flashdata('success') ?></p>
				</div>

				<?php } ?>
				
				<div class="panel panel-default">

					<div class="row">

						<div class="col-md-12">
							<div class="panel-heading">

								<h4 class="widgettitle"> IC or Smartcard Detail

									<a href="<?php echo site_url('icsmart-card/edit/' . $ic_smartcards->smart_card_id ) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>
									<a href="<?php echo site_url('icsmart-card'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
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
											<th>Internal Card Number</th>
											<td>: <?php echo $ic_smartcards->internal_card_number; ?></td>
										</tr>
										<tr>
											<th>External Card Number</th>
											<td>: <?php echo $ic_smartcards->external_card_number; ?></td>
										</tr>
										<tr>
											<th>Provider</th>

											<td>: <?php echo $ic_smartcards->stb_provider; ?></td>
										</tr>
										<tr>

											<th>Price</th>
											<td>: <?php echo $ic_smartcards->price; ?></td>
										</tr>
										<tr>
											<th>Status</th>
											<td>
											<?php if($ic_smartcards->is_used == 0) { ?>
											<span class="label label-success">Not Used</span>
											<?php } else { ?>
											<span class='label label-danger'>Used</span>
											<?php } ?>
											</td>
										</tr>
										<tr>
											<th>Assigned Date</th>
											<td><?php echo date("M d, Y", strtotime($ic_smartcards->used_date)); ?></td>
										</tr>
										<?php if($ic_smartcards->subscriber_name) { ?>
										<tr>
											<th>Assigned Subscriber</th>
											<td><?php echo $ic_smartcards->subscriber_name; ?></td>
										</tr>
										<?php } if($ic_smartcards->lco_name) { ?>
										<tr>
											<th>Assigned LCO</th>
											<td><?php echo $ic_smartcards->lco_name; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>  
		</div>

