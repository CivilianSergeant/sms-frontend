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

						<h4 class="widgettitle"> Distributor Detail

							<a href="<?php echo site_url('scratch-card-distributor/distributor-edit/' . $distributor->id); ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit </a>
							<a href="<?php echo site_url('scratch-card-distributor'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
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
										<th style="text-align: right">Distributor Name </th>
										<td><?php echo $distributor->distributor_name; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Primary Phone</th>
										<td><?php echo $distributor->phone1; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Secondary Phone</th>
										<td><?php echo $distributor->phone2; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Present Address</th>
										<td><?php echo $distributor->present_address; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Parmanent Address</th>
										<td><?php echo $distributor->parmanent_address; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Referance Name</th>
										<td><?php echo $distributor->reference_name; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">Referance Phone</th>
										<td><?php echo $distributor->reference_phone; ?></td>
									</tr>
									<tr>
										<th style="text-align: right">National ID</th>
										<td><?php echo $distributor->nid_number; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  

