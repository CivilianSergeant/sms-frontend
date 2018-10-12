<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">	

	<div class="panel panel-default" ng-controller="updateProgram">
		<div class="row">
			<div class="col-md-12">
				<?php if ($this->session->flashdata('update success')) { ?>
				<div class="alert alert-success"> <?php echo $this->session->flashdata('update success') ?> </div>
				<?php } ?>
			</div>
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Update Program</h4>

						<a href="<?php echo site_url('program/view/' . $program->get_attribute('id') ) ?>" class="btn btn-success btn-sm pull-right paddin-left" style="margin-left: 10px"><i class="fa fa-search"></i> View</a>
						<a href="<?php echo site_url('program'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-circle-left"></i> Back</a>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="<?php echo site_url('program/update');?>" method="POST" name="addProgram">
					<div class="col-md-12">
						<div class="col-md-10">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="program_name">Program Name <span style="color:red">*</span></label>						
								<div class="col-sm-4">
									<input type="hidden" name="id" value="<?php echo $program->get_attribute('id'); ?>">
									<input type="text" class="form-control" id="program_name" maxlength="20" name="program_name" value="<?php echo $program->get_attribute('program_name'); ?>" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="LCN">LCN <span style="color:red">*</span></label>						
								<div class="col-sm-4" ng-init="lcn = '<?php echo $program->get_attribute('lcn'); ?>'">

									<input type="text" class="form-control" id="LCN" name="LCN" ng-model ="lcn" value="<?php echo $program->get_attribute('lcn'); ?>" required="required">
									<span style="color:red" ng-show="lcnOver">Lcn cant over 3 digit</span>
								</div>					
							</div>

							<div class="form-group">
									<label class="col-sm-4 control-label" for="program_service_id">Service ID </label>						
									<div class="col-sm-4">			
										<input type="text" class="form-control" id="program_service_id" name="program_service_id" value="<?php echo $program->get_attribute('program_service_id'); ?>">
									</div>
								</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="program_type">Program Type</label>						
								<div class="col-sm-4">
									<select name="program_type" id ="program_type" class="form-control">
										<option value="1" <?php if($program->get_attribute('program_type') == 1) {echo "selected=\"selected\"";}?> >PPV</option>
										<option value="2" <?php if($program->get_attribute('program_type') == 2) {echo "selected=\"selected\"";}?> >Pay Per Month</option>

									</select>	
									
									
								</div>
							</div>		
							<div class="form-group">
								<label class="col-sm-4 control-label" for="teleview_level">Teleview Level</label>						
								<div class="col-sm-4">
									
									<select name="teleview_level" id = "teleview_level" class="form-control"> 
										<option value="1" <?php if($program->get_attribute('visible_level') == 1) {echo "selected=\"selected\"";}?>>1</option>
										<option value="2" <?php if($program->get_attribute('visible_level') == 2) {echo "selected=\"selected\"";}?>>2</option>
										<option value="3" <?php if($program->get_attribute('visible_level') == 3) {echo "selected=\"selected\"";}?>>3</option>
										<option value="4" <?php if($program->get_attribute('visible_level') == 4) {echo "selected=\"selected\"";}?>>4</option>
										<option value="5" <?php if($program->get_attribute('visible_level') == 5) {echo "selected=\"selected\"";}?>>5</option>
										<option value="6" <?php if($program->get_attribute('visible_level') == 6) {echo "selected=\"selected\"";}?>>6</option>
										<option value="7" <?php if($program->get_attribute('visible_level') == 7) {echo "selected=\"selected\"";}?>>7</option>
										<option value="8" <?php if($program->get_attribute('visible_level') == 8) {echo "selected=\"selected\"";}?>>8</option>
										<option value="9" <?php if($program->get_attribute('visible_level') == 9) {echo "selected=\"selected\"";}?>>9</option>
									</select>
								</div>
							</div>	
							<div class="form-group">
								<label class="col-sm-4 control-label" for="program_status">Program Status</label>						
								<div class="col-sm-4">
									
									<select name="program_status"  id="program_status"class="form-control"> 
										<option value="1" <?php if($program->get_attribute('status') == 1) {echo "selected=\"selected\"";}?>>Active</option>
										<option value="0" <?php if($program->get_attribute('status') == 0) {echo "selected=\"selected\"";}?>>In Active</option>
							
									</select>
								</div>
							</div>			
							<div class="form-group">
								<label class="col-sm-4 control-label" for="network_id">Emergency Brodcast NetworkID</label>						
								<div class="col-sm-4">
									
									<input type="text" class="form-control" id="network_id" name="network_id" value="<?php if($program->get_attribute('network_id') == 0){echo '0';}else{echo $program->get_attribute('network_id');} ?>" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="transport_stream_id">Emergency Brodcast TransportStreamID</label>						
								<div class="col-sm-4">
									
									<input type="text" class="form-control" id="transport_stream_id" value="<?php if($program->get_attribute('transport_stream_id') == 0){echo '0';}else{echo $program->get_attribute('transport_stream_id');} ?>" name="transport_stream_id"  required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="service_id">Emergency Brodcast ServiceID</label>						
								<div class="col-sm-4">
									
									<input type="text" class="form-control" id="service_id" name="service_id" value="<?php if($program->get_attribute('service_id') == 0){echo '0';}else{echo $program->get_attribute('service_id');} ?>" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="display_position">Fingerprint Display Position</label>						
								<div class="col-sm-4">
									
									<select class="form-control" id="display_position" name="display_position"  required="required">
										
										<option value="4" <?php if($program->get_attribute('display_position') =='4'){echo "selected=\"selected\"";} ?>>RANDOM</option>
										<option value="255" <?php if($program->get_attribute('display_position') =='255'){echo "selected=\"selected\"";} ?>>FINGER_POS_FIXED</option>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="position_x">Position X</label>						
								<div class="col-sm-4">
									
									<input type="text" class="form-control" id="position_x" value="<?php if($program->get_attribute('position_x') == 0){echo '0';}else{echo $program->get_attribute('position_x');} ?>" name="position_x" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="position_y">Position Y</label>						
								<div class="col-sm-4">
									
									<input type="text" class="form-control" id="position_y" value="<?php if($program->get_attribute('position_y') == 0){echo '0';}else{echo $program->get_attribute('position_y');} ?>" name="position_y" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="font_type">Font Type</label>						
								<div class="col-sm-4">
									<select class="form-control" id="font_type" name="font_type" required="required">
										
										<option value="0" <?php if($program->get_attribute('font_type')==0){ echo "selected=\"selected\"";} ?>>Arial</option>
										<option value="1" <?php if($program->get_attribute('font_type')==1){ echo "selected=\"selected\"";} ?>>Microsoft Sans Serif</option>
										<option value="2" <?php if($program->get_attribute('font_type')==2){ echo "selected=\"selected\"";} ?>>Microsoft Ya Hei</option>
										<option value="3" <?php if($program->get_attribute('font_type')==3){ echo "selected=\"selected\"";} ?>>STSong</option>
										<option value="4" <?php if($program->get_attribute('font_type')==4){ echo "selected=\"selected\"";} ?>>KaiTi</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="font_size">Font Size</label>						
								<div class="col-sm-4">
									<select class="form-control" id="font_size" name="font_size" required="required">
										<option value="8" <?php if($program->get_attribute('font_size')==8){echo "selected=\"selected\"";}?>>8</option>
										<option value="12" <?php if($program->get_attribute('font_size')==12){echo "selected=\"selected\"";}?>>12</option>
										<option value="16" <?php if($program->get_attribute('font_size')==16){echo "selected=\"selected\"";}?>>16</option>
										<option value="18" <?php if($program->get_attribute('font_size')==18){echo "selected=\"selected\"";}?>>18</option>
										<option value="20" <?php if($program->get_attribute('font_size')==20){echo "selected=\"selected\"";}?>>20</option>
										<option value="22" <?php if($program->get_attribute('font_size')==22){echo "selected=\"selected\"";}?>>22</option>
										<option value="24" <?php if($program->get_attribute('font_size')==24){echo "selected=\"selected\"";}?>>24</option>
										<option value="26" <?php if($program->get_attribute('font_size')==26){echo "selected=\"selected\"";}?>>26</option>
										<option value="28" <?php if($program->get_attribute('font_size')==28){echo "selected=\"selected\"";}?>>28</option>
										<option value="36" <?php if($program->get_attribute('font_size')==36){echo "selected=\"selected\"";}?>>36</option>
										<option value="48" <?php if($program->get_attribute('font_size')==48){echo "selected=\"selected\"";}?>>48</option>
										<option value="72" <?php if($program->get_attribute('font_size')==72){echo "selected=\"selected\"";}?>>72</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="font_color">Font Color</label>						
								<div class="col-sm-4">
									<select class="form-control" id="font_color" name="font_color" required="required">
										<option value="0" <?php if($program->get_attribute('font_color')==0){echo "selected=\"selected\"";} ?>>Tansparent</option>
										<option value="-16776961" <?php if($program->get_attribute('font_color')=='-16776961'){echo "selected=\"selected\"";} ?>>Blue</option>
										<option value="255" <?php if($program->get_attribute('font_color')=='255'){echo "selected=\"selected\"";} ?>>Black</option>
										<option value="65535" <?php if($program->get_attribute('font_color')=='65535'){echo "selected=\"selected\"";} ?>>Red</option>
										<option value="8388863" <?php if($program->get_attribute('font_color')=='8388863'){echo "selected=\"selected\"";} ?>>Green</option>
										<option value="-1" <?php if($program->get_attribute('font_color')=='-1'){echo "selected=\"selected\"";} ?>>White</option>
										<option value="-65281" <?php if($program->get_attribute('font_color')=='-65281'){echo "selected=\"selected\"";} ?>>Cyan</option>
										<option value="16777215" <?php if($program->get_attribute('font_color')=='16777215'){echo "selected=\"selected\"";} ?>>Yellow</option>
										<option value="10878975" <?php if($program->get_attribute('font_color')=='10878975'){echo "selected=\"selected\"";} ?>>Orange</option>
										<option value="-2147450625" <?php if($program->get_attribute('font_color')=='-2147450625'){echo "selected=\"selected\"";} ?>>Purple</option>
										<option value="-2139062017" <?php if($program->get_attribute('font_color')=='-2139062017'){echo "selected=\"selected\"";} ?>>Gray</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="background_color">Font Background Color</label>						
								<div class="col-sm-4">
									<select class="form-control" id="background_color" name="background_color" required="required">
										<option value="0" <?php if($program->get_attribute('background_color')==0){echo "selected=\"selected\"";} ?>>Tansparent</option>
										<option value="-16776961" <?php if($program->get_attribute('background_color')=='-16776961'){echo "selected=\"selected\"";} ?>>Blue</option>
										<option value="255" <?php if($program->get_attribute('background_color')=='255'){echo "selected=\"selected\"";} ?>>Black</option>
										<option value="65535" <?php if($program->get_attribute('background_color')=='65535'){echo "selected=\"selected\"";} ?>>Red</option>
										<option value="8388863" <?php if($program->get_attribute('background_color')=='8388863'){echo "selected=\"selected\"";} ?>>Green</option>
										<option value="-1" <?php if($program->get_attribute('background_color')=='-1'){echo "selected=\"selected\"";} ?>>White</option>
										<option value="-65281" <?php if($program->get_attribute('background_color')=='-65281'){echo "selected=\"selected\"";} ?>>Cyan</option>
										<option value="16777215" <?php if($program->get_attribute('background_color')=='16777215'){echo "selected=\"selected\"";} ?>>Yellow</option>
										<option value="10878975" <?php if($program->get_attribute('background_color')=='10878975'){echo "selected=\"selected\"";} ?>>Orange</option>
										<option value="-2147450625" <?php if($program->get_attribute('background_color')=='-2147450625'){echo "selected=\"selected\"";} ?>>Purple</option>
										<option value="-2139062017" <?php if($program->get_attribute('background_color')=='-2139062017'){echo "selected=\"selected\"";} ?>>Gray</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="show_time">Show Duration(sec)</label>						
								<div class="col-sm-4">
									<input type="text" class="form-control" id="show_time" value = "<?php if($program->get_attribute('show_time') == 0){echo '0';}else{echo $program->get_attribute('show_time');} ?>" name="show_time" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="stop_time">Stop Duration(sec)</label>						
								<div class="col-sm-4">
									<input type="text" class="form-control" id="stop_time" value = "<?php if($program->get_attribute('stop_time') == 0){echo '0';}else{echo $program->get_attribute('stop_time');} ?>" name="stop_time" required="required">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="over_flag">Fingerprint Over</label>						
								<div class="col-sm-4">
									<input type="checkbox" name="over_flag" value="1" id = "over_flag" <?php if($program->get_attribute('over_flag') == 1) { echo "checked"; } ?>> 
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="show_background_flag">Show Background Flag</label>						
								<div class="col-sm-4">
									<input type="checkbox" name="show_background_flag" value="1" id = "show_background_flag"  <?php if($program->get_attribute('show_background_flag') == 1) { echo "checked"; } ?>> 
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="show_stb_number_flag">Show STB or IC Number</label>						
								<div class="col-sm-4">
									<input type="checkbox" name="show_stb_number_flag" value="1" id ="show_stb_number_flag" <?php if($program->get_attribute('show_stb_number_flag') == 1) { echo "checked"; } ?>> 
								</div>
							</div>
						</div>
						<div class="col-md-4 col-md-offset-4">
							<input type="submit" class="btn btn-success" id="buttonsuccess" value="Submit" ng-disabled="addProgram.$invalid"> <a href="<?php echo site_url('program');?>"><button type="button" id="buttoncancel" class="btn btn-danger">Cancel</button></a>
						</div>
					</div>
				</form> 
			</div>
		</div>
	</div>
</div>


