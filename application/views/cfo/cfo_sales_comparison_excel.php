<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-bar-chart" aria-hidden="true"></i> CFO |</small>
                        Sales Comparison</small>
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/CFO/cfo_sales_comparison" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-table"></i> Excel Mode</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/CFO/cfo_sales_comparison_customer_graph" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-bar-chart"></i> Graph Mode</a></li>
            </ol>
        </nav>
    </section>
    <section class="content">
        <div class="row">
        <div class="col-xs-12">
                <div class="box box-warning" style="padding:2rem ;">
                    <div class="row">
                        <div class="col-lg-2">
                            <!-- Wag burahin, magic yang label haha -->
                            <label for="" style="color:white;">.</label>
                            <button class="btn btn-lg btn-info btn-block" id="btn_export_sales_comparison"> <i class="fa fa-download"></i> Export </button>
                        </div>
                        <div class="col-lg-4">
                            <form action="<?= base_url() ?>index.php/CFO/cfo_sales_comparison" method="POST">
                                <div class="form-group text-center">.
                                    <label for="">Select Revision</label>
                                    <select class="form-control text-center " id="txt_revision_from" name="txt_revision_from">
                                        <?php
                                        foreach ($revision as $rev) :
                                        ?>
                                            <option value="<?= $rev->revision ?>"><?= $rev->revision ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group text-center">
                                <label for="">Current Revision</label>
                                <select class="form-control  text-center" id="txt_revision_to" name="txt_revision_to">
                                    <option value="<?= $selectedDPRNO ?>"><?= $selectedDPRNO ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <!-- Wag burahin, magic yang label haha -->
                            <label for="" style="color:white;">.</label>
                            <button class="btn btn-lg btn-success btn-block"> Submit </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body" style="padding:20px">
                        <?php if ($revFrom != "" || !empty($revFrom)) { ?>
                            <div class="table-responsive freeze-table">
                                <table class="table table-bordered table-hover text-center " id="tbl_sales_comparison">
                                    <thead class="small ">
                                        <tr>
                                            <th>Customer</th>
                                            <th>Model</th>
                                            <?php foreach ($current_rev as $cur_rev) : ?>
                                                <th colspan="4">
                                                    <?= $cur_rev->month_year ?>
                                                </th>
                                                <th colspan="2" rowspan="2" >Difference</th>
                                            <?php endforeach; ?>

                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <?php foreach ($current_rev as $cur_rev) : ?>
                                                <th colspan="2"><?= $revFrom ?></th>
                                                <th colspan="2"><?= $revTo ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
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
                                            $models = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id='$customer->customers_id' ORDER BY sorting_code ASC")->result();
                                        ?>
                                            <tr>
                                                <td rowspan="<?= count($models) + 1 ?>"><?= $customer->customers_desc ?></td>
                                            </tr>

                                            <?php
                                            foreach ($models as $model) :
                                            ?>
                                                <tr>
                                                    <?php if($model->isHidden !== '1' && $model->status !== 'Inactive') {?>
                                                    <td><?= $model->models_desc ?></td>
                                                    <?php
                                                    foreach ($current_rev as $cur_rev) :
                                                        $model_data_revFrom = $this->db->query("SELECT sales_qty, sales_qty*price AS sales_amount FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$revFrom' AND month_year='$cur_rev->month_year'  ")->row();
                                                        $model_data_To = $this->db->query("SELECT sales_qty, sales_qty*price AS sales_amount FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$revTo' AND month_year='$cur_rev->month_year'  ")->row();
                                                        if ($model_data_revFrom !== NULL || $model_data_To !== NULL) {
                                                            if(is_object($model_data_revFrom) && is_object($model_data_To)){
                                                    ?>
                                                            <td><?= number_format($model_data_revFrom->sales_qty) ?></td>
                                                            <td><?= number_format($model_data_revFrom->sales_amount) ?></td>
                                                            <td><?= number_format($model_data_To->sales_qty) ?></td>
                                                            <td><?= number_format($model_data_To->sales_amount) ?></td>

                                                            <?php
                                                            // Computation of Difference is New Revision - Old Revision;
                                                            $qty_difference = $model_data_To->sales_qty - $model_data_revFrom->sales_qty;
                                                            $amount_difference = $model_data_To->sales_amount - $model_data_revFrom->sales_amount;
                                                            if ($qty_difference < 0 || $amount_difference < 0) {
                                                                $qty_difference = '<p style = "color:red;">(' . abs($qty_difference) . ')</p>';
                                                                $amount_difference = '<p style = "color:red;">(' . abs($amount_difference) . ')</p>';
                                                            } elseif ($qty_difference  > 0 || $amount_difference  > 0) {
                                                                $qty_difference = '<p style = "color:green;">' .$qty_difference . '</p>';
                                                                 $amount_difference = '<p style = "color:green;">' . $amount_difference . '</p>';
                                                            }
                                                            ?>
                                                            <td><?= $qty_difference ?></td>
                                                            <td><?= $amount_difference ?></td>

                                                    <?php } }
                                                    endforeach; ?>
                                                <?php } ?>
                                                </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                            <tr>
                                                <td colspan='2'>Total</td>
                                                <?php
                                                foreach ($current_rev as $cur_rev) :
                                                    $total_sales_revFrom = $this->db->query("SELECT SUM(sales_qty) AS total_sales_qty, SUM(sales_qty*price) AS total_sales_amount FROM tbl_new_plan WHERE customer='$customer->customers_id' AND revision='$revFrom' AND month_year='$cur_rev->month_year' ")->row();
                                                    $total_sales_revTo = $this->db->query("SELECT SUM(sales_qty) AS total_sales_qty, SUM(sales_qty*price) AS total_sales_amount FROM tbl_new_plan WHERE customer='$customer->customers_id' AND revision='$revTo' AND month_year='$cur_rev->month_year' ")->row();

                                                    // Computation of Difference is New Revision - Old Revision;
                                                    $total_qty_difference = $total_sales_revTo->total_sales_qty - $total_sales_revFrom->total_sales_qty;
                                                    $total_amount_difference = $total_sales_revTo->total_sales_amount - $total_sales_revFrom->total_sales_amount;
                                                    if ($total_qty_difference < 0 || $total_amount_difference < 0) {
                                                        $total_qty_difference = '<p style = "color:red;">(' . abs($total_qty_difference) . ')</p>';
                                                        $total_amount_difference = '<p style = "color:red;">(' . abs($total_amount_difference) . ')</p>';
                                                    }
                                                ?>
                                                    <td><?= number_format($total_sales_revFrom->total_sales_qty); ?></td>
                                                    <td><?= number_format($total_sales_revFrom->total_sales_amount); ?></td>

                                                    <td><?= number_format($total_sales_revTo->total_sales_qty); ?></td>
                                                    <td><?= number_format($total_sales_revTo->total_sales_amount); ?></td>

                                                    <td><?= $total_qty_difference; ?></td>
                                                    <td><?= $total_amount_difference; ?></td>

                                                <?php endforeach; ?>
                                            </tr>
                                        <?php
                                        endforeach;
                                        ?>
                                        <tr>
                                            <td colspan="2" >Grand Total</td>
                                            <?php
                                            foreach ($current_rev as $cur_rev) :
                                                $grandtotal_sales_revFrom = $this->db->query("SELECT SUM(sales_qty) AS grand_total_sales_qty, SUM(sales_qty*price) AS grand_total_sales_amount FROM tbl_new_plan WHERE revision='$revFrom' AND month_year='$cur_rev->month_year' ")->row();
                                                $grandtotal_sales_revTo = $this->db->query("SELECT SUM(sales_qty) AS grand_total_sales_qty, SUM(sales_qty*price) AS grand_total_sales_amount FROM tbl_new_plan WHERE revision='$revTo' AND month_year='$cur_rev->month_year' ")->row();
                                                
                                                // Computation of Difference is New Revision - Old Revision;
                                                $grandTotal_qty_difference = $grandtotal_sales_revTo->grand_total_sales_qty - $grandtotal_sales_revFrom->grand_total_sales_qty;
                                                $grandTotal_amount_difference = $grandtotal_sales_revTo->grand_total_sales_amount - $grandtotal_sales_revFrom->grand_total_sales_amount;
                                                if ($grandTotal_qty_difference < 0 || $grandTotal_amount_difference < 0) {
                                                    $grandTotal_qty_difference = '(' . abs($grandTotal_qty_difference) . ')';
                                                    $grandTotal_amount_difference = '(' . abs($grandTotal_amount_difference) . ')';
                                                }
                                                
                                            ?>
                                                <td><?=number_format($grandtotal_sales_revFrom->grand_total_sales_qty);?></td>
                                                <td><?=number_format($grandtotal_sales_revFrom->grand_total_sales_amount);?></td>
                                                
                                                <td><?=number_format($grandtotal_sales_revTo->grand_total_sales_qty);?></td>
                                                <td><?=number_format($grandtotal_sales_revTo->grand_total_sales_amount);?></td>

                                                <td><?=$grandTotal_qty_difference?></td>
                                                <td><?=$grandTotal_amount_difference?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else echo "<center> <h2><b> NO DATA </b></h2></center>" ?>

                    </div>
                </div>
            </div>

        </div>

    </section>
</div>
<script src="<?= base_url() ?>assets2/plugins/chart.js/Chart.min.js"></script>

<script>
    $(document).ready(() => {
        const table = document.querySelector("#tbl_sales_comparison");
        let headerCell = null;
        for (let row of table.rows) {
            const firstCell = row.cells[0];
            if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
                headerCell = firstCell;
            } else {
                headerCell.rowSpan++;
                firstCell.remove();
            }
        }

        $("#btn_export_sales_comparison").click(() => {
            exportTableToExcel("tbl_sales_comparison", "sales_Comparison_" + '<?= $revFrom . '-' . $revTo ?>');
        });


    })
</script>

<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
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
</script>



<script>
  $(function () {
    $('#tbl_sales_comparison').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>





<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>