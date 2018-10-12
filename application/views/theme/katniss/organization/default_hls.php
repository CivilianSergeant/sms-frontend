<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    a.active{padding:10px;color: #195f91;}
    .col-md-2.selected{margin-left:0px;padding-left:0px;width:14%;}
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
                        <div class="col-md-2 text-center" style="border-right:1px solid lightgray;">
                            <h4 class="widgettitle"><a class="text-primary" href="<?php echo site_url('organization/default-logo/'.$organization->id); ?>">Default Logo</a></h4>
                        </div>
                        <div class="col-md-2 text-center selected">
                            <h4 class="widgettitle"><a class="active text-primary" href="<?php echo site_url('organization/default-hls/'.$organization->id); ?>">Default URL</a></h4>
                        </div>
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>

            <div class="panel-body">
                <div class="col-md-12">
                    
                        <form class="form-horizontal" method="post" action="<?php echo site_url('organization/update-hls'); ?>" id="defaultHls">
                            <input type="hidden" name="id" value="<?php echo $organization->id; ?>" />
                            <fieldset>
                                <legend><strong>DEFAULT UNDEFINED HLS LINKS</strong></legend>
                                    <div class="form-group">
                                    <label class="control-label col-md-3">Web link</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_hls_web" class="form-control" value="<?php echo $organization->default_hls_web; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Mobile Link</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_hls_mobile" class="form-control" value="<?php echo $organization->default_hls_mobile; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">STB Link</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_hls_stb" class="form-control" value="<?php echo $organization->default_hls_stb; ?>"/>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <br/>
                            <fieldset>
                                <legend><strong>DEFAULT EXPIRED HLS LINKS</strong></legend>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Expired Web link</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_expire_hls_web" class="form-control" value="<?php echo $organization->default_expire_hls_web; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Expired Mobile Link</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_expire_hls_mobile" class="form-control" value="<?php echo $organization->default_expire_hls_mobile; ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Expire STB Link</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_expire_hls_stb" class="form-control" value="<?php echo $organization->default_expire_hls_stb; ?>"/>
                                    </div>
                                </div>
                            </fieldset>
                            <br/>
                            <fieldset>
                                <legend><strong>DEFAULT HLS LINKS</strong></legend>
                                <div class="form-group">
                                    <label class="control-label col-md-3">HLS For Unsubscribed User</label>
                                    <div class="col-md-8">
                                        <input type="text" name="default_unsubscribed_hls" class="form-control" value="<?php echo $organization->default_unsubscribed_hls; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-md-offset-3">
                                        <button class="btn btn-success" type="submit">Save HLS</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    
                    <br/>
                    
                    <fieldset>
                        <legend><strong>OTHER URL</strong></legend>
                        <form class="form-horizontal" method="post" action="<?php echo site_url('organization/update-other-url'); ?>" id="defaultOtherUrl">
                            <input type="hidden" name="id" value="<?php echo $organization->id; ?>" />
                            <div class="form-group">
                                <label class="control-label col-md-3">Default Channel Share URL</label>
                                <div class="col-md-8">
                                    <input type="text" name="default_channel_share_url" class="form-control" value="<?php echo $organization->default_channel_share_url; ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Default VoD Share URL</label>
                                <div class="col-md-8">
                                    <input type="text" name="default_vod_share_url" class="form-control" value="<?php echo $organization->default_vod_share_url; ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Default Catchup Share URL</label>
                                <div class="col-md-8">
                                    <input type="text" name="default_catchup_share_url" class="form-control" value="<?php echo $organization->default_catchup_share_url; ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3 col-md-offset-3">
                                    <button class="btn btn-success" type="submit">Save URL</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>