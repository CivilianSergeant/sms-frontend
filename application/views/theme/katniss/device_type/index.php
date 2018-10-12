<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript" src="<?php echo base_url('public/theme/katniss/js/tinymce/tinymce.min.js'); ?>"></script>

<div id="container">

    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading">
                    <div class="col-md-12">
                        <h4 class="widgettitle">
                            Device Name LIST
                          
                    </div>
                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <div class="panel-body">
                <div class="col-md-12" >
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>SL</th>
                            <th>Device Name</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($device_type as $key => $value) { ?>
                              <tr>
                                <td><?php echo $key; ?></td>
                                <td><?php echo $value->device_name;?></td>
                              </tr>
                            <?php } ?>
                        </tbody>
                      </table>
                </div>
                
            </div>
        </div>
    </div>
</div>




