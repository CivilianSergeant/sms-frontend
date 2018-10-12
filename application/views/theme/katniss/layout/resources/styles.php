



<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/fonts.googleapis.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/datatables.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/prettify/prettify.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/AdminLTE.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/skins/skin-blue.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/animate.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/base.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/wizerd.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>public/theme/katniss/css/custom.css" type="text/css" />


<?php

if(!empty($styles))
{
    foreach($styles as $style)
    {
        echo '<link rel="stylesheet" href="'.$style.'" type="text/css" />';
    }
}
?>