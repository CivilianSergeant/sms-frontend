<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">


	<div class="alert alert-warning hidden">
		<button class="close">×</button>
		<span class="warning_messages"></span>
	</div>
	<div class="panel panel-default">
	<div class="row">

		<div class="panel-body">
		<?php
		if(!empty($permissions) && $permissions->create_permission==1) { ?>
		<div class="col-md-6">
			<div class="panel-heading">
				<div class="col-md-12">
					<h4 class="widgettitle">Add Location</h4>
				</div>
			</div>
			<br/><br/>
			<form id="locationFrm" action="<?php echo site_url('location/save-location'); ?>" method="POST">
				
					
				<div class="col-md-12">
					<div class="col-md-6">
						
						<div class="form-group">
							<label for="exampleInputPassword1">Country</label>						
								
								<select class="form-control" id="country_id" name="country_id" >
									<option value="">---Select Country---</option>
									<?php if ($countries) { ?>
										<?php foreach ($countries as $country) { ?> 
											<option value="<?php echo $country->id; ?>"><?php echo $country->country_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
								<input type="text" class="form-control hidden" name="country_name"/>
						</div>

					</div>
					<div class="col-lg-2" style="margin-top:22px;">
						<p><a id="create_country" href="javascript:void(0);" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i></a></p>
						<a id="select_country" href="javascript:void(0);" class="btn btn-default btn-sm hidden" style="margin-top:-9px;"><i class="fa fa-list-alt"></i></a>
					</div>
				</div>
				<div id="division_box" class="col-md-12 hidden">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputPassword1">Divisions</label>						
							
								<select class="form-control" id="division_id" name="division_id" disabled>
									
								</select>
								<input type="text" class="form-control hidden" name="division_name"/>
						</div>
					</div>
					<div class="col-lg-2" style="margin-top:22px;">
						<a id="create_division" href="javascript:void(0);" class="btn btn-default btn-sm" title="Create Division"><i class="fa fa-plus-circle"></i></a>
						<a id="select_division" href="javascript:void(0);" class="btn btn-default btn-sm hidden" title="Select Division"><i class="fa fa-list-alt"></i></a>
					</div>
				</div>
				<div id="district_box" class="col-md-12 hidden">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputPassword1">District</label>						
							
								<select class="form-control" id="district_id" name="district_id" disabled>
									
								</select>
								<input type="text" class="form-control hidden" name="district_name"/>
						</div>
					</div>
					<div class="col-lg-2" style="margin-top:22px;">
						<a id="create_district" href="javascript:void(0);" class="btn btn-default btn-sm" title="Create District"><i class="fa fa-plus-circle"></i></a>
						<a id="select_district" href="javascript:void(0);" class="btn btn-default btn-sm hidden" title="Select District"><i class="fa fa-list-alt"></i></a>
					</div>
				</div>
				<div id="area_box" class="col-md-12 hidden">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputPassword1">Area</label>						
							
								<select class="form-control" id="area_id" name="area_id" disabled>
									
								</select>
								<input type="text" class="form-control hidden" name="area_name"/>
						</div>
					</div>
					<div class="col-lg-2" style="margin-top:22px;">
						<a id="create_area" href="javascript:void(0);" class="btn btn-default btn-sm" title="Create Area"><i class="fa fa-plus-circle"></i></a>
						<a id="select_area" href="javascript:void(0);" class="btn btn-default btn-sm hidden" title="Select Area"><i class="fa fa-list-alt"></i></a>
					</div>
				</div>
				<div id="sub_area_box" class="col-md-12 hidden">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputPassword1">Sub Area</label>						
							
								<select class="form-control" id="sub_area_id" name="sub_area_id" disabled>
									
								</select>
								<input type="text" class="form-control hidden" name="sub_area_name"/>
						</div>
					</div>
					<div class="col-lg-2" style="margin-top:22px;">
						<a id="create_sub_area" href="javascript:void(0);" class="btn btn-default btn-sm" title="Create Sub Area"><i class="fa fa-plus-circle"></i></a>
						<a id="select_sub_area" href="javascript:void(0);" class="btn btn-default btn-sm hidden" title="Select Sub Area"><i class="fa fa-list-alt"></i></a>
					</div>
				</div>
				<div id="road_box" class="col-md-12 hidden">
					<div class="col-md-6">
						<div class="form-group">
							<label for="exampleInputPassword1">Road</label>											
								
								<input type="text" class="form-control hidden" name="road_name"/>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="col-md-4">
						<div class="form-group">
							<button type="submit" id="buttonsuccess" class="btn btn-success"> Save </button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<?php } ?>
			<div class="col-md-6 geo-location">
				<div class="panel-heading">
					<h4>Geo Locations</h4>
				</div>
				<div class="col-md-12" id="showLocations" style="overflow-y:scroll;height:450px;">
					
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
<script type="text/javascript">

$(function(){
	var loadLocations = function(){
		$("#showLocations").load(SITE_URL+'location/get_tree_view','',function(e){
			var obj = $(this);
			obj.html(e);
			angular.element("#Notify").scope().loadNotification();
		});
	};
	
	loadLocations();
	
	$("#locationFrm").submit(function(){
		var obj = $(this);
		$.ajax({
			type:"POST",
			url : SITE_URL+'location/save-location',
			data: obj.serialize(),
			beforeSend:function(){

			},
			success:function(e){
				var data = $.parseJSON(e);
				if(data.status == 400){
					$(".alert").removeClass('hidden').children('.warning_messages').text(data.warning_messages);
					return;
				}
				switch(data.type){
					case 'country':
						var countryName = $("input[name=country_name]").val();
						$("#country_id").append('<option value="'+data.id+'">'+countryName+'</option>');
						break;
					case 'division':
						var divisionName = $("input[name=division_name]").val();
						$("#division_id").append('<option value="'+data.id+'">'+divisionName+'</option>');
						break;	
					case 'district':
						var districtName = $("input[name=district_name]").val();
						$("#district_id").append('<option value="'+data.id+'">'+districtName+'</option>');
						break;
					case 'area':
						var areaName = $("input[name=area_name]").val();
						$("#area_id").append('<option value="'+data.id+'">'+areaName+'</option>');
						break;
					
				}
				obj.find('input[type=text]').val('')
				loadLocations();
			}
		});
		return false;
	});

	/*** Create Country ***/
	$("#create_country").click(function(){
		$("#country_id").addClass('hidden');
		$("input[name=country_name]").removeClass('hidden').focus();
		$(this).addClass('hidden');
		$("#select_country").removeClass('hidden');		
		
		$("#division_box").addClass('hidden');
		$("#district_box").addClass('hidden');
		$("#area_box").addClass('hidden');
		$("#sub_area_box").addClass('hidden');
		$("#sub_sub_area_box").addClass('hidden');
		$("#road_box").addClass('hidden');
		$("#division_id").children().eq(0).attr('selected','selected');
		$("input[name=division_name]").val('');
		$("h4.widgettitle").html('Add Country');
		return false;
	});

	/*** Select Country ***/
	$("#select_country").click(function(){
		$("#country_id").removeClass('hidden');
		$("#country_id").val("");
		$("input[name=country_name]").addClass('hidden');
		$(this).addClass('hidden');
		$("#create_country").removeClass('hidden');

		$("#country_id").children().eq(0).attr('selected','selected');
		$("input[name=division_name]").val('');
		$("h4.widgettitle").html('Add Location');
		return false;
	});

	/*** Create Division ***/
	$("#create_division").click(function(){
		$("#division_id").addClass('hidden');
		$("input[name=division_name]").removeClass('hidden').focus();
		$(this).addClass('hidden');
		$("#district_id").children().eq(0).attr('selected','selected');
		$("#district_box").addClass('hidden');
		$("#area_box").addClass('hidden');
		$("#sub_area_box").addClass('hidden');
		$("#sub_sub_area_box").addClass('hidden');
		$("#road_box").addClass('hidden');
		$("#select_division").removeClass('hidden');
		$("h4.widgettitle").html('Add Division');
	});

	/*** Select Division ***/
	$("#select_division").click(function(){
		$("#division_id").removeClass('hidden');
		$("#division_id").val("");
		$("input[name=division_name]").addClass('hidden');
		$(this).addClass('hidden');
		$("#division_id").children().eq(0).attr('selected','selected');
		$("#create_division").removeClass('hidden');
		$("input[name=district_name]").val('');
		$("h4.widgettitle").html('Add Location');
	});

	/*** Create District ***/
	$("#create_district").click(function(){
		$("#district_id").addClass('hidden');
		$("input[name=district_name]").removeClass('hidden').focus();
		$(this).addClass('hidden');
		$("#area_box").addClass('hidden');
		$("#sub_area_box").addClass('hidden');
		$("#sub_sub_area_box").addClass('hidden');
		$("#road_box").addClass('hidden');
		$("#area_id").children().eq(0).attr('selected','selected');
		$("#select_district").removeClass('hidden');
		$("h4.widgettitle").html('Add District');
	});

	/*** Select District ***/
	$("#select_district").click(function(){
		$("#district_id").removeClass('hidden');
		$("#district_id").val("");
		$("input[name=district_name]").addClass('hidden');
		$(this).addClass('hidden');
		$("#district_id").children().eq(0).attr('selected','selected');
		$("#create_district").removeClass('hidden');
		$("input[name=area_name]").val('');
		$("h4.widgettitle").html('Add District');
	});

	/*** Create Area ***/
	$("#create_area").click(function(){
		$("#area_id").addClass('hidden');
		$("input[name=area_name]").removeClass('hidden').focus();
		$(this).addClass('hidden');
		$("#sub_area_box").addClass('hidden');
		$("#sub_sub_area_box").addClass('hidden');
		$("#road_box").addClass('hidden');
		$("#sub_area_id").children().eq(0).attr('selected','selected');
		$("#select_area").removeClass('hidden');
		$("h4.widgettitle").html('Add Area');
	});

	/*** Select Area ***/
	$("#select_area").click(function(){
		$("#area_id").removeClass('hidden');
		$("#area_id").val("");
		$("input[name=area_name]").addClass('hidden');
		$(this).addClass('hidden');
		$("#area_id").children().eq(0).attr('selected','selected');
		$("#create_area").removeClass('hidden');
		$("h4.widgettitle").html('Add Location');
	});

	/*** Create sub Area ***/
	$("#create_sub_area").click(function(){
		$("#sub_area_id").addClass('hidden');
		$("input[name=sub_area_name]").removeClass('hidden').focus();
		$(this).addClass('hidden');
		$("#sub_sub_area_box").addClass('hidden');
		$("#road_box").addClass('hidden');
		$("#sub_sub_area_id").children().eq(0).attr('selected','selected');
		$("#select_sub_area").removeClass('hidden');
		$("h4.widgettitle").html('Add Sub Area');
	});

	/*** Select sub Area ***/
	$("#select_sub_area").click(function(){
		$("#sub_area_id").removeClass('hidden');
		$("#sub_area_id").val("");
		$("input[name=sub_area_name]").addClass('hidden');
		$(this).addClass('hidden');
		$("#sub_area_id").children().eq(0).attr('selected','selected');
		$("#create_sub_area").removeClass('hidden');
		$("h4.widgettitle").html('Add Location');
	});

	/*** Create Sub Of Sub Area ***/
	$("#create_sub_sub_area").click(function(){
		$("#sub_sub_area_id").addClass('hidden');
		$("input[name=sub_sub_area_name]").removeClass('hidden').focus();
		$(this).addClass('hidden');
		$("#road_box").addClass('hidden');
		$("#select_sub_sub_area").removeClass('hidden');
		$("h4.widgettitle").html('Add Sub Area of Sub Area');
	});

	$("#select_sub_sub_area").click(function(){
		$("#sub_sub_area_id").removeClass('hidden');
		$("#sub_sub_area_id").val("");
		$("input[name=sub_sub_area_name]").addClass('hidden');
		$(this).addClass('hidden');
		$("#sub_sub_area_id").children().eq(0).attr('selected','selected');
		$("#create_sub_sub_area").removeClass('hidden');
		$("h4.widgettitle").html('Add Location');
	});


	$("#country_id").change(function(){
		var obj = $(this);

		$.ajax({
			url:"<?php echo site_url('location/ajax_get_request/divisions');?>",
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
				$("#district_box").addClass('hidden');
				$("#area_box").addClass('hidden');
				$("#sub_area_box").addClass('hidden');
				$("#sub_sub_area_box").addClass('hidden');
				$("#road_box").addClass('hidden');
				$("#division_box").removeClass('hidden');
				$("#division_id").html(divisionsOption).removeAttr('disabled');
			}
		});
	});

	$("#division_id").change(function(){
		var obj = $(this);
		
		$.ajax({
			url:"<?php echo site_url('location/ajax_get_request/districts');?>",
			method:"POST",
			data: {division_id:obj.val()},
			beforeSend:function(){
				$("#district_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="'+BASE_URL+'public/theme/katniss/img/loading_32.gif"/>');
			},
			success:function(e)
			{
				$("#loader").remove();
				var data = $.parseJSON(e);
				var districtOption = '<option value="">---Select District---</option>';
				$.each(data,function(i,el){
					districtOption += '<option value="'+data[i].id+'">'+data[i].district_name+'</option>';
				});
				$("#area_box").addClass('hidden');
				$("#sub_area_box").addClass('hidden');
				$("#sub_sub_area_box").addClass('hidden');
				$("#road_box").addClass('hidden');
				$("#district_box").removeClass('hidden');
				$("#district_id").html(districtOption).removeAttr('disabled');
			}
		});
	});

	$("#district_id").change(function(){
		var obj = $(this);
		
		$.ajax({
			url:"<?php echo site_url('location/ajax_get_request/areas');?>",
			method:"POST",
			data: {district_id:obj.val()},
			beforeSend:function(){
				$("#area_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="'+BASE_URL+'public/theme/katniss/img/loading_32.gif"/>');
			},
			success:function(e)
			{
				$("#loader").remove();
				var data = $.parseJSON(e);
				var areaOption = '<option value="">---Select Area---</option>';
				$.each(data,function(i,el){
					areaOption += '<option value="'+data[i].id+'">'+data[i].area_name+'</option>';
				});
				$("#sub_area_box").addClass('hidden');
				$("#sub_sub_area_box").addClass('hidden');
				$("#road_box").addClass('hidden');
				$("#area_box").removeClass('hidden');
				$("#area_id").html(areaOption).removeAttr('disabled');
			}
		});
	});

	$("#area_id").change(function(){
		var obj = $(this);
		
		$.ajax({
			url:"<?php echo site_url('location/ajax_get_request/sub_areas');?>",
			method:"POST",
			data: {area_id:obj.val()},
			beforeSend:function(){
				$("#sub_area_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="'+BASE_URL+'public/theme/katniss/img/loading_32.gif"/>');
			},
			success:function(e)
			{
				$("#loader").remove();
				var data = $.parseJSON(e);
				console.log(data.length);
				var subAreaOption = '<option value="">---Select Sub Area---</option>';
				$.each(data,function(i,el){
					subAreaOption += '<option value="'+data[i].id+'">'+data[i].sub_area_name+'</option>';
				});
				$("#sub_sub_area_box").addClass('hidden');
				$("#road_box").addClass('hidden');
				$("#sub_area_box").removeClass('hidden');
				$("#sub_area_id").html(subAreaOption).removeAttr('disabled');
			}
		});
	});


	/*$("#sub_area_id").change(function(){
		var obj = $(this);
		
		$.ajax({
			url:"<?php echo site_url('location/ajax_get_request/sub_sub_areas');?>",
			method:"POST",
			data: {sub_area_id:obj.val()},
			beforeSend:function(){
				$("#sub_area_id").after('<span style="float: right;position: relative;top: -32px;right: -45px;" id="loader"><img src="'+BASE_URL+'public/theme/katniss/img/loading_32.gif"/>');
			},
			success:function(e)
			{
				$("#loader").remove();
				var data = $.parseJSON(e);
				
				var subSubAreaOption = '<option value="">---Select Sub area---</option>';
				$.each(data,function(i,el){
					subSubAreaOption += '<option value="'+data[i].id+'">'+data[i].sub_sub_area_name+'</option>';
				});
				$("#sub_sub_area_box").removeClass('hidden');
				$("#sub_sub_area_id").html(subSubAreaOption).removeAttr('disabled');
			}
		});
	});*/

	$("#sub_area_id").change(function(){
		var obj = $(this);
		$("#road_box").removeClass('hidden');
		$("input[name=road_name]").removeClass('hidden');
	});

	$(".close").click(function(){
		$(this).parent().addClass('hidden');
	});
	

});

</script>
