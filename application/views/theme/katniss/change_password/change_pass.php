<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">
  

    
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <h4 class="widgettitle"> Change Password</h4>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <hr/>
            <div class="panel-body">
                <form action="<?php echo site_url('update-password'); ?>" method="POST" class="form-horizontal">
                    <div class="col-md-12">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="old_password">Old Password </label>                     
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="old_password" placeholder="Enter Old Password" name="old_password" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="new_password"> Password </label>                     
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="re_password"> Retype Password </label>                     
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="re_password" name="re_password" placeholder="Enter Retype password" required="required">
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-2 col-md-offset-2">
                            <button class="btn btn-success">Update</button>
                        </div>
                    </div>
                    </form>
            </div>
       </div>
    </div>
</div>