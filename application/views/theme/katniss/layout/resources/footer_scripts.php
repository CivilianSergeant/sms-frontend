<script type="text/javascript" src="<?php echo base_url().'public/node_modules/angular/'; ?>index.js"></script>
<script type="text/javascript" src="<?php echo base_url().'public/theme/katniss/js/'; ?>add_field.js"></script>
<?php

if(!empty($scripts))
{
    foreach($scripts as $script)
    {
        echo '<script type="text/javascript" src="'.base_url().'public/'.$script.'"></script>';
    }
}


?>