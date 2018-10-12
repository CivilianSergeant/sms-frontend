<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
	var programId = "<?php echo $programId; ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
	tinymce.init({
		selector:'textarea',
		toolbar: false,
		menubar:false
	});
</script>
<style>
	.thumbnails{
		width:100px;height:70px;margin:0px 70px;
	}
	.blank-img{
		width:100px;margin:0px 70px;height:57px;
	}
</style>
<div id="container" ng-controller="SerialProgram" ng-cloak>

	<div class="alert alert-warning" ng-show="warning_messages" ng-model="warning_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{warning_messages}}
	</div>

	<div class="alert alert-success" ng-show="success_messages" ng-model="success_messages">
		<button class="close" ng-click="closeAlert()">×</button>
		{{success_messages}}
	</div>

	<div class="panel panel-default">
		<div class="row" >
			<!-- <div class="col-md-12">
				<div class="col-lg-12"><h3 class="widgettitle">Package</h3></div>
			</div> -->
			<div class="col-md-12">
				<div class="panel-heading">
					<div class="col-md-12">
						<h4 class="widgettitle">View Content [{{formData.program_name}}]
							<a href="<?php echo site_url('serial-contents'); ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
							<a href="<?php echo site_url('serial-contents/edit/'.$programId); ?>" id="buttoncancel" class="btn btn-success btn-sm pull-right" style="margin-right:10px;"><i class="fa fa-pencil"></i> Edit </a>
						</h4>
					</div>
					<span class="clearfix"></span>
				</div>
				<hr/>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<form class="form-horizontal" ng-submit="updateIpTvProgram()">
						<div class="form-group">
							<label class="control-label col-md-3">Content Name</label>
							<div class="col-md-4">
								<input type="text" class="form-control" readonly ng-model="formData.program_name"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Content Directory</label>
							<div class="col-md-4">
								<input type="text" class="form-control" readonly maxlength="20" ng-model="formData.content_dir" requried="required"/>
							</div>
						</div>

						<!--<div class="form-group">
							<label class="control-label col-md-3">LCN</label>
							<div class="col-md-2">
								<input type="text" readonly class="form-control" ng-model="formData.lcn"/>
							</div>
						</div>-->
						<!--<div class="form-group">
							<label class="control-label col-md-3">Type </label>
							<div class="col-md-3">
								<select class="form-control" disabled ng-model="formData.type">
									<option ng-repeat="i in types" ng-selected="formData.type==i.type" value="{{i.type}}">{{i.type}}</option>
								</select>
							</div>
						</div>-->
						<div class="form-group">
							<label class="control-label col-md-3">Description</label>
							<div class="col-md-7">
								<textarea type="text"  readonly class="form-control" style="height:auto;" ng-model="formData.description"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Web Thumbnail </label>
							<?php if(!empty($program['logo_web_url']) && file_exists($program['logo_web_url'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.logo_web_url}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['logo_web_url']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['logo_web_url'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else { ?>
								<div class="col-md-3">
									<img id="webThumbnail" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr/>
						<div class="form-group">
							<label class="control-label col-md-3">STB Thumbnail </label>
							<?php if(!empty($program['logo_stb_url']) && file_exists($program['logo_stb_url'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.logo_stb_url}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['logo_stb_url']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['logo_stb_url'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else { ?>
								<div class="col-md-3">
									<img id="stbThumbnail" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr/>
						<div class="form-group">
							<label class="control-label col-md-3">Mobile Thumbnail </label>
							<?php if(!empty($program['logo_mobile_url']) && file_exists($program['logo_mobile_url'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.logo_mobile_url}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['logo_mobile_url']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['logo_mobile_url'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="mobileThumbnail" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr/>
						<div class="form-group">
							<label class="control-label col-md-3">Mobile Poster </label>
							<?php if(!empty($program['poster_url_mobile']) && file_exists($program['poster_url_mobile'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.poster_url_mobile}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['poster_url_mobile']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['poster_url_mobile'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="mobilePoster" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">WEB Poster</label>
							<?php if(!empty($program['poster_url_web']) && file_exists($program['poster_url_web'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.poster_url_web}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['poster_url_web']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['poster_url_web'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="mobilePoster" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">STB Poster</label>
							<?php if(!empty($program['poster_url_stb']) && file_exists($program['poster_url_stb'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.poster_url_stb}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['poster_url_stb']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['poster_url_stb'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="mobilePoster" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr>
                                                <div class="form-group">
							<label class="control-label col-md-3">Web Player Poster</label>
							<?php if(!empty($program['player_poster_web']) && file_exists($program['player_poster_web'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.player_poster_web}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['player_poster_web']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['player_poster_web'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="webPlayerPoster" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr>
                                                <div class="form-group">
							<label class="control-label col-md-3">Mobile Player Poster</label>
							<?php if(!empty($program['player_poster_mobile']) && file_exists($program['player_poster_mobile'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.player_poster_mobile}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['player_poster_mobile']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['player_poster_mobile'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="mobilePlayerPoster" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Watermark URL</label>
							<?php if(!empty($program['water_mark_url']) && file_exists($program['water_mark_url'])){ ?>
								<div class="col-md-8">
									<img class="thumbnails" ng-src="<?php echo base_url(); ?>{{formData.water_mark_url}}"/>
									<span><?php
										list($width, $height, $type, $attr) = getimagesize($program['logo_mobile_url']);
										echo $width.'x'.$height.' Pixel, Size: '.number_format(filesize($program['logo_mobile_url'])/1024,2) .' KB';
										?>
									</span>
								</div>
							<?php }else{ ?>
								<div class="col-md-3">
									<img id="watermark" class="blank-img" ng-src="<?php echo base_url(); ?>public/theme/katniss/img/no-image-found.png"/>
								</div>
							<?php } ?>
						</div>
						<hr>
						<!--<div class="form-group">
							<label class="control-label col-md-3">HLS URL <small>(Mobile)</small></label>
							<div class="col-md-6">
								<input type="text" readonly class="form-control" ng-model="formData.hls_url_mobile"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">HLS URL <small>(STB)</small></label>
							<div class="col-md-6">
								<input type="text" readonly class="form-control" ng-model="formData.hls_url_stb"/>
							</div>
						</div>-->
						<div class="form-group">
							<label class="control-label col-md-3">Video Trailer URL <span class="text-danger">*</span></label>
							<div class="col-md-6">
								<input type="text" readonly class="form-control" ng-model="formData.video_trailer_url" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Video Share URL </label>
							<div class="col-md-6">
								<div class="input-group">
									<spant class="input-group-addon">{{shared_url}}</spant>
									<input type="text" class="form-control" readonly ng-model="formData.video_share_url" required="required"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox" disabled ng-model="formData.is_active" ng-checked="formData.is_active=='1'" ng-true-value="'1'" ng-false-value="'0'"/>
										<strong>Is Active</strong>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox" disabled ng-model="formData.is_available" ng-checked="formData.is_available=='1'" ng-true-value="'1'" ng-false-value="'0'"/>
										<strong>Is Available</strong>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<div class="checkbox">
									<label>
										<input type="checkbox" disabled ng-model="formData.age_restriction" ng-checked="formData.age_restriction" ng-true-value="'1'" ng-false-value="'0'"/>
										<strong>Age Restriction</strong>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Individual Price </label>
							<div class="col-md-2">
								<input type="number" readonly class="form-control" string-to-number ng-model="formData.individual_price"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Duration <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<input type="text" readonly class="form-control" ng-model="formData.duration" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Video Tags <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<input type="text" readonly class="form-control" ng-model="formData.video_tags" required="required"/>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3">Recording Date <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<input type="text" readonly class="form-control" kendo-datepicker  k-format="'yyyy-MM-dd'" ng-model="formData.recording_date" required="required"/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Video Language <span class="text-danger">*</span></label>
							<div class="col-md-2">
								<select class="form-control" readonly ng-model="formData.video_language">
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
							<label class="control-label col-md-3">Keywords <span class="text-danger">*</span></label>
							<div class="col-md-6">
								<input type="text" readonly class="form-control" ng-model="formData.keywords" required="required"/>
								<span><small>(Use comma (,) as separator between values)</small></span>
							</div>

						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Content Provider</label>
							<div class="col-md-3">
								<select class="form-control" disabled ng-model="formData.content_provider_id">
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
							<div class="col-md-2">
								<select class="form-control" disabled ng-model="formData.content_aggregator_type_id">
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
								<select class="form-control" disabled ng-model="formData.content_provider_category_id">
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

									<li ng-repeat="s in service_operators">
										<label class="checkbox-inline">
											<input disabled ng-model="formData.service_operator_id[$index]" ng-checked="isChecked(s.id)"  ng-true-value="{{s.id}}" ng-false-value="0" type="checkbox">{{ s.telco_name}}
										</label>
									</li>

								</ul>

							</div>
						</div>

					</form>
				</div>
				<div class="col-md-12" ng-if="delete_hls_item">
					<hr/>
					<h5>Verify Password Before Delete Operation</h5>
					<br/>
					<form class="form-horizontal" ng-submit="confirmDelete()">
						<div class="form-group">
							<label class="control-label col-md-4">Password</label>
							<div class="col-md-2">
								<input type="password" class="form-control" ng-model="formData.password"/>
							</div>
							<div class="col-md-5">
								<input type="submit" value="Confirm Delete" class="btn btn-danger btn-sm"/>
								<input type="button" ng-click="cancelDelete()" value="Cancel Delete" class="btn btn-success btn-sm"/>
							</div>
						</div>
					</form>
				</div>
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Sub-Category</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($categories)){ ?>
                                            <?php 
                                                  foreach($categories as $category){
                                                      $sub_cateogries = (!empty($category->sub_categories))? explode(",", $category->sub_categories) : array(); 

                                            ?>
                                            <tr>
                                            <td><?php echo $category->category_name; ?></td>
                                                <td>
                                                    <?php 
                                                        if(!empty($sub_cateogries)){
                                                            echo '<ul>';
                                                                foreach($sub_cateogries as $sub_category){
                                                                    echo '<li>'.$sub_category.'</li>';
                                                                }
                                                            echo '</ul>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php }else{ ?>
                                                <tr>
                                                    <td colspan="2" class="text-center">No Category/Sub Category assigned yet.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
				<div class="col-md-12">
					<hr/>
					<h5>Streamer Instance Mapping</h5>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Sl</th>
								<th>HLS URL STB</th>
								<th>HLS URL WEB</th>
								<th>HLS URL Mobile</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($streamer_instances)){ ?>

									<?php foreach($streamer_instances as $i=> $si){ ?>
										<tr >
											<td><?php echo ($i+1); ?></td>
											<td><?php echo $si->hls_url_stb; ?></td>
											<td><?php echo $si->hls_url_web; ?></td>
											<td><?php echo $si->hls_url_mobile; ?></td>
											<td><a class="btn btn-danger btn-xs" ng-click="deleteItem('<?php echo $si->id; ?>')"> Delete</a></td>
										</tr>
								<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
<style type="text/css">
	#service_operator li{list-style: none;}
	#service_operator {margin-left: -35px;}
</style>