<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="container">

	<div class="row">
  <?php if($user_info->user_type=='MSO'){?>
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="<?php echo site_url('program');?>">
      <div class="small-box bg-aqua">
        <div class="top">
          <h4>Program</h4>
        </div>
        <div class="inner">
          <h3><span>Total </span><?php echo $total_program->totalprogram;?></h3>
        </div>
          <div class="icon">
          <i class="fa fa-bar-chart"></i>
        </div>
        <div class="small_box_footer small-box-footer">
          <p >Active <?php echo $program_active->activeprogram;?></p> 
          <p style="float:right;">Deactive <?php echo $program_deactive->deactiveprogram;?></p>
        </div>
      </div></a>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="<?php echo site_url('package');?>">
      <div class="small-box bg-green">
        <div class="top">
          <h4>Package</h4>
        </div>
        <div class="inner">
          <h3><span>Total </span><?php echo $total_package->totalpackage;?></h3>
        </div>
        <div class="icon">
          <i class="fa fa-star-o"></i>
        </div>
        <div class="small_box_footer small-box-footer">
          <p>Active <?php echo $package_active->activepackage;?></p> 
          <p style="float:right;">Deactive <?php echo $package_deactive->deactivepackage;?></p>
        </div>
      </div>
      <a/>
    </div>
    <div class="col-lg-3 col-xs-6">
       <a href="<?php echo site_url('lco');?>">
      <div class="small-box bg-yellow">
        <div class="top">
          <h4>LCO</h4>
        </div>
         <div class="inner">
          <h3><span>Total </span><?php echo $count_lco->totalcount;?></h3>
        </div>
        <div class="icon">
          <i class="fa fa-user"></i>
        </div>
        <div class="small_box_footer small-box-footer">
        </div>
      </div>
      </a>
    </div>
    <div class="col-lg-3 col-xs-6">
      <a href="<?php echo site_url('mso');?>">
      <div class="small-box bg-red">
        <div class="top">
          <h4>MSO Staff</h4>
        </div>
         <div class="inner">
          <h3><span>Total </span><?php echo $mso_staff->totalstaff;?></h3>
        </div>
        <div class="icon">
          <i class="fa fa-pie-chart"></i>
        </div>
         <div class="small_box_footer small-box-footer">
        </div>
      </div>
      </a>
    </div>
    <?php } ?>
    <?php if($user_info->user_type=='LCO'){?>
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="<?php echo site_url('program');?>">
      <div class="small-box bg-aqua">
        <div class="top">
          <h4>Subscriber</h4>
        </div>
        <div class="inner">
          <h3><span>Total </span><?php echo $lco_staff->totalstaff;?></h3>
        </div>
          <div class="icon">
          <i class="fa fa-bar-chart"></i>
        </div>
        <div class="small_box_footer small-box-footer">
          <p >Active <?php echo $staff_active->staffactive;?></p> 
          <p style="float:right;">Deactive <?php echo $staff_deactive->staffdeactive;?></p>
        </div>
      </div></a>
    </div><!-- ./col -->

    <?php } ?>
  </div>
</div>
