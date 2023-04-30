

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <h1 style="color: white;">
            CFO <small style="color : yellow;">End Inventory Comparison</small> <i class="fa fa-bar-chart" aria-hidden="true" style="color:yellow;"></i>
        </h1>
        </li>
        </ol>
        </nav>
    </section>
    <section class="content">


        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="padding:1rem ;">
                    <div class="box-header">
                        <h1 class="box-title tags"><a title="" href="" class="color2">End Inventory Comparison</a> </h1>
                        <button class="button-33 left" onclick="exportTableToExcel('tbl_endInventory_Comparison', 'End Inventory Comparison')"><i class="fa fa-download" style="color : black"></i> Export Data</button>
                    </div>

                    <div class="box-body  no-padding">
                        <div class="row">
                            <div class="col-md-5">
                                <form action="<?= base_url() ?>index.php/task/End_invty_comparison" method="POST">
                                    <div class="form-group text-center">
                                        <label for="">Select Revision</label>
                                        <select class="form-control selecting" id="txt_revision_to" name="txt_revision_to">
                                            <?php
                                            foreach ($revision as $rev) :
                                            ?>
                                                <option value="<?= $rev->revision ?>"><?= $rev->revision ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group text-center">
                                    <label for="">Current Revision</label>
                                    <select class="form-control selecting" id="txt_revision_from" name="txt_revision_from">
                                        <option value="<?= $selectedDPRNO ?>"><?= $selectedDPRNO ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label for="" style="color:white;">Dont Remove Me</label>
                                <button class="btn btn-lg btn-primary btn-block"> Submit </button>
                                </form>
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <div class="box">
                                <div class="box-body" style="padding:20px">
                                    <?php if ($revFrom != "") { ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover text-center" id="tbl_endInventory_Comparison">
                                                <thead class="small ">
                                                    <tr>
                                                        <th>Customer</th>

                                                        <?php foreach ($current_rev as $cur_rev) : ?>
                                                            <th colspan="4">
                                                                <?= $cur_rev->month_year ?>
                                                            </th>
                                                            <th colspan="2" rowspan="2">GAP</th>
                                                        <?php endforeach; ?>

                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <?php foreach ($current_rev as $cur_rev) : ?>
                                                            <th colspan="2"><?= $revFrom ?></th>
                                                            <th colspan="2"><?= $revTo ?></th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <tr>
                                                        <td ></td>
                                                        <?php foreach ($current_rev as $cur_rev) : ?>
                                                            <th>Quantity</th>
                                                            <th>Amount</th>
                                                            <th>Quantity</th>
                                                            <th>Amount</th>
                                                            <th>Quantity</th>
                                                            <th>Amount</th>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($customers as $customer) :
                                                        $models = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id='$customer->customers_id' ORDER BY models_desc ASC")->result();
                                                    ?>
                                                        <tr>
                                                            <td rowspan="<?= count($models) + 1 ?>"><?= $customer->customers_desc ?></td>
                                                        </tr>
                                                        <?php
                                                        foreach ($current_rev as $cur_rev) :
                                                            $total_end_invty_from = $this->db->query("SELECT SUM((prod_qty-sales_qty)*price) AS end_inventory_amount, SUM(prod_qty-sales_qty ) AS end_inventory_qty FROM tbl_new_plan WHERE customer='$customer->customers_id' AND revision='$revFrom' AND month_year='$cur_rev->month_year' ")->row();
                                                            $total_end_invty_To = $this->db->query("SELECT SUM((prod_qty-sales_qty)*price) AS end_inventory_amount, SUM(prod_qty-sales_qty ) AS end_inventory_qty FROM tbl_new_plan WHERE customer='$customer->customers_id' AND revision='$revTo' AND month_year='$cur_rev->month_year' ")->row();

                                                            // Computation of Difference is New Revision - Old Revision;
                                                            $total_end_invty_qty_gap = abs($total_end_invty_To->end_inventory_qty) - abs($total_end_invty_from->end_inventory_qty);
                                                            $total_end_invty_amount_gap = abs($total_end_invty_To->end_inventory_amount) - abs($total_end_invty_from->end_inventory_amount);

                                                            if ($total_end_invty_qty_gap < 0 || $total_end_invty_amount_gap < 0) {
                                                        $total_end_invty_qty_gap = '<p style = "color:red;">(' . number_format(abs($total_end_invty_qty_gap)) . ')</p>';
                                                        $total_end_invty_amount_gap = '<p style = "color:red;">(' . number_format(abs($total_end_invty_amount_gap)) . ')</p>';

                                                            }elseif ($total_end_invty_qty_gap > 0 || $total_end_invty_amount_gap > 0) {

                                                        $total_end_invty_qty_gap = '<p style = "color:green;">' . number_format($total_end_invty_qty_gap) . '</p>';
                                                        $total_end_invty_amount_gap = '<p style = "color:green;">' . number_format($total_end_invty_amount_gap) . '</p>';


                                                            }

                                                        ?>

                                                             <td><?= number_format(abs($total_end_invty_from->end_inventory_qty)); ?></td>
                                                            <td><?= number_format(abs($total_end_invty_from->end_inventory_amount)); ?></td>

                                                            <td><?= number_format(abs($total_end_invty_To->end_inventory_qty)); ?></td>
                                                            <td><?= number_format(abs($total_end_invty_To->end_inventory_amount)); ?></td>

                                                            <td><?= ($total_end_invty_qty_gap) ?></td>
                                                            <td><?= ($total_end_invty_amount_gap) ?></td>

                                                        <?php endforeach; ?>
                                                </tbody>
                                            <?php
                                                    endforeach; ?>
                                            <tr>
                                                <td>Grand Total</td>
                                                <?php
                                                    foreach ($current_rev as $cur_rev) :
                                                        $endInventoryGrandTotal_RevFrom = $this->db->query(" SELECT SUM((prod_qty-sales_qty)*price) AS end_inventory_amount, SUM(prod_qty-sales_qty ) AS end_inventory_qty FROM tbl_new_plan WHERE  revision='$revFrom' AND month_year='$cur_rev->month_year'")->row();
                                                        $endInventoryGrandTotal_RevTo = $this->db->query(" SELECT SUM((prod_qty-sales_qty)*price) AS end_inventory_amount, SUM(prod_qty-sales_qty ) AS end_inventory_qty FROM tbl_new_plan WHERE  revision='$revTo' AND month_year='$cur_rev->month_year'")->row();
                                                        
                                                          // Computation of Difference is New Revision - Old Revision;
                                                          $grandTotalEndInvty_qty_gap = abs($endInventoryGrandTotal_RevTo->end_inventory_qty) - abs($endInventoryGrandTotal_RevFrom->end_inventory_qty);
                                                          $grandTotalEndInvty_amount_gap = abs($endInventoryGrandTotal_RevTo->end_inventory_amount) - abs($endInventoryGrandTotal_RevFrom->end_inventory_amount);
                                                ?>
                                                             <td><?= abs($endInventoryGrandTotal_RevFrom->end_inventory_qty) ?></td>
                                                             <td><?= abs($endInventoryGrandTotal_RevFrom->end_inventory_amount) ?></td>
                                                             
                                                             <td><?= abs($endInventoryGrandTotal_RevTo->end_inventory_qty) ?></td>
                                                             <td><?= abs($endInventoryGrandTotal_RevTo->end_inventory_amount) ?></td>

                                                             <td><?=$grandTotalEndInvty_qty_gap?></td>
                                                             <td><?=$grandTotalEndInvty_amount_gap?></td>
         


                                                <?php endforeach; ?>
                                            </tr>
                                            </table>
                                        </div>
                                    <?php } else echo "<center> <h2><b> NO DATA </b></h2></center>" ?>

                                </div>
                            </div>
                        </div>

    </section>
</div>
<script src="<?= base_url() ?>assets2/plugins/chart.js/Chart.min.js"></script>

<script>
    function exportTableToExcel(tableID, filename = '') {

        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

        console.log(tableHTML);

        // Specify file name
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }

      function number_format(number) {
        let formatCurrency = new Intl.NumberFormat('en-US');
        var data = formatCurrency.format(number)
        return data;
    }
</script>





<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>