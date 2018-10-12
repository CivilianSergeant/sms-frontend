<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">
	
	<!-- <a href="<?php echo site_url('location/save-district'); ?>" class="pull-right btn btn-success">Add District</a> -->

	<div class="row">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

		<!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
			<form action="<?php echo site_url('location/save-district'); ?>" method="POST">
				<div class="col-md-12">

					<div class="col-md-4">
						<h3 class="widgettitle">Add New District</h3>
						<div class="form-group">
							<label for="exampleInputPassword1">Country</label>						
							<div class="input-group margin-bottom-sm">
								<span class="input-group-addon"><i class="fa fa-list"></i></span>
								<select class="form-control" id="country_id" name="country_id" required="required">
									<option value="">---Select Country---</option>
									<?php if ($countries) { ?>
										<?php foreach ($countries as $country) { ?> 
											<option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-4">
						<div class="form-group">
							<label for="exampleInputPassword1">Divisions</label>						
							<div class="input-group margin-bottom-sm">
								<span class="input-group-addon"><i class="fa fa-list"></i></span>
								<select class="form-control" id="division_id" name="division_id" disabled required="required">
									
								</select>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="col-md-4">
						<div class="form-group">
							<label for="program_name">District</label>						
							<div class="input-group margin-bottom-sm">
								<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
								<input type="text" class="form-control" id="district_name" name="district_name" placeholder="Enter District Name" required="required">
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="col-md-4">
						<div class="form-group">
							<button type="submit" class="btn btn-success"> Save </button>
							<a href="<?php echo site_url('location'); ?>" class="btn btn-default">Back</a>
						</div>
					</div>
				</div>
			</form>
			

		
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){


	$("#country_id").change(function(){
		var obj = $(this);
		$.ajax({
			url:"<?php echo site_url('location/ajax_get_divisions');?>",
			method:"POST",
			data: {country_id:obj.val()},
			beforeSend:function(){
				$("#division_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="'+BASE_URL+'public/theme/katniss/img/loading_32.gif"/>');
			},
			success:function(e)
			{
				$("#loader").remove();
				var data = $.parseJSON(e);
				var divisionsOption = '<option value="">---Select Division---</option>';
				$.each(data,function(i,el){
					divisionsOption += '<option value="'+data[i].id+'">'+data[i].division_name+'</option>';
				});
				$("#division_id").html(divisionsOption).removeAttr('disabled');
			}
		});
	});

	$("#division_id").change(function(){
		var obj = $(this);
		
		$.ajax({
			url:"<?php echo site_url('location/ajax_get_districts');?>",
			method:"POST",
			data: {division_id:obj.val()},
			beforeSend:function(){
				$("#division_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="'+BASE_URL+'public/theme/katniss/img/loading_32.gif"/>');
			},
			success:function(e)
			{
				$("#loader").remove();
				var data = $.parseJSON(e);
				var districtOption = '<option value="">---Select District---</option>';
				$.each(data,function(i,el){
					districtOption += '<option value="'+data[i].id+'">'+data[i].district_name+'</option>';
				});
				$("#district_id").html(districtOption).removeAttr('disabled');
			}
		});
	});

});
</script>
