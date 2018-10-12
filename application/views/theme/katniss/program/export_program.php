<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container">
	
	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
        <button class="close" ng-click="closeAlert()">×</button>
        {{warning_messages}}
    </div>
	<div class="panel panel-default">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Export Programs
							<a class="btn btn-primary btn-sm pull-right" href="<?php echo site_url('program'); ?>"><i class="fa fa-arrow-left"></i> Back</a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<!-- Form Left Part -->
			<div class="panel-body">
				<form class="form-horizontal" action="<?php echo site_url('program/dump_to_xlsx');?>" method="post">
					<div class="col-md-12">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3"><input type="checkbox" id="selectAll"/> Select All</label>
							</div>
							<hr/>
							<?php if(!empty($fields)){ ?>
							<div class="form-group">
								<?php foreach($fields as $key=>$value){ ?>
									<label class="col-md-3"><input class="fields" <?php if(in_array($key,array('program_name','lcn','program_service_id'))){ echo 'checked="checked"'; } ?> type="checkbox" name="field[]" value="<?php echo $key; ?>"/> <?php echo $value; ?></label>
									
								<?php } ?>
							</div>
							<?php } ?>
							<div class="form-group">
								<div class="col-md-3">
									<button class="btn btn-success" type="submit">Export</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$("#selectAll").change(function(){
		var obj = $(this);

		if(obj.prop('checked')){
			
			$("input.fields").prop('checked','checked');
		} else {
			
			$("input.fields").removeAttr('checked');
		}
	});
});
</script>