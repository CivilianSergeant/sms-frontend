<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    a.active{padding:10px;color: #195f91;}
    .col-md-2.selected{margin:0px;padding:0px;width:14%;}
    .widgettitle{height:28px;}
</style>
<div id="container">
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12" style="padding-left:0px;">
                        <div class="col-md-2 text-center" style="padding-left:0px;border-right:1px solid lightgray;">
                            <h4 class="widgettitle"><a class="text-primary" href="<?php echo site_url('organization');?>">Basic Info</a></h4>
                        </div>
                        <div class="col-md-2 text-center selected" style="border-right:1px solid lightgray;">
                            <h4 class="widgettitle"><a class="active text-primary" href="<?php echo site_url('organization/default-logo/'.$organization->id); ?>">Default Logo</a></h4>
                        </div>
                        <div class="col-md-2 text-center">
                            <h4 class="widgettitle"><a class="text-primary" href="<?php echo site_url('organization/default-hls/'.$organization->id); ?>">Default URL</a></h4>
                        </div>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="panel-body">
                <form class="form-horizontal" action="<?php echo site_url('organization/upload-logo'); ?>" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <div class="col-md-12">
                    <fieldset>
                        <legend><strong>CHANNEL FALLBACK LOGO</strong></legend>
                    <div  class="col-md-12 push-bottom-20">
                        <div  class="form-group">

                            <label class="control-label col-md-3"> WEB Thumbnail </label>
                            <div class="col-md-8">
                                <input type="file" name="web_logo" style="height:34px;" >
                                <img <?php if(empty($organization->default_web_logo)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_web_logo); } ?>"/><br/>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                <span><small><em>(Max width <?php echo $imageSize['advance']['WEB_LOGO']['width']; ?> , Max height <?php echo $imageSize['advance']['WEB_LOGO']['height']; ?>)</em></small></span>
                            </div>

                        </div>
                    </div>
                    <div  class="col-md-12 push-bottom-20">
                        <hr/>
                        <div  class="form-group">

                            <label class="control-label col-md-3"> STB Thumbnail</label>
                            <div class="col-md-8">
                                <input type="file" name="stb_logo" style="height:34px;" >
                                <img <?php if(empty($organization->default_stb_logo)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_stb_logo); } ?>"/><br/>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                <span><small><em>(Max width <?php echo $imageSize['advance']['STB_LOGO']['width']; ?> , Max height <?php echo $imageSize['advance']['STB_LOGO']['height']; ?>)</em></small></span>
                            </div>

                        </div>
                    </div>
                    <div  class="col-md-12 push-bottom-20">
                        <hr/>
                        <div  class="form-group">

                            <label class="control-label col-md-3"> Mobile Thumbnail</label>
                            <div class="col-md-8">
                                <input type="file" name="mobile_logo" style="height:34px;" >
                                <img <?php if(empty($organization->default_mobile_logo)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_mobile_logo); } ?>"/><br/>
                                <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                <span><small><em>(Max width <?php echo $imageSize['advance']['MOBILE_LOGO']['width']; ?> , Max height <?php echo $imageSize['advance']['MOBILE_LOGO']['height']; ?>)</em></small></span>
                            </div>

                        </div>
                    </div>
                        <div  class="col-md-12 push-bottom-20">
                            <hr/>
                            <div  class="form-group">
                                <label class="control-label col-md-3"> Web Poster</label>
                                <div class="col-md-8">
                                    <input type="file" name="web_poster" style="height:34px;" >
                                    <img <?php if(empty($organization->default_poster_web)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_poster_web); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width <?php echo $imageSize['advance']['WEB_POSTER']['width']; ?>, Max height <?php echo $imageSize['advance']['WEB_POSTER']['height']; ?>)</em></small></span>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-12 push-bottom-20">
                            <hr/>
                            <div  class="form-group">
                                <label class="control-label col-md-3"> STB Poster</label>
                                <div class="col-md-8">
                                    <input type="file" name="stb_poster" style="height:34px;" >
                                    <img <?php if(empty($organization->default_poster_stb)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_poster_stb); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width <?php echo $imageSize['advance']['STB_POSTER']['width']; ?>, Max height <?php echo $imageSize['advance']['STB_POSTER']['height']; ?>)</em></small></span>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-12 push-bottom-20">
                            <hr/>
                            <div  class="form-group">
                                <label class="control-label col-md-3"> Mobile Poster</label>
                                <div class="col-md-8">
                                    <input type="file" name="mobile_poster" style="height:34px;" >
                                    <img <?php if(empty($organization->default_poster_mobile)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_poster_mobile); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width <?php echo $imageSize['advance']['MOBILE_POSTER']['width']; ?>, Max height <?php echo $imageSize['advance']['MOBILE_POSTER']['height']; ?>)</em></small></span>
                                </div>
                            </div>
                        </div>

                        <div  class="col-md-12 push-bottom-20">
                            <hr/>
                            <div  class="form-group">

                                <label class="control-label col-md-3"> Watermark </label>
                                <div class="col-md-8">
                                    <input type="file" name="watermark_logo" style="height:34px;" >
                                    <img <?php if(empty($organization->default_watermark_logo)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_watermark_logo); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width <?php echo $imageSize['watermark']['width']; ?> , Max height <?php echo $imageSize['watermark']['height']; ?>)</em></small></span>
                                </div>

                            </div>
                        </div>
                    </fieldset>
                        <br/>
                        <fieldset>
                            <legend>PACKAGE FALLBACK LOGO</legend>
                            <div  class="form-group">

                                <label class="control-label col-md-3"> Package Logo <small>(STB)</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="pkg_logo_stb" style="height:34px;" onchange="imageView(this,600,600);" >
                                    <img <?php if(empty($organization->default_pkg_logo_stb)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_pkg_logo_stb); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                </div>

                            </div>
                            <hr/>
                            <div  class="form-group">

                                <label class="control-label col-md-3"> Package Logo <small>(Mobile)</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="pkg_logo_mobile" style="height:34px;" onchange="imageView(this,115,115);" >
                                    <img <?php if(empty($organization->default_pkg_logo_mobile)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_pkg_logo_mobile); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 115 , Max height 115)</em></small></span>
                                </div>

                            </div>
                            <hr/>
                            <div  class="form-group">

                                <label class="control-label col-md-3"> Package Poster <small>(STB)</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="pkg_poster_stb" style="height:34px;" onchange="imageView(this,600,600);" >
                                    <img <?php if(empty($organization->default_pkg_poster_stb)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_pkg_poster_stb); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                </div>

                            </div>
                            <hr/>
                            <div  class="form-group">

                                <label class="control-label col-md-3"> Package Poster <small>(Mobile)</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="pkg_poster_mobile" style="height:34px;" onchange="imageView(this,600,600);" >
                                    <img <?php if(empty($organization->default_pkg_poster_mobile)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_pkg_poster_mobile); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                </div>

                            </div>
                        </fieldset>
                        <br/>
                        <fieldset>
                            <legend>EPG FALLBACK LOGO</legend>

                            <div  class="form-group">

                                <label class="control-label col-md-3"> EPG Logo </label>
                                <div class="col-md-8">
                                    <input type="file" name="epg_logo" style="height:34px;" onchange="imageView(this,600,600);" >
                                    <img <?php if(empty($organization->default_epg_logo)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_epg_logo); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                </div>

                            </div>
                            <hr/>
                            <div  class="form-group">

                                <label class="control-label col-md-3"> EPG Poster </label>
                                <div class="col-md-8">
                                    <input type="file" name="epg_poster" style="height:34px;" onchange="imageView(this,600,600);" >
                                    <img <?php if(empty($organization->default_epg_poster)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->default_epg_poster); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 600 , Max height 600)</em></small></span>
                                </div>

                            </div>

                        </fieldset>
                        <br/>
                        <fieldset>
                            <legend>BKash Instruction Image</legend>

                            <div  class="form-group">

                                <label class="control-label col-md-3"> Bkash Image </label>
                                <div class="col-md-8">
                                    <input type="file" name="bkash_info" style="height:34px;" onchange="imageView(this,720,420);" >
                                    <img <?php if(empty($organization->bkash_info_img_url)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->bkash_info_img_url); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 720 , Max height 420)</em></small></span>
                                </div>

                            </div>


                        </fieldset>
                        <br/>
                        <fieldset>
                            <legend>About Us Image</legend>

                            <div  class="form-group">

                                <label class="control-label col-md-3"> About Us Image </label>
                                <div class="col-md-8">
                                    <input type="file" name="about_us_info" style="height:34px;" onchange="imageView(this,720,420);" >
                                    <img <?php if(empty($organization->about_us)){ ?>class="hidden" <?php } ?> style="width:100px; height:100px;" src="<?php if(!empty($organization)){ echo base_url($organization->about_us); } ?>"/><br/>
                                    <span><small><em>(Max filesize 1M and supported format png)</em></small></span><br/>
                                    <span><small><em>(Max width 720 , Max height 420)</em></small></span>
                                </div>

                            </div>


                        </fieldset>
                    </div>

                    <div class="col-md-12">
                        <br/>
                        <!--<fieldset>-->
                            <hr/>
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <button class="btn btn-success" type="submit">Save Logo</button>
                            </div>
                        </div>
                        <!--</fieldset>-->
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var imageView = function(input,w,h){
        var img = null;
        var obj = $(input);
        if(input.files && input.files[0]){

            var fileReader = new FileReader();
            fileReader.readAsDataURL(input.files[0]);
            fileReader.onload= function(e){
                img = new Image();
                img.src= e.target.result;
               /* if(img.width<=w && img.height<=h){*/
                    $(input).next().attr('src', img.src);
                    $(input).next().removeClass('hidden');

                /*}else{
                    alert("width and height must be "+w+"x"+h);
                    input.files[0].name='';
                    $(input).next().attr('src','');
                    $(input).next().addClass('hidden');
                    $(input).val('');

                }*/
            };

        }
    }
</script>