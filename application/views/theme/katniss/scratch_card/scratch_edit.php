	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	?>

	<div id="container">
		
		<div class="panel panel-default">			

			<div class="row">

				<div class="col-md-12">
					<div class="panel-heading">

						<h4 class="widgettitle"> Update Scratch Card

							<a href="<?php echo site_url('scratch-card-generate/card-view/' . $card_detail->id) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
							<a href="<?php echo site_url("scratch-card-generate/scratch-card-batch-info/".$card_detail->card_id); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
						</h4>

						<span class="clearfix"></span>
					</div>
					<hr/>
				</div>
				<!-- Form Left Part -->
				<div class="panel-body">
					<div class="col-md-6" style="font-size: 15px">
						<div class="table-responsive">
							<form  action="<?php echo site_url('scratch-card-generate/card-update'); ?>" method="POST" name="updateCard" class="form-horizontal">
								<input type="hidden" name="card_id" value="<?php echo $card_detail->id; ?>">
								<table class="table">
								<tbody>
									<tr>
										<th style="text-align: right">Card Number</th>
										<td><?php echo $card_detail->card_no; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Batch Number</th>
										<td><?php echo $card_detail->batch_no; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Is Active</th>
										<td><input type="checkbox" name="is_active" value="1" <?php if($card_detail->is_active == 1) echo 'checked'; ?>></td>
									</tr>
									<tr>
										<th style="text-align: right">Is Suspended</th>
										<td><input type="checkbox" name="is_suspended" value="1" <?php if($card_detail->is_suspended == 1) echo 'checked'; ?>></td>
									</tr>
									<tr>
										<th style="text-align: right">Active From</th>
										<td><?php echo $card_detail->active_from_date ?></td>
									</tr>
									<tr>
										<th style="text-align: right">SerialNumber</th>
										<td><?php echo $card_detail->serial_no; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Is Used</th>
										<td><?php echo ($card_detail->is_used == 1) ? '<span class="text-danger">Used</span>' : '<span class="text-success">Not Used</span>'; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Used date</th>
										<td><?php if($card_detail->used_date) { echo $card_detail->used_date; } else { echo "Not In Used"; } ?></td>
									</tr>
									<tr>
										<th style="text-align: right">LCO </th>
										<td><?php echo $card_detail->lco_name; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Distributor</th>
										<td><?php //echo $card_detail->used_date; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Subscriber</th>
										<td><?php echo $card_detail->subscriber_name; ?></td>
									</tr>
								</tbody>
							</table>
								<div class="col-md-6 pull-right">
									<button type="submit" class="btn btn-success">Update Card</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  

