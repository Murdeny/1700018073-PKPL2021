<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>

<?php
if ($modal) {
    ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php
            } else {
                ?><!doctype html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title></title>
                    <base/>
                    <meta http-equiv="cache-control" content="max-age=0"/>
                    <meta http-equiv="cache-control" content="no-cache"/>
                    <meta http-equiv="expires" content="0"/>
                    <meta http-equiv="pragma" content="no-cache"/>
                    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
                    <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                    <link href="<?= $assets ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
                    <link href="<?= $assets ?>dist/css/custom.css" rel="stylesheet" type="text/css" />
                    <style type="text/css" media="all">
                        body { color: #000; }
                        #wrapper { max-width: 300px; margin: 0 auto; padding-top: 20px; }
                        .btn { margin-bottom: 5px; }
                        .table { border-radius: 0px; }
                        .table th { background: #ffffff; }
                        .table th, .table td { vertical-align: left !important; }
                        h3 { margin: 1px 0; }

                        @media print {
                            .no-print { display: none; }
                            #wrapper { max-width: 300px; width: 150%; min-width: 250px; margin: 0 auto; }
                        }
                    </style>
                </head>
                <body>
                    <?php
                }
                ?>
                <div id="wrapper">
                    <div id="receiptData" style="width: auto; max-width: 900px; min-width: 100px; margin: 0 auto;">
                        <div class="no-print">
                            <?php if ($message) { ?>
                            <div class="alert alert-success">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <?= is_array($message) ? print_r($message, true) : $message; ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div id="receipt-data">
                            <div>
                                <div style="text-align:left;" width="50%";>
                                    <?php
                                    if ($store) {
                                        echo '<img src="'.base_url('uploads/'.$store->logo).'" alt="'.$store->name.'">';
                                        echo '<p style="text-align:left;">';
                                       echo '<h2>'.$store->name.'</h2>';
                                       /*echo'<br>';*/
                                        echo $store->address1.'<br>';
                                        echo $store->city.'<br>';
                                        echo '</p>';
                                        echo '<p>'.nl2br($store->receipt_header).'</p>';
                                    }
                                    ?>
                                </div>
                                <p style="text-align:left;">
                                    <?= lang("").' '.$this->tec->hrld($inv->date); ?> <br>
                                    <?= lang('#').' '.$inv->id; ?><br>
                                    <?= lang("customer").': '. $inv->customer_name; ?> <br>
                                    <?= lang("Kasir").': '. $created_by->first_name; ?> <br>
                                </p>
                                <div style="clear:both;"></div>
								<table class="table table-striped table-condensed">
                                    <thead>
                                        <!--<tr>
                                            <th style="text-align:left; width: 50%; border-bottom: 2px solid #ddd;"><?=lang('description');?></th>
                                            <th style="text-align:center; width: 10%; border-bottom: 2px solid #ddd;"><?=lang('quantity');?></th>
                                            <th style="text-align:center; width: 24%; border-bottom: 2px solid #ddd;"><?=lang('price');?></th>
                                            <th style="text-align:center; width: 26%; border-bottom: 2px solid #ddd;"><?=lang('subtotal');?></th>
                                        </tr>-->
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tax_summary = array();
                                        foreach ($rows as $row) {
                                            echo '<tr><td style="text-align:left;">' . $row->product_name .'</td></tr>';
                                            echo '<td style="text-align:left;">' . $this->tec->formatQuantity($row->quantity) . ' x ';
                                        
                                            echo $this->tec->formatMoney($row->net_unit_price + ($row->item_tax / $row->quantity)) . ' = ' . $this->tec->formatMoney($row->subtotal) . '</td>';
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align:left;"><?= lang("total"). '   ' .$this->tec->formatMoney($inv->total + $inv->product_tax); ?></th></tr>
                                        
                                        <?php
                                        if ($inv->order_tax != 0) {
                                            echo '<tr><th colspan="2">' . lang("order_tax") . '</th><th colspan="2">' . $this->tec->formatMoney($inv->order_tax) . '</th></tr>';
                                        }
                                        if ($inv->total_discount != 0) {
                                            echo '<tr><th colspan="2">' . lang("order_discount") . '</th><th colspan="2">' . $this->tec->formatMoney($inv->total_discount) . '</th></tr>';
                                        }

                                        if ($Settings->rounding) {
                                            $round_total = $this->tec->roundNumber($inv->grand_total, $Settings->rounding);
                                            $rounding = $this->tec->formatDecimal($round_total - $inv->grand_total);
                                            ?>
                                            
                                            <!--<tr>
                                                <th colspan="2" style="text-align:left;"><?= lang("grand_total"); ?></th>
                                                <th colspan="2" style="text-align:left;"><?= $this->tec->formatMoney($inv->grand_total + $rounding); ?></th>
                                            </tr>-->
                                            <?php
                                        } else {
                                            $round_total = $inv->grand_total;
                                            ?>
                                            <tr>
                                                <th colspan="2" style="text-align:left;"><?= lang("grand_total"); ?></th>
                                                <th colspan="2" style="text-align:right;"><?= $this->tec->formatMoney($inv->grand_total); ?></th>
                                            </tr>
                                            <?php
                                        }
                                        if ($inv->paid < $round_total) { ?>
										<tr>
                                            <th colspan="2" style="text-align:left;"><?= lang("paid_amount"); ?></th>
                                            <th colspan="2" style="text-align:right;"><?= $this->tec->formatMoney($inv->paid); ?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="text-align:left;"><?= lang("due_amount"); ?></th>
                                            <th colspan="2" style="text-align:right;"><?= $this->tec->formatMoney($inv->grand_total - $inv->paid); ?></th>
                                        </tr>

                                        
                                        <?php } ?>
                                    </tfoot>
                                </table>
                                <?php
                                if ($payments) {
                                    echo '<table class="table table-striped table-condensed" style="margin-top:10px;"><tbody>';
                                    foreach ($payments as $payment) {
                                        echo '<tr>';
                                        if ($payment->paid_by == 'cash' && $payment->pos_paid) {
                                            echo '<th colspan="2" style="text-align:left;">' . lang("") . ' ' . lang($payment->paid_by) . '</th>';
                                            echo '<th colspan="2" style="text-align:left;">' . lang("") . ' ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . '<br>';
                                            echo lang("") . ' ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</th>';
                                        }
                                        if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) {
                                            echo '<td style="padding-left:15px;">' . lang("") . ' ' . lang($payment->paid_by) . '<br>';
                                            echo  lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid) . '<br>';
                                            echo  lang("") . ' ' . 'xxxx xxxx xxxx ' . substr($payment->cc_no, -4) . '<br>';
                                            echo  lang("name") . ': ' . $payment->cc_holder . '</td>';
                                        }
                                        if ($payment->paid_by == 'Cheque' || $payment->paid_by == 'cheque' && $payment->cheque_no) {
                                            echo '<td style="padding-left:15px;">' . lang("") . ' ' . lang($payment->paid_by) . '<br>';
                                            echo lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid) . '<br>';
                                            echo lang("cheque_no") . ': <br>' . $payment->cheque_no . '</td>';
                                        }
                                        if ($payment->paid_by == 'gift_card' && $payment->pos_paid) {
                                            echo '<td style="padding-left:15px;">' . lang("") . ' ' . lang($payment->paid_by) . '<br>';
                                            echo lang("no") . ': ' . $payment->gc_no . '<br>';
                                            echo lang("amount") . ': ' . $this->tec->formatMoney($payment->pos_paid) . '<br>';
                                            echo lang("balance") . ': ' . ($payment->pos_balance > 0 ? $this->tec->formatMoney($payment->pos_balance) : 0) . '</td>';
                                        }
                                        if ($payment->paid_by == 'other' && $payment->amount) {
                                            echo '<td style="padding-left:0px;">' . lang("amount") . ' : ' . $this->tec->formatMoney($payment->pos_paid == 0 ? $payment->amount : $payment->pos_paid) . '</td>';
                                            echo $payment->note ? '</tr><td colspan="0">' . lang("payment_note") . ': <br>' . $payment->note . '</td>' : '';
                                        }
                                        echo '</tr>';
                                    }
                                    echo '</tbody></table>';
                                }

                                ?>

                                <?= $inv->note ? '<p style="margin-top:10px; text-align: left;">' . $this->tec->decode_html($inv->note) . '</p>' : ''; ?>
                                <?php if (!empty($store->receipt_footer)) { ?>
                                <div class="well well-sm"  style="margin-top:10px;">
                                    <div style="text-align:left;"><?= nl2br($store->receipt_footer); ?></div>
                                </div>
                                <?php } ?>
                            </div>
                            <div style="clear:both;"></div>
                        </div>

                        <!-- start -->
                        <div id="buttons" style="padding-top:10px; text-transform:uppercase;" class="no-print">
                            <hr>
                            <?php if ($modal) { ?>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <div class="btn-group" role="group">
                                    <?php
                                    if ( ! $Settings->remote_printing) {
                                        echo '<a href="'.site_url('pos/print_receipt/'.$inv->id.'/1').'" id="print" class="btn btn-block btn-primary">'.lang("print").'</a>';
                                    } elseif ($Settings->remote_printing == 1) {
                                        echo '<button onclick="window.print();" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                    } else {
                                        echo '<button onclick="return printReceipt()" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                    }
                                    ?>
                                </div>
                               <!-- <div class="btn-group" role="group">
                                    <a class="btn btn-block btn-success" href="#" id="email"><?= lang("email"); ?></a>
                                </div>-->
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
                                </div>
                            </div>
                            <?php } else { ?>
                            <span class="pull-right col-xs-12">
                                <?php
                                if ( ! $Settings->remote_printing) {
                                    echo '<a href="'.site_url('pos/print_receipt/'.$inv->id.'/1').'" id="print" class="btn btn-block btn-primary">'.lang("print").'</a>';
                                    echo '<a href="'.site_url('pos/open_drawer/').'" class="btn btn-block btn-default">'.lang("open_cash_drawer").'</a>';
                                } elseif ($Settings->remote_printing == 1) {
                                    echo '<button onclick="window.print();" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                } else {
                                    echo '<button onclick="return printReceipt()" class="btn btn-block btn-primary">'.lang("print").'</button>';
                                    echo '<button onclick="return openCashDrawer()" class="btn btn-block btn-default">'.lang("open_cash_drawer").'</button>';
                                }
                                ?>
                            </span>
                            <!--<span class="pull-left col-xs-12"><a class="btn btn-block btn-success" href="#" id="email"><?= lang("email"); ?></a></span>-->
                            <span class="col-xs-12">
                                <a class="btn btn-block btn-warning" href="<?= site_url('pos'); ?>"><?= lang("back_to_pos"); ?></a>
                            </span>
                            <?php } ?>
                            <div style="clear:both;"></div>
                        </div>
                        <!-- end -->
                    </div>
                </div>
                <!-- start -->
                <?php
                if (!$modal) {
                    ?>
                    <script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
                    <script src="<?= $assets ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
                    <script src="<?= $assets ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
                    <script src="<?= $assets ?>dist/js/common-libs.js" type="text/javascript"></script>
                    <?php
                }
                ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#print').click(function (e) {
                            e.preventDefault();
                            var link = $(this).attr('href');
                            $.get(link);
                            return false;
                        });
                        $('#email').click(function () {
                            bootbox.prompt({
                                title: "<?= lang("email_address"); ?>",
                                inputType: 'email',
                                value: "<?= $customer->email; ?>",
                                callback: function (email) {
                                    if (email != null) {
                                        $.ajax({
                                            type: "post",
                                            url: "<?= site_url('pos/email_receipt') ?>",
                                            data: {<?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: <?= $inv->id; ?>},
                                            dataType: "json",
                                            success: function (data) {
                                                bootbox.alert({message: data.msg, size: 'small'});
                                            },
                                            error: function () {
                                                bootbox.alert({message: '<?= lang('ajax_request_failed'); ?>', size: 'small'});
                                                return false;
                                            }
                                        });
                                    }
                                }
                            });
                            return false;
                        });
                    });
                </script>
                <?php /* include FCPATH.'themes'.DIRECTORY_SEPARATOR.$Settings->theme.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'pos'.DIRECTORY_SEPARATOR.'remote_printing.php'; */ ?>
                <?php include 'remote_printing.php'; ?>
                <?php
                if ($modal) {
                    ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
    <!-- end -->
    </body>
    </html>
    <?php
}
?>
