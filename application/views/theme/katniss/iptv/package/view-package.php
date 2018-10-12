<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
	var package_program = <?php echo $package_programs; ?>;
	var uri = <?php echo $uri; ?>;
</script>
<?php $packageType = preg_match('/(catchup|vod)/',$uri); ?>
<div id="container">
	<div class="panel panel-default">
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">					
					<h4 class="widgettitle">Program Details
						<?php if(in_array($user_info->user_type,array('mso','MSO'))){ ?>
							<a href="<?php echo site_url($uri.'/edit/' . $package->get_attribute('id')); ?>" class="btn btn-success btn-sm pull-right" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>
						<?php } ?>
						<a href="<?php echo site_url($uri); ?>" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-circle-left"></i> Back</a>
					</h4>
				</div>
				<span class="clearfix"/>
				<hr/>
			</div>
			
			<div class="panel-body form-horizontal">
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left">Package Name</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span class="inline">
							: <?php echo $package->get_attribute('package_name'); ?>
						</span>
					</div>
				</div>		
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3  pull-left">Package Duration</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span>
							: <?php 
							$duration = $package->get_attribute('duration');
							echo ($duration==1)? $duration.' Day': $duration.' Days'; 
							?>
						</span>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left">Is Commercial</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<?php $status = $package->get_attribute('is_commercial'); ?>
						<span>: <?php echo (!empty($status))? '<span style="color: green">Yes</span>' : '<span style="color: red">No</span>';?></span>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left">Is Active</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<?php $status = $package->get_attribute('is_active'); ?>
						<span>: <?php echo (!empty($status))? '<span style="color: green">Yes</span>' : '<span style="color: red">No</span>';?></span>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left">No of Program</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span>
							: <?php 
								
								$count_programs = count(json_decode($package_programs)); 
								echo $count_programs . (($count_programs==1)? ' Program' : ' Programs');	
							?>
						</span>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left">Package Logo (STB)</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span class="inline">
							: <?php echo $package->get_attribute('package_stb_logo'); ?>
						</span>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left">Package Logo (STB)</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span class="inline">
							: <?php echo $package->get_attribute('package_mobile_logo'); ?>
						</span>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left"> Poster (STB)</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span class="inline">
							: <?php echo $package->get_attribute('package_poster_stb'); ?>
						</span>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label col-lg-2 col-md-3 col-sm-3 col-xs-3 pull-left"> Poster (Mobile)</label>
					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
						<span class="inline">
							: <?php echo $package->get_attribute('package_poster_mobile'); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if(!$packageType) {  ?>
	<div class="panel panel-default">
		<div class="row">

			<div class="col-lg-12">
				<div class="panel-heading">
					<h5 class="widgettitle">Available Program List</h5>
				</div>
				<hr/>
				<div class="col-lg-12">
				<link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.common-bootstrap.min.css" />
    			<link rel="stylesheet" href="//kendo.cdn.telerik.com/2015.3.1111/styles/kendo.bootstrap.min.css" />
					
					<div id="grid"></div>

					<script>
						$(document).ready(function() {
							$("#grid").kendoGrid({
								dataSource: {
									data: package_program,
									pageSize: 10,
								},
								sortable: true,
								pageable: true,
								scrollable: true,
								resizable: true,
								filterable: true,
								pageable: {
									input: true,
									numeric: false
								},
								columns: [
									{field: "program_id", title: "ID",width: "auto"},
									{field: "program_name", title: "Program Name",width: "auto"},
									{field: "lcn", title: "LCN",width: "auto"},


								]

							});
						});

					</script>

				</div>
			</div>
		</div>

	</div>
	<?php } ?>
</div>
