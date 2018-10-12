 <!-- sidebar: style can be found in sidebar.less -->
 <section class="sidebar">    
    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">

        <li class="header">SYSTEM MENU <br/><?php date_default_timezone_set('Asia/Dhaka'); echo date('Y-m-d H:i:s'); ?></li>

        <?php $controller = $this->uri->segment(1);?>
        <li <?php if($controller == 'dashboard')  { echo "class='active'";  } ?> ><a href="<?php echo site_url(); ?>"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
        
        <!-- MSO Menus Start -->

        <?php if ($user_info->user_type == 'MSO') { 
            $role = $this->user->get_user_role($user_info->id);

            $role_type = (!empty($role))? strtolower($role->role_type):'';
            $sidebar_menu = $this->menus->get_menus($role->id,$role->user_type);
            //test($sidebar_menu);
            /*array_map(function($item){
                $item->submenus = json_decode($item->submenus);
                return $item;
            },$sidebar_menu);*/

        ?>
        <?php if($role_type == 'admin'){?>
        <!--<li class="treeview">
            <a href="#"><i class="fa fa-cog"></i><span>Administration</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu" <?php if(in_array($controller,array('module','menu','sub-menu'))){ echo 'style="display:block;"'; } ?>>
                <li <?php if($controller == 'user-role')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('user-role'); ?>"><span>User Role</span></a></li>
                <!-- <li <?php if($controller == 'menu')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('menu'); ?>"><span>Menu</span></a></li>
                <li <?php if($controller == 'sub-menu')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('sub-menu'); ?>"><span>Sub Menu</span></a></li>     -->
                <!--      <li <?php if($controller == 'permissions')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('permissions'); ?>"><span>Role Permissions</span></a></li>
                <li <?php if($controller == 'mso')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mso'); ?>"><span>MSO Users</span></a></li>
            </ul>
        </li>-->
        <?php } ?>

        <?php if(!empty(1)){ ?>
            <?php foreach($sidebar_menu as $smenu){ ?>
                    <?php if(!$smenu['permission'] && $role_type == "staff"){
                        continue;

                    }?>
                    <li class="treeview">
                    <a href="<?php echo ((!empty($smenu['submenus']))? "#": site_url(strtolower($smenu['main_menu']))); ?>"><i class="fa <?php echo substr($smenu['main_menu_route'],1); ?>"></i> <span><?php echo $smenu['main_menu']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
                    <?php if(!empty($smenu['submenus'])){ ?>
                        <ul class="treeview-menu" <?php if(in_array($controller,$smenu['routes'])){ echo 'style="display:block;"'; }?>>
                        <?php
                            foreach($smenu['submenus'] as $i=>$submenu) {
                                if (!$submenu['permission'] && $role_type == "staff") {
                                    continue;
                                }
                        ?>
                                    <li <?php if ($controller == $submenu['route']) {  echo "class='active'";  } ?> >
                                        <a href="<?php echo site_url($submenu['route']); ?>"><span><?php echo $submenu['name']; ?></span></a>
                                    </li>
                        <?php

                            }
                        ?>
                        </ul>
                    <?php } ?>
                    </li>

            <?php } ?>
        <?php } ?>
        <?php if(0){ ?>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>System Settings</span> <i class="fa fa-angle-left pull-right"></i></a>

                <ul class="treeview-menu" <?php if(in_array($controller,array('location','organization','region'))){ echo 'style="display:block;"'; } ?>>

                    <!-- <li <?php //if($controller == 'user-role')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('user-role'); ?>"><span>User Role</span></a></li> -->

                    <li <?php if($controller == 'location')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('location'); ?>"><span>Geo Location Manage</span></a></li>
                    <li <?php if($controller == 'organization')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('organization'); ?>"><span>System Info</span></a></li>
                    <li <?php if($controller == 'region')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('region'); ?>"><span>Business Region</span></a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Package-Plan</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('program','package'))){ echo 'style="display:block;"'; }?>>
                    <li <?php if($controller == 'program')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('program'); ?>"><span>Program Manage</span></a></li>
                    <li <?php if($controller == 'package')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('package'); ?>"><span>Package Manage</span></a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Add-On Packages </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('add-on-package'))){ echo 'style="display:block;"'; }?>>
                    <li <?php if($controller == 'add-on-package')  { echo "class='active'";  } ?>><a href="<?php echo site_url('add-on-package'); ?>">Package List</a></li>
                    <li><a href="<?php echo site_url('add-on-package/subscriber'); ?>">Add-On Subscriber List</a></li>
                </ul>
            </li>
            <li <?php if($controller == 'nvod')  { echo "class='active'";  } ?>>
                <a href="#"><i class="fa fa-cog"></i> <span>NVOD Packages </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="">Create NVOD Channel</a></li>
                    <li><a href="">Configure NVOD</a></li>
                    <li><a href="">NVOD Subscriber List</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Equipment Management </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('icsmart-card','set-top-box','stb-provider','icsmartcard-provider'))){ echo 'style="display:block;"'; } ?>>
                    <li <?php if($controller == 'stb-provider')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('stb-provider'); ?>"><span>STB Provider</span></a></li>
                    <li <?php if($controller == 'icsmartcard-provider')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('icsmartcard-provider'); ?>"><span>IC or Smart card Provider</span></a></li>
                    <li <?php if($controller == 'set-top-box')  { ?> class="active" <?php } ?>><a href="<?php echo site_url('set-top-box'); ?>"><span>Set-Top Box</span></a></li>
                    <li <?php if($controller == 'icsmart-card')  { ?> class="active" <?php } ?>><a href="<?php echo site_url('icsmart-card'); ?>"><span>IC or Smart Card</span></a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>FOC Subscriber </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('foc-subscriber'))){ echo 'style="display:block;"'; } ?>>
                    <li <?php if($controller == 'foc-subscriber')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('foc-subscriber'); ?>"><i class="fa fa-bars"></i> <span>Subscriber Lists</span></a></li>
                    <!-- <li <?php if($controller == 'subscriber-recharge')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('subscriber-recharge'); ?>"><i class="fa fa-bars"></i> <span>Recharge Account</span></a></li> -->
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>LCO </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('lco','lco-assign-stb','lco-assign-card'))){ echo 'style="display:block;"'; } ?>>
                    <li <?php if($controller == 'lco')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco'); ?>"><i class="fa fa-bars"></i> <span>LCO Manage</span></a></li>
                    <li <?php if($controller == 'lco-users')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco-users'); ?>"><i class="fa fa-bars"></i> <span>LCO-Users</span></a></li>
                    <li <?php if($controller == 'lco-subscribers')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco-subscribers'); ?>"><i class="fa fa-bars"></i> <span>LCO-Subscribers</span></a></li>
                    <li <?php if($controller == 'lco-assign-stb')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco-assign-stb'); ?>"><i class="fa fa-bars"></i> <span>Assign STB</span></a></li>
                    <li <?php if($controller == 'lco-assign-card')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco-assign-card'); ?>"><i class="fa fa-bars"></i> <span>Assign Card</span></a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Scratch Card </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('card-generate','card-report','card-distribute'))){ echo 'style="display:block;"'; } ?>>
                    <li <?php if($controller == 'card-generate')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('card-generate'); ?>"><i class="fa fa-bars"></i> <span>Generate Card</span></a></li>
                    <li <?php if($controller == 'card-distributor')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('card-distributor'); ?>"><i class="fa fa-bars"></i> <span>Card Distributor</span></a></li>
                    <li <?php if($controller == 'card-distribute')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('card-distribute'); ?>"><i class="fa fa-bars"></i> <span>Card Distribution</span></a></li>
                    <li <?php if($controller == 'card-report')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('card-report'); ?>"><i class="fa fa-bars"></i> <span>Card Report</span></a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Payments </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <!-- <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Cash</span></a></li> -->
                    <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Bank</span></a></li>
                    <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Online (Debit/Credit Card)</span></a></li>
                    <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Scratch-Card</span></a></li>
                    <!-- <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>POS</span></a></li> -->
                    <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Bkash</span></a></li>
                    <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Gift Voucher</span></a></li>
                    <li <?php if($controller == 'refund')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Refund</span></a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Tools </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('conditional-mail','conditional-search','pair-stb-ic'))){ echo 'style="display:block;"'; } ?>>
                    <li <?php if($controller == 'conditional-mail')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('conditional-mail'); ?>"><i class="fa fa-bars"></i> <span>Conditional Mail</span></a></li>
                    <li <?php if($controller == 'conditional-search')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('conditional-search'); ?>"><i class="fa fa-bars"></i> <span>Conditional Search</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Pair STB and IC</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Conditional OSD</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Force OSD</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Conditional Limited</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>ECM Finger Print</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>EMM Finger Print</span></a></li>
                    <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Temporary Authorization</span></a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Reports </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu" <?php if(in_array($controller,array('mail-log'))){ echo 'style="display:block;"'; } ?>>
                    <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>Active Subscriber</span></a></li>
                    <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>Assigned Package</span></a></li>
                    <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>STB Status</span></a></li>
                    <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>SmartCard Status</span></a></li>
                    <!-- <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Mail Log</span></a></li> -->
                    <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Subscriber Transaction</span></a></li>
                    <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Recharge History</span></a></li>
                    <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Scratch Card Status</span></a></li>
                    <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>System Log</span></a></li>
                </ul>
            </li>
        <?php } ?>
            <!-- MSO Menus End -->

        <!-- LCO Menus Start -->

        <?php } else if ($user_info->user_type == 'LCO') {
            $role = $this->user->get_user_role($user_info->id);
            $role_type = (!empty($role))? strtolower($role->role_type):'';

            $sidebar_menu = $this->menus->get_menus($role->id,$role->user_type);
            //test($sidebar_menu);
         ?>
            <?php if(!empty(1)){ ?>
                <?php foreach($sidebar_menu as $smenu){ ?>
                    <?php if(!$smenu['permission'] && $role_type == "staff"){
                        continue;
                    }?>
                        <li class="treeview">
                            <a href="#"><i class="fa <?php echo substr($smenu['main_menu_route'],1); ?>"></i> <span><?php echo $smenu['main_menu']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
                            <?php if(!empty($smenu['submenus'])){ ?>
                                <ul class="treeview-menu" <?php if(in_array($controller,$smenu['routes'])){ echo 'style="display:block;"'; }?>>
                                    <?php
                                    foreach($smenu['submenus'] as $i=>$submenu) {
                                        if (!$submenu['permission'] && $role_type=="staff") {
                                                continue;
                                            }
                                            ?>
                                            <li <?php if ($controller == $submenu['route']) {  echo "class='active'";  } ?> >
                                                <a href="<?php echo site_url($submenu['route']); ?>"><span><?php echo $submenu['name']; ?></span></a>
                                            </li>
                                            <?php

                                    }
                                    ?>
                                </ul>
                            <?php } ?>
                        </li>

                <?php } ?>
            <?php } ?>
        <?php if(0){ ?>
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i><span>LCO</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu" <?php if(in_array($controller,array('lco','lco-role','collector','assign-money-receipt'))){ echo 'style="display:block;"'; } ?>>
                <?php if($role_type == 'admin'){?>
                <li <?php if($controller == 'lco')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco'); ?>"><i class="fa fa-bars"></i> <span>LCO User</span></a></li>
                <li <?php if($controller == 'lco-role')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('lco-role'); ?>"><i class="fa fa-bars"></i> <span>LCO Role</span></a></li>
                <?php } ?>
                <li <?php if($controller == 'collector')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('collector'); ?>"><i class="fa fa-bars"></i> <span>Billing Collector</span></a></li>
                <li <?php if($controller == 'assign-money-receipt')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('assign-money-receipt'); ?>"><i class="fa fa-bars"></i> <span>Assign Money Receipt</span></a></li>
            </ul>
        </li> 
              
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i> <span>Subscriber Manage </span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu" <?php if(in_array($controller,array('subscriber','subscriber-recharge'))){ echo 'style="display:block;"'; } ?>>
                <li <?php if($controller == 'subscriber')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('subscriber'); ?>"><i class="fa fa-bars"></i> <span>Subscriber Lists</span></a></li>
                <li <?php if($controller == 'subscriber-recharge')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('subscriber-recharge'); ?>"><i class="fa fa-bars"></i> <span>Recharge Account</span></a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="<?php echo site_url('package'); ?>"><i class="fa fa-cog"></i> <span>Packages </span></a>
        </li>
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i> <span>Add-On Packages </span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="<?php echo site_url('add-on-package'); ?>">Package List</a></li>
                <li><a href="<?php echo site_url('add-on-package/assign'); ?>">Assign package</a></li>
                <li><a href="<?php echo site_url('add-on-package/subscriber'); ?>">Add-On Subscriber List</a></li>
            </ul>
        </li>
        <li <?php if($controller == 'nvod')  { echo "class='active'";  } ?>>
            <a href="#"><i class="fa fa-cog"></i> <span>NVOD Packages </span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="">Assign NVOD</a></li>
                <li><a href="">Show NVOD Time-table</a></li>
                <li><a href="">NVOD Subscriber List</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i> <span>Payments </span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('billing/cash'); ?>"><i class="fa fa-bars"></i> <span>Cash</span></a></li>
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Bank</span></a></li>
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Online (Debit/Credit Card)</span></a></li>
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Scratch-Card</span></a></li>
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>POS</span></a></li>
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Bkash</span></a></li>
                <li <?php if($controller == 'payments')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Gift Voucher</span></a></li>
                <li <?php if($controller == 'refund')  { echo "class='active'";  } ?> ><a href="#"><i class="fa fa-bars"></i> <span>Refund</span></a></li>
            </ul>
        </li>
        
        <li <?php if($controller == 'parking-zone')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('parking-zone'); ?>"><i class="fa fa-bars"></i> <span>Ownership Transfer</span></a></li>        
                
        
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i> <span>Tools </span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu" <?php if(in_array($controller,array('conditional-mail','conditional-search','pair-stb-ic'))){ echo 'style="display:block;"'; } ?>>
                <li <?php if($controller == 'conditional-mail')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('conditional-mail'); ?>"><i class="fa fa-bars"></i> <span>Conditional Mail</span></a></li>
                <li <?php if($controller == 'conditional-search')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('conditional-search'); ?>"><i class="fa fa-bars"></i> <span>Conditional Search</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Pair STB and IC</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Conditional OSD</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Force OSD</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Conditional Limited</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>ECM Finger Print</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>EMM Finger Print</span></a></li>
                <li <?php if($controller == 'pair-stb-ic')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('pair-stb-ic'); ?>"><i class="fa fa-bars"></i> <span>Temporary Authorization</span></a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#"><i class="fa fa-cog"></i> <span>Reports </span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu" <?php if(in_array($controller,array('mail-log'))){ echo 'style="display:block;"'; } ?>>
                <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>Active Subscriber</span></a></li>
                <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>Assigned Package</span></a></li>
                <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>STB Status</span></a></li>
                <li <?php if($controller == 'report-user')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('report-user'); ?>"><i class="fa fa-bars"></i> <span>SmartCard Status</span></a></li>
                <!-- <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Mail Log</span></a></li> -->
                <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Subscriber Transaction</span></a></li>
                <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Recharge History</span></a></li>
                <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>Scratch Card Status</span></a></li>
                <li <?php if($controller == 'mail-log')  { echo "class='active'";  } ?> ><a href="<?php echo site_url('mail-log'); ?>"><i class="fa fa-bars"></i> <span>System Log</span></a></li>
            </ul>
        </li>
            <?php }?>
        <!-- LCO Menus End -->

        <!-- Subscriber Menus Start -->

        <?php } else if ($user_info->user_type == 'Subscriber') {
            $role = $this->user->get_user_role($user_info->id);
            $role_type = (!empty($role))? strtolower($role->role_type):'';

            $sidebar_menu = $this->menus->get_menus($role->id,$role->user_type);
         ?>
        <?php if(!empty($sidebar_menu)){
            foreach($sidebar_menu as $smenu){ //test($smenu); ?>
                <li class="treeview">
                    <a href="<?php echo (!empty($smenu['main_menu_route'])? site_url($smenu['main_menu_route'] .'/'.$user_info->token) : '#'); ?>"> <i class="fa fa-cog"></i> <span><?php echo $smenu['main_menu']; ?></span> <?php  if(!empty($smenu['submenus'])){ ?><i class="fa fa-angle-left pull-right"></i><?php } ?></a>
                    <?php if(!empty($smenu['submenus'])){ ?>
                        <ul class="treeview-menu" <?php if(in_array($controller,$smenu['routes'])){ echo 'style="display:block;"'; }?>>
                            <?php
                            foreach($smenu['submenus'] as $i=>$submenu) {
                                if (!$submenu['permission'] && $role_type=="staff") {
                                    continue;
                                }
                                ?>
                                <li <?php if ($controller == $submenu['route']) {  echo "class='active'";  } ?> >
                                    <a href="<?php echo site_url($submenu['route'].'/'.$user_info->token); ?>"><span><?php echo $submenu['name']; ?></span></a>
                                </li>
                                <?php

                            }
                            ?>
                        </ul>
                    <?php } ?>
                </li>
            <?php }
            }
        ?>
                <!--<li <?php /*if($controller == 'profile')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('profile/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                <!--<li <?php /*/*if($controller == 'user-info')  { echo "class='active'";  } */?> ><a href="<?php /*/*echo site_url('user-info/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>User Info</span></a></li>-->
                <!--<li <?php /*/*if($controller == 'billing-info')  { echo "class='active'";  } */?> ><a href="<?php /*/*echo site_url('billing-info/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>Billing Information</span></a></li>-->
                <!--<li <?php /*if($controller == 'subscriber-packages')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('subscriber-packages/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>Packages</span></a></li>
                <li <?php /*if($controller == 'subscriber-addon-packages')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('subscriber-addon-packages/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>Add-on Packages</span></a></li>
                <li <?php /*if($controller == 'user-documents')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('user-documents/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>Documents</span></a></li>
                <li <?php /*if($controller == 'subscription-info')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('subscription-info/'.$user_info->token); */?>"><i class="fa fa-user"></i> <span>Subscription Information</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-cog"></i> <span>Recharge </span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li <?php /*if($controller == 'subscription-scratch-recharge')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('subscription-scratch-recharge/'.$user_info->token); */?>"> <span>Scratch Card Recharge</span></a></li>
                    <li <?php /*if($controller == 'subscription-online-recharge')  { echo "class='active'";  } */?> ><a href="<?php /*echo site_url('subscription-online-recharge/'.$user_info->token); */?>"> <span>Online Recharge</span></a></li>
                </ul>
                <li <?php /*if($controller == 'my-transactions') { echo "class='active'"; } */?> ><a href="<?php /*echo site_url('my-transactions/'.$user_info->token); */?>"><i class="fa fa-money"></i> <span>Transactions</span></a></li>
            </li>-->
        <?php } else if ($user_info->user_type == 'Group') {
            $role = $this->user->get_user_role($user_info->id);
            $role_type = (!empty($role))? strtolower($role->role_type):'';
            
            $sidebar_menu = $this->menus->get_menus($role->id,$role->user_type);
        ?>
            <?php if(!empty($sidebar_menu)){ ?>
                <?php foreach($sidebar_menu as $smenu){ ?>
                    <?php if(!$smenu['permission'] && $role_type == "staff"){
                        continue;
                    }?>
                        <li class="treeview">
                            <a href="#"><i class="fa <?php echo substr($smenu['main_menu_route'],1); ?>"></i> <span><?php echo $smenu['main_menu']; ?></span> <i class="fa fa-angle-left pull-right"></i></a>
                            <?php if(!empty($smenu['submenus'])){ ?>
                                <ul class="treeview-menu" <?php if(in_array($controller,$smenu['routes'])){ echo 'style="display:block;"'; }?>>
                                    <?php
                                    foreach($smenu['submenus'] as $i=>$submenu) {
                                        if (!$submenu['permission'] && $role_type=="staff") {
                                                continue;
                                            }
                                            ?>
                                            <li <?php if ($controller == $submenu['route']) {  echo "class='active'";  } ?> >
                                                <a href="<?php echo site_url($submenu['route']); ?>"><span><?php echo $submenu['name']; ?></span></a>
                                            </li>
                                            <?php

                                    }
                                    ?>
                                </ul>
                            <?php } ?>
                        </li>

                <?php } ?>
            <?php } ?>
        <?php } ?>

        <!-- Subscriber Menus End -->
    </ul>                       
    <!-- /.sidebar-menu -->
</section>


