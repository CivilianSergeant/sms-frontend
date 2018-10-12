<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">
	<div class="panel panel-default">
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					
					<h4 class="widgettitle">Program Details
						<a href="<?php echo site_url('program/edit_view/' . $program_view->get_attribute('id') ) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-pencil"></i> Edit</a>
						<a href="<?php echo site_url('program'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-circle-left"></i> Back</a>
					</h4>
				</div>
				<span class="clearfix"/>
				<hr/>
			</div>
			
			<div class="panel-body form-horizontal">
				<div class="col-md-10">		
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="program_name">Program Name</label>						
							: <?php echo $program_view->get_attribute('program_name'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="lcn">Lcn </label>						
							: <?php echo $program_view->get_attribute('lcn'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="Fingerprint">Program Type</label>						
							 : <?php if($program_view->get_attribute('program_type') == 1) {echo "PPV";}?> 
							<?php if($program_view->get_attribute('program_type') == 2) {echo "Pay Per Month";}?>	
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="Fingerprint">Teleview Level</label>						
							: <?php echo $program_view->get_attribute('visible_level'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="program_status">Program Status</label>						
							<?php $status=$program_view->get_attribute('status'); ?>
						<span>: <?php   if($status != 0){ echo'Active';}else{ echo 'Inactive';} ?></span> 
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="network_id">Emergency Brodcast NetworkID</label>						
							: <?php echo $program_view->get_attribute('network_id'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="transport_stream_id">Emergency Brodcast TransportStreamID</label>													
							: <?php echo $program_view->get_attribute('transport_stream_id'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="service_id">Emergency Brodcast ServiceID</label>						
							: <?php echo $program_view->get_attribute('service_id'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="display_position">Fingerprint Display Position</label>						
							: <?php echo $program_view->get_attribute('display_position'); ?>
					</div>
						<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="position_x">Position X</label>						
							: <?php echo $program_view->get_attribute('position_x'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="position_y">Position Y</label>						
							: <?php echo $program_view->get_attribute('position_y'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="font_type">Font Type</label>						
							
							: <?php echo $program_view->get_attribute('font_type'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="font_color">Font Color</label>						
							
							: <?php echo $program_view->get_attribute('font_color'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="font_size">Font Size</label>						
							
							: <?php echo $program_view->get_attribute('font_size'); ?>
					</div>

					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="font_type">Font Type</label>						
							: <?php if($program_view->get_attribute('font_type') == 1){echo "Arial";} ?>
							<?php if($program_view->get_attribute('font_type') == 2){echo "Microsoft Sans Serif";} ?>
							<?php if($program_view->get_attribute('font_type') == 3){echo "Microsoft Ya Hei";} ?>
							<?php if($program_view->get_attribute('font_type') == 4){echo "STSong";} ?>
							<?php if($program_view->get_attribute('font_type') == 5){echo "KaiTi";} ?>
					</div>
					<div class="form-group">
						<label id="view" id="view" class="col-sm-4 control-label" for="font_size">Font Size</label>						
							: <?php echo $program_view->get_attribute('font_size'); ?>
					</div>
					<div class="form-group">
						<label id="view" id="view" class="col-sm-4 control-label" for="font_color">Font Color</label>							
							: <?php if($program_view->get_attribute('font_color')==1){echo "Tansparent";} ?>
							<?php if($program_view->get_attribute('font_color')==2){echo "Blue";} ?>
							<?php if($program_view->get_attribute('font_color')==3){echo "Black";} ?>
							<?php if($program_view->get_attribute('font_color')==4){echo "Red";} ?>
							<?php if($program_view->get_attribute('font_color')==5){echo "Green";} ?>
							<?php if($program_view->get_attribute('font_color')==6){echo "White";} ?>
					</div>
					<div class="form-group">
						<label id="view" id="view" class="col-sm-4 control-label" for="font_color">Font Background Color</label>							
							: <?php if($program_view->get_attribute('background_color')==1){echo "Tansparent";} ?>
							<?php if($program_view->get_attribute('background_color')==2){echo "Blue";} ?>
							<?php if($program_view->get_attribute('background_color')==3){echo "Black";} ?>
							<?php if($program_view->get_attribute('background_color')==4){echo "Red";} ?>
							<?php if($program_view->get_attribute('background_color')==5){echo "Green";} ?>
							<?php if($program_view->get_attribute('background_color')==6){echo "White";} ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="show_time">Show Duration(sec)</label>													
							: <?php echo $program_view->get_attribute('show_time'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="stop_time">Stop Duration(sec)</label>						
							: <?php echo $program_view->get_attribute('stop_time'); ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="over_flag">Fingerprint Over</label>													
							: <?php if($program_view->get_attribute('over_flag')==0){echo "No";} ?>
							<?php if($program_view->get_attribute('over_flag')==1){echo "Yes";} ?>
						
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="show_background_flag">Show Background Color</label>						
							: <?php if($program_view->get_attribute('show_background_flag')==0){echo "No";} ?>
							<?php if($program_view->get_attribute('show_background_flag')==1){echo "Yes";} ?>
					</div>
					<div class="form-group">
						<label id="view" class="col-sm-4 control-label" for="show_stb_number_flag">Show STB or IC Number</label>						
							: <?php if($program_view->get_attribute('show_stb_number_flag')==0){echo "No";} ?>
							<?php if($program_view->get_attribute('show_stb_number_flag')==1){echo "Yes";} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
