<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
	a.active{padding:10px;color: #195f91;}
	.col-md-2.selected{margin:0px;padding:0px;width:11%;}
	.widgettitle{height:28px;}
</style>
<div id="container">
	<div class="panel panel-default">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12" style="padding-left:0px;">
						<div class="col-md-2 text-center selected" style="border-right:1px solid lightgray;">
							<h4 class="widgettitle"><a class="active text-primary" href="<?php echo site_url('organization');?>">Basic Info</a></h4>
						</div>
                                                <?php if(!empty($organization)) {?> 
						<div class="col-md-2 text-center" style="border-right:1px solid lightgray;">
							<h4 class="widgettitle"><a class="text-primary" href="<?php echo site_url('organization/default-logo/'.$organization->id); ?>">Default Logo</a></h4>
						</div>
						<div class="col-md-2 text-center">
							<h4 class="widgettitle"><a class="text-primary" href="<?php echo site_url('organization/default-hls/'.$organization->id); ?>">Default URL</a></h4>
						</div>
                                                <?php } ?>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<?php if (empty($organization)) { ?>
			<div class="panel-body">
				<form class="form-horizontal" action="<?php echo site_url('organization/save-organization');?>" method="POST" enctype="multipart/form-data">
					<div class="col-md-8">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_name">Organization Name <span style="color:red">*</span></label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="organization_name" name="organization_name"  required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_phone">Organization Phone <span style="color:red">*</span></label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="organization_phone" name="organization_phone"  required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_email">Organization Email <span style="color:red">*</span></label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="organization_email" name="organization_email"  required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="operator_id">Operator Id</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="operator_id" name="operator_id" readonly />
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="copyright_year">Copyright Year</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="copyright_year" name="copyright_year">
								</div>
							</div>
						</div>
					
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="gift_amount">Initial Amount </label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="gift_amount" name="gift_amount" value="<?php echo (!empty($organization))? $organization->gift_amount : 0; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="administrator1">System Administrator 1 </label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="administrator1" name="administrator1">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="phone1">Administrator 1 Phone</label>						
								<div class="col-sm-4">
									<input type="text" class="form-control" id="phone1" name="phone1">
								</div>
								<div class="col-md-2" style="padding: 0px"><input type="checkbox" name="is_show" value="1"> Show to user </div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="administrator2">System Administrator 2</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="administrator2" name="administrator2">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="phone2">Administrator 2 Phone</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="phone2" name="phone2">
								</div>
							</div>
						</div>
<!--                                                <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label" for="phone2">About Us</label>						
                                                            <div class="col-sm-6">
                                                                <textarea class="form-control" id="about_us" name="about_us"></textarea>
                                                            </div>
                                                        </div>
                                                </div>-->
						<div class="col-md-12">
							
							<div class="form-group">
								<label class="col-sm-4 control-label" for="phone2">Logo</label>
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<span  class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span>
									<span class="fileupload-exists">Change</span><input type="file" name="userfile" /> </span>
									<span class="fileupload-preview"></span>
									<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a> 
								</div>
							</div>
								
						</div>
						<div class="col-md-12">
							
							<div class="form-group">
								<label class="col-sm-4 control-label" for="phone2">Icon</label>
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<span  class="btn btn-default btn-file"><span class="fileupload-new">Select file</span>
									<span class="fileupload-exists">Change</span>         <input type="file" name="iconfile" /> </span> 
									<span class="fileupload-preview"></span>
									<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a> 
								</div>
							</div>
								
						</div>
						<div class="col-md-12">
							<div class="col-md-2 col-md-offset-2">
								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn btn-default"> Submit </button>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</form>
			</div>
			<?php } ?>
			<?php if (!empty($organization)) { ?>
			<div class="panel-body">
				<form class="form-horizontal" id="orgUpdateFrm" action="<?php echo site_url('organization/update-organization/'.$organization->id);?>" method="POST" enctype="multipart/form-data">
					<div class="col-md-8">
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_name">Organization Name <span style="color:red">*</span></label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="organization_name" name="organization_name" value="<?php echo $organization->organization_name; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_phone">Organization Phone <span style="color:red">*</span></label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="organization_phone" name="organization_phone" value="<?php echo $organization->organization_phone; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_email">Organization Suppport Email <span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="email" class="form-control" id="organization_email" name="organization_email" value="<?php echo $organization->organization_email; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_email">Organization Registration Email <span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="email" class="form-control" id="reg_email" name="organization_reg_email" value="<?php echo $settings->reg_email; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_email">Registration Email Password<span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="password" class="form-control" id="reg_email_pass" name="organization_reg_email_pass" value="<?php echo $settings->reg_email_password; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="is_email_send">Is email send <span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="checkbox" id="is_email_send" name="is_email_send" required="required" <?php if($settings->is_email_send){ echo 'checked'; } ?> >
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="is_sms_send">Is sms send <span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="checkbox"  id="is_sms_send" name="is_sms_send" <?php if($settings->is_sms_send){ echo 'checked'; }?> />
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_email">Email Template <span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="email_from_template" name="email_from_template" value="<?php echo $settings->email_from_template; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="organization_email">SMS Confirm Code Template <span style="color:red">*</span></label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="confirm_code_template" name="confirm_code_template" value="<?php echo $settings->confirm_code_template; ?>" required="required">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="operator_id">Operator Id</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="operator_id" name="operator_id" value="<?php echo $organization->operator_id; ?>" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="copyright_year">Copyright Year </label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="copyright_year" name="copyright_year" value="<?php echo $organization->copyright_year; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="gift_amount">Initial Amount</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="gift_amount" name="gift_amount" value="<?php echo $organization->gift_amount; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="administrator1">System Administrator 1</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" id="administrator1" name="administrator1" value="<?php echo $organization->administrator1; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="phone1"> Administrator 1 Phone</label>						
								<div class="col-sm-4">
									<input type="text" class="form-control" maxlength="11" id="phone1" name="phone1" value="<?php echo $organization->phone1; ?>">
								</div>
								<div class="col-md-2" style="padding: 0px">
								<input <?php if($organization->is_show == 1) echo 'checked'; ?> type="checkbox"  name="is_show" value="1"> Show to user
								 </div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="administrator2">System Administrator 2</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control"  id="administrator2" name="administrator2" value="<?php echo $organization->administrator2; ?>">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="phone2">Administrator 2 Phone</label>						
								<div class="col-sm-6">
									<input type="text" class="form-control" maxlength="11" id="phone2" name="phone2" value="<?php echo $organization->phone2; ?>">
								</div>
							</div>
						</div>
<!--                                                <div class="col-md-12">
                                                        <div class="form-group">
                                                                <label class="col-sm-4 control-label" for="phone2">About Us</label>						
                                                                <div class="col-sm-6">
                                                                    <textarea  class="form-control" id="about_us" name="about_us"><?php echo $organization->about_us; ?></textarea>
                                                                </div>
                                                        </div>
                                                </div>-->
					</div>
					<style type="text/css">
						.thumbnail{height:55px;width:230px; }
						.thumbnail img{height:45px;width:230px; border: 1px solid black;}
						.thumbnail_icon img{height:16px;width:16px; border: 1px solid black;}
					</style>
					<div class="col-md-4">
						<div class="col-md-9">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" for="copyright_image">Logo</label>						
									<!-- <div class="input-group"> -->
										<div class="thumbnail">
											<?php if ($organization->logo) { ?>
																
											<?php echo '<img src="'.base_url($organization->logo).'" />'; ?>
										
											<?php } else { ?>

											<img src="<?php echo base_url(); ?>public/uploads/organization/temp.png">

											<?php } ?>
										</div>
									<!-- </div> -->
									<p class="col-md-offset-2">height : 45px <br/> width : 230px<br/> Max Image Size : 1MB <br/>Format : PNG </p>
								</div>
							</div>
							<?php if(!empty($permissions) && $permissions->edit_permission == 1){ ?>
							<div class="col-md-12 col-md-offset-3">
							<div class="form-group">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<span class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span>
									<span class="fileupload-exists">Change</span><input type="file" name="userfile" /> </span> 
									<span class="fileupload-preview"></span>
									<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>  
								</div>
							</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-9">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label" for="copyright_image">Icon</label>						
									<!-- <div class="input-group"> -->
										<div class="thumbnail_icon col-md-offset-4">
											<?php if ($organization->icon) { ?>
																
											<?php echo '<img src="' . base_url($organization->icon)  . '" />'; ?>
										
											<?php } else { ?>

											<img src="<?php echo base_url(); ?>public/uploads/organization/temp.png">

											<?php } ?>
										</div>
									<!-- </div> -->
									<p class="col-md-offset-2">height : 16px <br/> width : 16px<br/> Max Image Size : 15kb <br/>Format : PNG </p>
								</div>
							</div>
							<?php if(!empty($permissions) && $permissions->edit_permission == 1){ ?>
							<div class="col-md-12 col-md-offset-3">
							<div class="form-group">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<span class="btn btn-primary btn-file"><span class="fileupload-new">Select file</span>
									<span class="fileupload-exists">Change</span><input type="file" name="iconfile" /> </span> 
									<span class="fileupload-preview"></span>
									<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>  
								</div>
							</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php if(!empty($permissions) && $permissions->edit_permission == 1){ ?>
					<div class="col-md-11">
						<div class="col-md-3 col-md-offset-3">
							<div class="form-group">
								<button type="submit" class="btn btn-default">Upadate </button>
							</div>
						</div>
					</div>
					<?php } ?>
				</form>	
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.clearfix{*zoom:1;}.clearfix:before,.clearfix:after{display:table;content:"";line-height:0;}
	.clearfix:after{clear:both;}
	.hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0;}
	.input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
	.btn-file{overflow:hidden;position:relative;vertical-align:middle;}.btn-file>input{position:absolute;top:0;right:0;margin:0;opacity:0;filter:alpha(opacity=0);transform:translate(-300px, 0) scale(4);font-size:23px;direction:ltr;cursor:pointer;}
	.fileupload{margin-bottom:9px;}.fileupload .uneditable-input{display:inline-block;margin-bottom:0px;vertical-align:middle;cursor:text;}
	.fileupload .thumbnail{overflow:hidden;display:inline-block;margin-bottom:5px;vertical-align:middle;text-align:center;}.fileupload .thumbnail>img{display:inline-block;vertical-align:middle;max-height:100%;}
	.fileupload .btn{vertical-align:middle;}
	.fileupload-exists .fileupload-new,.fileupload-new .fileupload-exists{display:none;}
	.fileupload-inline .fileupload-controls{display:inline;}
	.fileupload-new .input-append .btn-file{-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;}
	.thumbnail-borderless .thumbnail{border:none;padding:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;}
	.fileupload-new.thumbnail-borderless .thumbnail{border:1px solid #ddd;}
	.control-group.warning .fileupload .uneditable-input{color:#a47e3c;border-color:#a47e3c;}
	.control-group.warning .fileupload .fileupload-preview{color:#a47e3c;}
	.control-group.warning .fileupload .thumbnail{border-color:#a47e3c;}
	.control-group.error .fileupload .uneditable-input{color:#b94a48;border-color:#b94a48;}
	.control-group.error .fileupload .fileupload-preview{color:#b94a48;}
	.control-group.error .fileupload .thumbnail{border-color:#b94a48;}
	.control-group.success .fileupload .uneditable-input{color:#468847;border-color:#468847;}
	.control-group.success .fileupload .fileupload-preview{color:#468847;}
	.control-group.success .fileupload .thumbnail{border-color:#468847;}
</style>

<script type="text/javascript">
	<?php if(!empty($permissions) && $permissions->edit_permission != 1){ ?>
		$("input").prop('disabled','disabled');
	<?php } ?>
	!function(e){var t=function(t,n){this.$element=e(t),this.type=this.$element.data("uploadtype")||(this.$element.find(".thumbnail").length>0?"image":"file"),this.$input=this.$element.find(":file");if(this.$input.length===0)return;this.name=this.$input.attr("name")||n.name,this.$hidden=this.$element.find('input[type=hidden][name="'+this.name+'"]'),this.$hidden.length===0&&(this.$hidden=e('<input type="hidden" />'),this.$element.prepend(this.$hidden)),this.$preview=this.$element.find(".fileupload-preview");var r=this.$preview.css("height");this.$preview.css("display")!="inline"&&r!="0px"&&r!="none"&&this.$preview.css("line-height",r),this.original={exists:this.$element.hasClass("fileupload-exists"),preview:this.$preview.html(),hiddenVal:this.$hidden.val()},this.$remove=this.$element.find('[data-dismiss="fileupload"]'),this.$element.find('[data-trigger="fileupload"]').on("click.fileupload",e.proxy(this.trigger,this)),this.listen()};t.prototype={listen:function(){this.$input.on("change.fileupload",e.proxy(this.change,this)),e(this.$input[0].form).on("reset.fileupload",e.proxy(this.reset,this)),this.$remove&&this.$remove.on("click.fileupload",e.proxy(this.clear,this))},change:function(e,t){if(t==="clear")return;var n=e.target.files!==undefined?e.target.files[0]:e.target.value?{name:e.target.value.replace(/^.+\\/,"")}:null;if(!n){this.clear();return}this.$hidden.val(""),this.$hidden.attr("name",""),this.$input.attr("name",this.name);if(this.type==="image"&&this.$preview.length>0&&(typeof n.type!="undefined"?n.type.match("image.*"):n.name.match(/\.(gif|png|jpe?g)$/i))&&typeof FileReader!="undefined"){var r=new FileReader,i=this.$preview,s=this.$element;r.onload=function(e){i.html('<img src="'+e.target.result+'" '+(i.css("max-height")!="none"?'style="max-height: '+i.css("max-height")+';"':"")+" />"),s.addClass("fileupload-exists").removeClass("fileupload-new")},r.readAsDataURL(n)}else this.$preview.text(n.name),this.$element.addClass("fileupload-exists").removeClass("fileupload-new")},clear:function(e){this.$hidden.val(""),this.$hidden.attr("name",this.name),this.$input.attr("name","");if(navigator.userAgent.match(/msie/i)){var t=this.$input.clone(!0);this.$input.after(t),this.$input.remove(),this.$input=t}else this.$input.val("");this.$preview.html(""),this.$element.addClass("fileupload-new").removeClass("fileupload-exists"),e&&(this.$input.trigger("change",["clear"]),e.preventDefault())},reset:function(e){this.clear(),this.$hidden.val(this.original.hiddenVal),this.$preview.html(this.original.preview),this.original.exists?this.$element.addClass("fileupload-exists").removeClass("fileupload-new"):this.$element.addClass("fileupload-new").removeClass("fileupload-exists")},trigger:function(e){this.$input.trigger("click"),e.preventDefault()}},e.fn.fileupload=function(n){return this.each(function(){var r=e(this),i=r.data("fileupload");i||r.data("fileupload",i=new t(this,n)),typeof n=="string"&&i[n]()})},e.fn.fileupload.Constructor=t,e(document).on("click.fileupload.data-api",'[data-provides="fileupload"]',function(t){var n=e(this);if(n.data("fileupload"))return;n.fileupload(n.data());var r=e(t.target).closest('[data-dismiss="fileupload"],[data-trigger="fileupload"]');r.length>0&&(r.trigger("click.fileupload"),t.preventDefault())})}(window.jQuery)

	$("#orgUpdateFrm").submit(function(){
		var phoneNo = $("#organization_phone");
		var initAmount = $("#gift_amount");
		var regex = /^\d+$/;
		if(!regex.test(phoneNo.val())){
			phoneNo.after('<span class="warning text-danger">Phone Number not valid</span>');
			$("html,body").animate({scrollTop:'0px'});
			return false;

		}else{
			phoneNo.next('.warning').remove();
		}

		if(!regex.test(initAmount.val())){
			initAmount.after('<span class="init-amount-warning text-danger">Initial amount should be number</span>');
			$("html,body").animate({scrollTop:'0px'});
			return false;
		}else{
			initAmount.next('.init-amount-warning').remove();
		}

	});
</script>