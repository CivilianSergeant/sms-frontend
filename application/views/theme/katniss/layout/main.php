<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php 
    $organization= $this->organization->get_row();
    if(empty($organization->icon)){ ?>

    <link rel="icon" href="<?php echo base_url();?>/public/favicon.png" type="image/x-icon"/>
    <?php }else{ ?>
        <link rel="icon" href="<?php echo base_url($organization->icon);?>" type="image/x-icon"/>
    <?php } ?>
<title><?php echo $title; ?></title>
<?php

echo $style_layout;
echo $script_layout;

?>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src='<?php echo base_url("public/theme/katniss"); ?>/js/excanvas.min.js'></script><![endif]-->
<script type="text/javascript">
var SITE_URL = "<?php echo site_url('/');?>";
var BASE_URL = "<?php echo base_url('/');?>";

window.oncontextmenu = function(){
    //return false;
}

window.history.forward(1);
function noBack(e) {  window.history.forward(1); }
</script>
</head>

<body onload="noBack();" class="skin-blue" ng-app="plaasApp">

<div id="Notify" class="wrapper" ng-controller="Notify" ng-cloak>
<!-- Main Header -->
<header class="main-header">

<!-- Logo -->

<?php

    $organization=$this->organization->get_row(); ?>

    <?php if (!empty($organization->logo)) { ?>
    <a href="<?php echo site_url(); ?>" class="logo">
    <img src="<?php echo base_url($organization->logo); ?>">
    </a>
    <?php }else{ ?>
    <a href="<?php echo site_url(); ?>" class="logo">
    <img style="height:45px; width:203px" src="<?php echo base_url() .'public/uploads/organization/temp.png'?>"/> </a>
    <?php } ?>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu" style="padding-right:10px;"><span><?php
                        $role = $this->role->find_by_id($user_info->role_id);

                        if($role->get_attribute('role_type') =='admin' && $user_info->user_type == 'MSO') {
                            echo ' [' . $user_info->user_type . ']';
                        }else {
                            echo 'Parent: ';
                            $user = $this->user->find_by_id($user_info->parent_id);
                            switch ($user->get_attribute('user_type')) {
                                case 'MSO':
                                    echo $this->mso_profile->get_mso_name($user->get_attribute('id'));
                                    break;
                                case 'LCO':
                                    echo $this->lco_profile->get_lco_name($user->get_attribute('id'));
                                    break;
                                case 'Group':
                                    echo $this->group_profile->get_group_name($user->get_attribute('id'));
                                    break;
                                case 'Subscriber':
                                    echo $this->subscriber_profile->get_subscriber_name($user->get_attribute('id'));
                                    break;
                            }
                            echo ', [' . $user_info->user_type . ']';
                        }


                        ?></span></li>
                <!--<li class="dropdown messages-menu">
                    <!-- Menu toggle button
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success" ng-if="countmsg">{{countmsg}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 4 messages</li>
                        <li>
                           <ul class="menu">
                                <li><!-- start notification
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> New Message
                                    </a>
                                </li><!-- end notification
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>--><!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php //$count=$this->notification->count_msg();?>
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning" ng-if="countNotification">{{countNotification}}</span>
                    </a>
                    <ul class="dropdown-menu">
                    
                        <li class="header">You have {{countNotification}} notifications</li>
                    <?php //$notifications=$this->notification->welcome_msg_view();$notifications= array_reverse($notifications);?>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">

                                <li ng-repeat="n in notifications"><!-- start notification -->
                                    <a href="<?php echo site_url('notification');?>">
                                        <i class="fa fa-users text-aqua"></i> {{n.title}}
                                    </a>
                                </li><!-- end notification -->     

                            </ul>
                        </li>
                        <li class="footer"><a href="<?php echo site_url('notification');?>">View all</a></li>
                    </ul>
                </li>

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="<?php echo base_url('public/theme/katniss/img/avatar.png'); ?>" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">Welcome <?php

                            switch($user_info->user_type){
                                case 'MSO':
                                    echo $this->mso_profile->get_mso_name($user_info->id);

                                    break;
                                case 'LCO':
                                    echo $this->lco_profile->get_lco_name($user_info->id);
                                    break;
                                case 'Group':
                                    echo $this->group_profile->get_group_name($user_info->id);
                                    break;
                                case 'Subscriber':
                                    echo $this->subscriber_profile->get_subscriber_name($user_info->id);
                                    break;
                            }



                        ?> <i class="fa fa-angle-down"></i></span>
                    </a>
                    <ul class="dropdown-menu user-dropdown-menu">
                        <!-- Menu Footer-->
                        <li>
                            <a href="<?php echo site_url('change-password'); ?>"><i class="fa fa-key"></i> Change Password</a></li>
                            <li>
                                <a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-sign-out"></i> Sign out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

           <?php 
           if(isset($left_sidebar))
            echo $left_sidebar; 
        ?>

    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php 
                $uri = $this->uri->segment(1);
                if(preg_match('/live-delay-packages/',$uri)){
                    $uri = 'channel-packages';
                }else if(preg_match('/live-delay-categories/',$uri)){
                    $uri = 'channel-categories';
                }
            ?>
            <a href="#"> / <?php echo (!empty($uri))? strtoupper($uri) : 'Dashboard'; ?> </a>
            <span ng-show="balance" class="pull-right"><strong >[Available Balance: {{balance}}]</strong></span>
        </section>

        <div align="center" id="load" style="margin: auto 0px; position: initial; display: none">
            <img src="<?php echo base_url("public/theme/katniss/img/loading.gif"); ?>">
        </div>
        <!-- Main content -->
        <section class="content">
            <?php 
            $error_messages = $this->session->flashdata('error_messages');
            if (!empty($error_messages)) { ?>
            <div class="alert alert-danger">
                <button class="close" data-dismiss="alert" aria-label="close">&times;</button>
                <p><?php echo $error_messages; ?></p>
            </div>
            <?php } ?>

            <?php
            $warning_messages = $this->session->flashdata('warning_messages');
            if (!empty($warning_messages)) {
                ?>
                <div class="alert alert-warning">
                    <button class="close" data-dismiss="alert" aria-label="close">&times;</button>
                    <p><?php echo $warning_messages; ?></p>
                </div>
                <?php
            }
            ?>



            <?php 
            $success_messages = $this->session->flashdata('success_messages');
            if (!empty($success_messages)) {
               ?>
               <div class="alert alert-success">
                <button class="close" data-dismiss="alert" aria-label="close">&times;</button>
                <p><?php echo $success_messages; ?></p>
            </div>
            <?php  
        }
        ?>

        <?php echo $content; ?>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        Powered By <a href="http://www.nexdecade.com/main/"><?php echo $this->config->item('powered_by'); ?></a>.
    </div>
    <!-- Default to the left --> 


    <strong>Copyright &copy; <?php if(!empty($organization)){ echo $organization->copyright_year;?>
        <a href="http://www.nexdecade.com/main/"><?php echo $organization->organization_name; }?></a>.
    </strong> All rights reserved.
    
</footer>

</div><!-- ./wrapper --> 


<?php echo $custom_script_layout; ?>
<script>
    var sessionExist = 0;
    var intervalId = setInterval(function(){
        $.ajax({
            type:'POST',
            url : SITE_URL+'check-status',
            data:{timezone:(new Date()).toString().split(" ")[5]},
            success:function(e){
                if(e!=1 && sessionExist == 1){
                    window.location.reload();
                    sessionExist = e;
                }
                if(e == 1)
                    sessionExist = e;
            }
        });
    },5000);

</script>
</body>
</html>

