<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/md5.js'); ?>"></script>
<script type="text/javascript">
	tinymce.init({
		selector:'textarea',
		fontsize_formats:'8pt 10pt 12pt 14pt 16pt 18pt',
		toolbar: 'bold italic font_size forecolor fontsizeselect',
		plugins: "textcolor colorpicker",

		menubar:false
	});
	var serviceOperators = '<?php echo json_encode($service_operators); ?>';
</script>
<div id="container" ng-controller="IptvProgram" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{warning_messages}}
	</div>

	<div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{success_messages}}
	</div>

	<div class="panel panel-default" ng-show="addFormFlag">
		<div class="row" >
			<!-- <div class="col-md-12">
				<div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
			</div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">Add New Content
                                                    
							<a ng-click="hideForm();" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-close"></i> Close </a>
                                                    
                                                </h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<form class="form-horizontal" name="saveIptvProgram" ng-submit="saveIpTvProgram()" enctype="multipart/form-data">
						<div class="form-group">
							<label class="control-label col-md-3">Content Name <span class="text-danger">*</span></label>
							<div class="col-md-4">
								<input type="text" class="form-control" ng-model="formData.program_name" requried="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Content Directory <span class="text-danger">*</span></label>
							<div class="col-md-4">
								<input type="text" class="form-control" maxlength="20" ng-model="formData.content_dir" requried="required"/>
							</div>
						</div>
						<!--<div class="form-group">
							<label class="control-label col-md-3">LCN</label>
							<div class="col-md-2">
								<input type="text" class="form-control" ng-model="formData.lcn"/>
							</div>
						</div>-->
						<!--<div class="form-group">
							<label class="control-label col-md-3">Type <span class="text-danger">*</span></label>
							<div class="col-md-3">
								<select class="form-control" ng-model="formData.type" ng-change="showDuration()" required="required">
									<option ng-repeat="i in types" value="{{i.type}}">{{i.type}}</option>
								</select>
							</div>
						</div>
						<div class="form-group" ng-if="showDurationFlag==true">
							<label class="control-label col-md-3">Duration <span class="text-danger">*</span></label>
							<div class="col-md-3">
								<input type="number" class="form-control" ng-model="formData.duration" required="required"/>
							</div>
						</div>-->
						<div class="form-group">
							<label class="control-label col-md-3">Description</label>
							<div class="col-md-7">
								<textarea class="form-control" style="resize:none;" rows="5" ng-model="formData.description" ></textarea>
							</div>
						</div>
						<!--<div class="form-group">
							<label class="control-label col-md-3">Logo URL</label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.logo_url"/>
							</div>
						</div>-->

						<!--<div class="form-group">
							<label class="control-label col-md-3">Poster URL <small>(Mobile)</small></label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.poster_url_mobile"/>
							</div>
						</div>-->
						<!--<div class="form-group">
							<label class="control-label col-md-3">Poster URL <small>(STB)</small></label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.poster_url_stb"/>
							</div>
						</div>-->
						<!--<div class="form-group">
							<label class="control-label col-md-3">Watermark URL</label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.watermark_url"/>
							</div>
						</div>-->
						<!--<div class="form-group">

							<label class="control-label col-md-3">Logo URL <small>(STB/Web)</small></label>

							<div class="col-md-4">
								<div ng-hide="messageState">
									<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderLogoUrl" nv-file-select="">
												</a>
											  </span>
									</div>

								</div>
								<span><small><em>(Max filesize 1M and supported formats png)</em></small></span>
							</div>

						</div>
						<div class="form-group">

							<label class="control-label col-md-3">Logo URL <small>(Mobile)</small></label>

							<div class="col-md-4">
								<div ng-hide="messageState">
									<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderLogoMobileUrl" nv-file-select="">
												</a>
											  </span>
									</div>

								</div>
								<span><small><em>(Max filesize 1M and supported formats png)</em></small></span>
							</div>

						</div>
						<div class="form-group">

							<label class="control-label col-md-3">Poster URL <small>(Mobile)</small></label>

							<div class="col-md-4">
								<div ng-hide="messageState">
									<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderPosterMobile" nv-file-select="">
												</a>
											  </span>
									</div>

								</div>
								<span><small><em>(Max filesize 1M and supported formats png)</em></small></span>
							</div>

						</div>
						<div class="form-group">

							<label class="control-label col-md-3">Poster URL <small>(STB)</small></label>

							<div class="col-md-4">
								<div ng-hide="messageState">
									<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderPosterSTB" nv-file-select="">
												</a>
											  </span>
									</div>

								</div>
								<span><small><em>(Max filesize 1M and supported formats png)</em></small></span>
							</div>

						</div>-->
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Image Quality</label>
                                                    <div class="col-md-3">
                                                        <select class="form-control" ng-init="formData.image_quality='0-75'" ng-model="formData.image_quality">
                                                            <?php if(!empty($image_qualities)){ ?>
                                                            <?php foreach($image_qualities as $iq){ ?>
                                                            <option value="<?php echo $iq->quality; ?>"><?php echo $iq->quality; ?></option>
                                                            <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
						<div class="form-group">
							<label class="control-label col-md-3">Thumbnail & Poster</label>
							<div class="col-md-4">
								<div ng-hide="messageState">
									<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderImage" nv-file-select="">
												</a>
											  </span>
									</div>
								</div>
								<span><small><em>(Max filesize 1M and formats png, minimum  <?php echo $image_sizes['minimum']['width'].'x'.$image_sizes['minimum']['height']; ?> pixel )</em></small></span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="allImageFormat" id="setAllImageFormat" ng-change="setAllImageFormat()"/> <label for="setAllImageFormat" class="control-label ">SELECT ALL FORMAT</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-3">
								<a class="btn btn-info btn-xs" ng-if="showAdv==0" ng-click="showAdvOptions()">Show Advance</a>
								<a class="btn btn-info btn-xs"  ng-if="showAdv==1" ng-click="hideAdvOptions()">Hide Advance</a>
							</div>
						</div>
						<div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.web_logo" ng-checked="formData.web_logo==1" ng-true-value="1" ng-false-value="0" id="web_logo"/> <label for="web_logo">Web Thumbnail ( <?php echo $image_sizes['advance']['WEB_LOGO']['width'].'x'.$image_sizes['advance']['WEB_LOGO']['height']; ?> pixel)</label>
							</div>
						</div>

						<div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.stb_logo" ng-checked="formData.stb_logo==1" ng-true-value="1" ng-false-value="0" id="stb_logo"/> <label for="stb_logo">STB Thumbnail ( <?php echo $image_sizes['advance']['STB_LOGO']['width'].'x'.$image_sizes['advance']['STB_LOGO']['height']; ?> pixel)</label>
							</div>
						</div>
						<div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.mobile_logo" ng-checked="formData.mobile_logo==1" ng-true-value="1" ng-false-value="0" id="mobile_logo"/> <label for="mobile_logo">Mobile Thumbnail ( <?php echo $image_sizes['advance']['MOBILE_LOGO']['width'].'x'.$image_sizes['advance']['MOBILE_LOGO']['height']; ?> pixel)</label>
							</div>
						</div>

						<div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.mobile_poster" ng-checked="formData.mobile_poster==1" ng-true-value="1" ng-false-value="0" id="mobile_poster"/> <label for="mobile_poster">Mobile Poster ( <?php echo $image_sizes['advance']['MOBILE_POSTER']['width'].'x'.$image_sizes['advance']['MOBILE_POSTER']['height']; ?> pixel)</label>
							</div>
						</div>
						<div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.web_poster" ng-checked="formData.web_poster==1" ng-true-value="1" ng-false-value="0" id="web_poster"/> <label for="web_poster">Web Poster ( <?php echo $image_sizes['advance']['WEB_POSTER']['width'].'x'.$image_sizes['advance']['WEB_POSTER']['height']; ?> pixel)</label>
							</div>
						</div>
						<div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.stb_poster" ng-checked="formData.stb_poster==1" ng-true-value="1" ng-false-value="0" id="stb_poster"/> <label for="stb_poster">STB Poster ( <?php echo $image_sizes['advance']['STB_POSTER']['width'].'x'.$image_sizes['advance']['STB_POSTER']['height']; ?> pixel)</label>
							</div>
						</div>
                                                <div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.web_player_poster" ng-checked="formData.web_player_poster==1" ng-true-value="1" ng-false-value="0" id="web_player_poster"/> <label for="web_player_poster">Web Player Poster ( size [ <?php echo $image_sizes['advance']['WEB_PLAYER_POSTER']['width'].'x'.$image_sizes['advance']['WEB_PLAYER_POSTER']['height']; ?> ] pixel )</label>
							</div>
						</div>
                                                <div class="form-group" ng-if="showAdv==1">
							<div class="col-md-6 col-md-offset-3">
								<input type="checkbox" ng-model="formData.mobile_player_poster" ng-checked="formData.mobile_player_poster==1" ng-true-value="1" ng-false-value="0" id="mobile_player_poster"/> <label for="mobile_player_poster">Mobile Player Poster ( size [ <?php echo $image_sizes['advance']['MOBILE_PLAYER_POSTER']['width'].'x'.$image_sizes['advance']['MOBILE_PLAYER_POSTER']['height']; ?> ] pixel )</label>
							</div>
						</div>
						<div class="form-group">

								<label class="control-label col-md-3">Water mark</label>

								<div class="col-md-4">
									<div ng-hide="messageState">
										<div class="input-file">

											<span class="input-group-addon">
												<a href="javascript:;" class="btn btn-primary">
													Browse
													<input type="file" style="height:34px;" onchange="$(this).parent().parent().parent().find('.form-control').html($(this).val());" accept="application/json" uploader="UploaderWatermark" nv-file-select="">
												</a>
											  </span>
										</div>

									</div>
									<span><small><em>(Max filesize 1M and supported formats png, size[<?php echo $image_sizes['watermark']['width'].'x'.$image_sizes['watermark']['height']; ?>] )</em></small></span>
								</div>

						</div>
						<!--<div class="form-group">
							<label class="control-label col-md-3">HLS URL <small>(Mobile)</small><span class="text-danger">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.hls_url_mobile" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">HLS URL <small>(STB)</small><span class="text-danger">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.hls_url_stb" required="required"/>
							</div>
						</div>-->
						<div class="form-group">
							<label class="control-label col-md-3">Video Trailer URL </label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.video_trailer_url" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Video Share URL <span class="text-danger">*</span></label>
							<div class="col-md-6">
								<div class="input-group">
									<spant class="input-group-addon" ng-init="shared_url='<?php echo $settings->default_share_url; ?>vod/'">{{shared_url}}</spant>
									<input type="text" class="form-control" readonly ng-model="formData.video_share_url" required="required"/>
								</div>
							</div>
						</div>
                                                <?php if(!empty($lsps)){ ?>
                                                <div class="form-group">
                                                   
                                                    <label class="control-label col-md-3"> Select LSP <span class="text-danger">*</span></label>
                                                    <div class="col-md-3">
                                                        <input list="browsers" name="browser" ng-model="formData.parent_id">
                                                        <datalist id="browsers">
                                                            <option value="MSO"/>
                                                            <?php foreach($lsps as $lsp){ ?>
                                                            <option value="<?php echo $lsp->username; ?>"><?php echo $lsp->lco_name; ?></option>
                                                            <?php } ?>
                                                        
                                                        </datalist>
                                                    </div>
                                                    
                                                </div>
                                                <?php } ?>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox" ng-model="formData.is_active" ng-checked="formData.is_active" ng-true-value="1" ng-false-value="0"/>
										<strong>Is Active</strong>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox" ng-model="formData.is_available" ng-checked="formData.is_available" ng-true-value="1" ng-false-value="0"/>
										<strong>Is Available</strong>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox" ng-model="formData.age_restriction" ng-checked="formData.age_restriction" ng-true-value="1" ng-false-value="0"/>
										<strong>Age Restriction</strong>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Individual Price <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<input type="number" class="form-control" ng-model="formData.individual_price" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Duration <span class="text-danger">*</span></label>
							<div class="col-md-1" style="padding-right:0px;width:58px;">
                                                            <input type="number" class="form-control duration duration_h" placeholder="00"  required="required"/>                                                                               
                                                        </div>
                                                        <div class="col-md-1" style="padding:0px;text-align:center;width:20px;"> : </div>
                                                        <div class="col-md-1" style="padding-left:0px;padding-right:0px;width:40px;">
                                                            <input type="number" class="form-control duration duration_m" placeholder="00" required="required"/>
                                                        </div>
                                                        <div class="col-md-1" style="padding:0px;text-align:center;width:20px;"> : </div>
                                                        <div class="col-md-1" style="padding-left:0px;padding-right:0px;width:40px;">
                                                            <input type="number" class="form-control duration duration_s" placeholder="00"   required="required"/>
                                                        </div>
                                                        <script>
                                                            
                                                            $("input.duration").change(function(){
                                                               var obj = $(this);
                                                               var value = parseInt(obj.val());
                                                               if(value < 10){
                                                                   obj.val(("0"+value));
                                                               }else if(value <= 0){
                                                                   obj.val("00");
                                                               }
                                                            });
                                                        </script>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Video Tags </label>
							<div class="col-md-2">
								<input type="text" class="form-control" ng-model="formData.video_tags" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Recording Date </label>
							<div class="col-md-2">
								<input type="text" class="form-control" kendo-datepicker  k-format="'yyyy-MM-dd'" ng-model="formData.recording_date" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Content Language <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<select class="form-control" ng-model="formData.video_language" required="required">
									<option value="">Select Language</option>
									<?php if(!empty($languages)){ ?>
										<?php foreach($languages as  $language){ ?>
											<option value="<?php echo $language->lang_name; ?>"><?php echo $language->lang_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Category <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<select class="form-control" ng-model="formData.category_id" ng-change="loadSubCategories()">
									<option value="">Select Category</option>
									<?php if(!empty($categories)){ ?>
										<?php foreach($categories as  $category){ ?>
											<option value="<?php echo $category->id; ?>"><?php echo $category->category_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Sub Category <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<select class="form-control" ng-model="formData.sub_category_id">
									<option value="">Select Sub Category</option>
									<option ng-repeat="subc in sub_categories" value="{{subc.id}}">{{subc.sub_category_name}}</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Keywords <span class="text-danger">*</span></label>
							<div class="col-md-6">
								<input type="text" class="form-control" ng-model="formData.keywords" required="required"/>
								<span><small>(Use comma (,) as separator between values)</small></span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Content Provider</label>
							<div class="col-md-3">
								<select class="form-control" ng-model="formData.content_provider_id">
									<option value="">Select Content Provider</option>
									<?php if(!empty($content_providers)){ ?>
										<?php foreach($content_providers as $content_provider){ ?>
											<option value="<?php echo $content_provider->id; ?>"><?php echo $content_provider->company_name; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Content Aggregator Type</label>
							<div class="col-md-3">
								<select class="form-control" ng-model="formData.content_aggregator_type_id">
									<option value="">Select Aggregator Type</option>
									<?php if(!empty($content_aggregator_types)){ ?>
										<?php foreach($content_aggregator_types as  $content_aggregator_type){ ?>
											<option value="<?php echo $content_aggregator_type->id; ?>"><?php echo $content_aggregator_type->content_aggregator_type; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
						  	<label class="control-label col-md-3">Content Provider Category</label>
						    <div class="col-md-2">
								<select class="form-control" id="sel1" ng-model="formData.content_provider_category_id">
									<option value="">Select</option>
									<?php
										foreach($content_provider_categories as $cpc){
									?>
										<option value="<?php echo $cpc->id; ?>"><?php echo $cpc->title; ?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="form-group">
                            <label class="control-label col-md-3">Service Operator</label>
                            <div class="col-md-3"> 
                            <ul id="service_operator">
								<li><input ng-model="selectAllServiceOperator" ng-change="toggleSelectAllServiceOperator()" type="checkbox" ng-true-value="1" ng-false-value="0"/> Select All</li>
	                            <?php 
	                            	foreach ($service_operators as $key => $value) {
	                            ?>
	                            	<li>
	                            		<label class="checkbox-inline">
	                            			<input class="service-opeartor" ng-model="formData.service_operator_id[<?php echo $key; ?>]" ng-true-value="<?php echo $value->id; ?>" ng-false-value="0" type="checkbox"><?php echo $value->telco_name;?>
	                            		</label>
	                            	</li>
	                           	<?php } ?>
                            </ul>
	                            
                            </div>
                        </div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
							<input type="submit" class="btn btn-success" ng-disabled="saveIptvProgram.$invalid" value="Save Program"/>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default" ng-if="!addFormFlag">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">VoD Contents
                                                    <?php if($user_info->lsp_type_id != 2){ ?>
							<a ng-click="showForm()" id="buttoncancel" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Add Content </a>
                                                    <?php } ?>
                                                </h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12" ng-if="!delete_item && imageProcessing==0">
					<div kendo-grid id="grid" options="mainGridOptions"></div>
				</div>
				<div class="col-md-12 text-center" ng-if="delete_item">
					<form>
						<p><strong>Are you sure to delete this content</strong></p>
						<div class="form-group">
							<label class="control-label col-md-5 text-right">Password</label>
							<div class="col-md-3">
								<input type="password" class="form-control" ng-model="verifyData.password"/>
							</div>
						</div>
						<div class="form-group" style="margin-top:50px;">
							<div class="col-md-5 col-md-offset-5 text-left">
								<input type="submit" ng-click="confirm_delete()" class="btn btn-danger" value="Yes"/>
								<input type="button" ng-click="cancel_delete()" class="btn btn-warning" value="No"/>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-12 text-center" ng-if="imageProcessing==1">
					<h3 class="text-danger">Please Wait Image processing</h3>
					<br/>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	#service_operator li{list-style: none;}
	#service_operator {margin-left: -35px;}
</style>