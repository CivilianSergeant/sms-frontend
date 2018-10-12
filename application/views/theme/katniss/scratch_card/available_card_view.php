<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="container">

    <div class="panel panel-default">

        <div class="row">

            <div class="col-md-12">
                <div class="panel-heading">

                    <h4 class="widgettitle"> Scratch Card Detail


                        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="buttoncancel" class="btn btn-danger btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back </a>
                    </h4>

                    <span class="clearfix"></span>
                </div>
                <hr/>
            </div>
            <!-- Form Left Part -->
            <?php //test($card_detail); ?>
            <div class="panel-body">
                <div class="col-md-6" style="font-size: 15px">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <?php if($user_info->user_type == 'Group'){ ?>
                                <tr>
                                    <th style="text-align: right">Card Number</th>
                                    <?php if($this->config->item('group_display_card')){ ?>
                                        <td><?php echo $card_detail->card_no; ?></td>
                                    <?php }else{ ?>
                                        <td><?php echo card_mask(16); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <?php if($user_info->user_type == 'LCO'){ ?>
                                <tr>
                                    <th style="text-align: right">Card Number</th>
                                    <?php if($this->config->item('lco_display_card')){ ?>
                                        <td><?php echo $card_detail->card_no; ?></td>
                                    <?php }else{ ?>
                                        <td><?php echo card_mask(16); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th style="text-align: right">Batch Number</th>
                                <td><?php echo $card_detail->batch_no; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Value/Amount</th>
                                <td><?php echo $card_detail->value; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Is Active</th>
                                <td><?php if($card_detail->is_active == 1) { echo '<span class="text-success">Active</span>'; } else { echo '<span class="text-danger">Inactive</span>'; } ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Active From</th>
                                <td><?php echo $card_detail->active_from_date ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Is Suspended</th>
                                <td><?php  echo ($card_detail->is_suspended == 1) ? '<span class="text-danger">Suspended</span>' : '<span class="text-success">Not Suspended</span>'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">SerialNumber</th>
                                <td><?php echo $card_detail->serial_no; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Is Used</th>
                                <td><?php echo ($card_detail->is_used == 1) ? '<span class="text-danger">Used</span>' : '<span class="text-success">Not Used</span>'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Used date</th>
                                <td><?php if($card_detail->used_date) { echo $card_detail->used_date; } else { echo "Not In Used"; } ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Create date</th>
                                <td><?php if($card_detail->created_at){ echo $card_detail->created_at;} ?></td>
                            </tr>
                            <?php
                            if($user_info->user_type == "MSO"){
                                ?>
                                <!--<tr>
                                    <th style="text-align: right">MSO </th>
                                    <td><?php /*echo $card_detail->operator_name; */?></td>
                                </tr>-->
                            <?php } ?>

                            <tr>
                                <th style="text-align: right">Subscriber</th>
                                <td><?php echo (!empty($card_detail->subscriber_name))?$card_detail->subscriber_name : 'Not Assigned'; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Group Name</th>
                                <td><?php echo $group_name; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">LCO </th>
                                <td><?php echo $card_detail->lco_name; ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: right">Recharge By</th>
                                <td>
                                    <?php echo $recharge_by; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

