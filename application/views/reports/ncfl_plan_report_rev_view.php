<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> NCFL Plan Report
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/ncfl_plan_report" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-file-o"></i> PLAN</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/ncfl_plan_report_actual" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-check-circle"></i> ACTUAL</a></li>
            </ol>
        </nav>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title">NCFL Plan (
                            <?= $selectedDPRNO; ?> )
                        </h3>
                        <button class="button-33" onclick="exportTableToExcel('tblData', 'NCFL_Plan_Report<?= $selectedDPRNO; ?>')">Export Data</button>
                        <div class="box-tools">
                            <form action="<?php echo base_url(); ?>index.php/task/ncfl_plan_report_rev_view" method="POST">
                                <div class="input-group">
                                    <select type="text" name="revi" class="form-control">
                                        <option value="">Select Revision</option>
                                        <?php
                                        if (!empty($revision)) {
                                            foreach ($revision as $rev) : ?>
                                                <option value="<?php echo $rev->revision ?>">
                                                    <?php echo $rev->revision ?>
                                                </option>
                                        <?php
                                            endforeach;
                                        } ?>
                                    </select>
                                    <button class="btn btn-lg btn-success" type="submit"> <i class="fa fa-search"></i> </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive freeze-table">
                        <table class="table table-bordered table-hover " id="tbl_models">
                            <?php
                            if (isset($current_rev) && count($customers) > 0) {
                            ?>
                                <thead class="small lead text-center">
                                    <tr>
                                        <th rowspan="2">Customer</th>
                                        <th rowspan="2">Model</th>
                                        <th rowspan="2">Active Price</th>
                                    </tr>
                                    <tr>
                                        <?php foreach ($current_rev as $cur_rev) : ?>
                                            <th rowspan="2" colspan="7">
                                                <?= $cur_rev->month_year ?>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <th rowspan="2"> </th>
                                        <th rowspan="2"> </th>
                                        <th rowspan="2"> </th>
                                    </tr>
                                    <tr>
                                        <?php foreach ($current_rev as $cur_rev) : ?>
                                            <th rowspan="3">Monthly Price</th>
                                            <th rowspan="3">Production Quantity</th>
                                            <th rowspan="3">Production Amount</th>
                                            <th rowspan="3">Sales Quantity</th>
                                            <th rowspan="3">Sales Amount</th>
                                            <th rowspan="3">Inventory</th>
                                            <th rowspan="3">Inventory Amount</th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <thead hidden>
                                    <tr>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $c) :
                                        $models = $this->db->query("SELECT  * FROM tbl_models WHERE customer_models_id='$c->customers_id' ORDER BY sorting_code ASC ")->result(); ?>
                                        <tr>
                                            <td rowspan="<?= count($models) + 2 ?>"><?= $c->customers_desc; ?></td>
                                            <?php foreach ($models as $model) : ?>
                                        </tr>
                                        <?php
                                                $models_prices = $this->db->query("SELECT DISTINCT active_price FROM tbl_models WHERE models_id = '$model->models_id '")->row();
                                                foreach ($models_prices as $models_price) :
                                        ?>
                                            <td rowspan="<?= count($models_price) ?>"><?php echo $model->models_desc; ?></td>
                                            <td><?= $models_price ?></td>
                                            <?php
                                                    $models_datas = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$selectedDPRNO' ORDER BY month_year_numerical ASC  ")->result();
                                                    $inv = 0;
                                                    foreach ($models_datas as $models_data) :
                                                        $inv +=  ($models_data->prod_qty  - $models_data->sales_qty);
                                                        // *** LAHAT NG MAHAHABANG CODES - COMPUTATION NG PABABA NA DATA!!!***
                                                        // Compute total of production quantity per customer per month
                                                        if (isset($customer_total[$models_data->customer]['prod_qty'][$models_data->month_year])) {
                                                            $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] += $models_data->prod_qty;
                                                        } else {
                                                            $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] = $models_data->prod_qty;
                                                        }
                                                        // Compute total of production amount per customer per month
                                                        if (isset($customer_total[$models_data->customer]['prod_amount'][$models_data->month_year])) {
                                                            $customer_total[$models_data->customer]['prod_amount'][$models_data->month_year] += $models_data->prod_qty * $models_data->price;
                                                        } else {
                                                            $customer_total[$models_data->customer]['prod_amount'][$models_data->month_year] = $models_data->prod_qty * $models_data->price;
                                                        }
                                                        // Compute total of sales quantity per customer per month
                                                        if (isset($customer_total[$models_data->customer]['sales_qty'][$models_data->month_year])) {
                                                            $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year] += $models_data->sales_qty;
                                                        } else {
                                                            $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year] = $models_data->sales_qty;
                                                        }
                                                        // Compute total of sales amount per customer per month
                                                        if (isset($customer_total[$models_data->customer]['sales_amount'][$models_data->month_year])) {
                                                            $customer_total[$models_data->customer]['sales_amount'][$models_data->month_year] += $models_data->sales_qty * $models_data->price;
                                                        } else {
                                                            $customer_total[$models_data->customer]['sales_amount'][$models_data->month_year] = $models_data->sales_qty * $models_data->price;
                                                        }

                                                        // Compute total of INVENTORY AMOUNT per customer per month
                                                        if (isset($inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year])) {
                                                            $inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year] += $inv * $models_data->price;
                                                        } else {
                                                            $inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year] = $inv * $models_data->price;
                                                        }

                                                        // Compute Grand total of INVENTORY AMOUNT per customer per month
                                                        if (isset($invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year])) {
                                                            $invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year] += $inv * $models_data->price;
                                                        } else {
                                                            $invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year] = $inv * $models_data->price;
                                                        }

                                                        // Compute Grand total of sales quantity 
                                                        if (isset($g_total['sales_qty'][$models_data->month_year])) {
                                                            $g_total['sales_qty'][$models_data->month_year] += $models_data->sales_qty;
                                                        } else {
                                                            $g_total['sales_qty'][$models_data->month_year] = $models_data->sales_qty;
                                                        }
                                                        // Compute Grand total of sales amount 
                                                        if (isset($g_total['sales_amount'][$models_data->month_year])) {
                                                            $g_total['sales_amount'][$models_data->month_year] += $models_data->sales_qty * $models_data->price;
                                                        } else {
                                                            $g_total['sales_amount'][$models_data->month_year] = $models_data->sales_qty * $models_data->price;
                                                        }
                                                        // Compute Grand total of production quantity 
                                                        if (isset($g_total['prod_qty'][$models_data->month_year])) {
                                                            $g_total['prod_qty'][$models_data->month_year] += $models_data->prod_qty;
                                                        } else {
                                                            $g_total['prod_qty'][$models_data->month_year] = $models_data->prod_qty;
                                                        }
                                                        // Compute Grand total of production amount 
                                                        if (isset($g_total['prod_amount'][$models_data->month_year])) {
                                                            $g_total['prod_amount'][$models_data->month_year] += $models_data->prod_qty * $models_data->price;
                                                        } else {
                                                            $g_total['prod_amount'][$models_data->month_year] = $models_data->prod_qty * $models_data->price;
                                                        }
                                            ?>
                                                <td><?= $models_data->price ?></td>
                                                <td><?= $models_data->prod_qty ?></td>
                                                <td><?= $models_data->prod_qty * $models_data->price ?></td>
                                                <td><?= $models_data->sales_qty ?></td>
                                                <td><?= $models_data->sales_qty * $models_data->price ?></td>
                                                <td><?= $inv ?></td>
                                                <td><?= $inv * $models_data->price ?></td>
                                    <?php
                                                    endforeach;
                                                endforeach;
                                            endforeach;
                                    ?>
                                </tbody>
                                <tbody hidden>
                                    <tr>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td class='small' colspan='3'>Total</td>
                                        <?php
                                        $inv = 0;
                                        $models_datas = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$selectedDPRNO'  ")->result();
                                        foreach ($models_datas as $models_data) :
                                            $inv += $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] - $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year]; ?>

                                            <td class='h4'></td>
                                            <td class='h4'><?= $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year]  ?></td>
                                            <td class='h4'><?= $customer_total[$models_data->customer]['prod_amount'][$models_data->month_year]  ?></td>
                                            <td class='h4'><?= $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year]  ?></td>
                                            <td class='h4'><?= $customer_total[$models_data->customer]['sales_amount'][$models_data->month_year]  ?></td>
                                            <td class='h4'><?= $inv  ?></td>
                                            <td class='h4'><?= $inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year]  ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>

                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3">Grand Total</td>
                                <?php
                                $inv = 0;
                                $models_datas = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$selectedDPRNO' ORDER BY month_year_numerical ASC  ")->result();
                                foreach ($models_datas as $models_data) :
                                    $inv += $g_total['prod_qty'][$models_data->month_year] - $g_total['sales_qty'][$models_data->month_year]; ?>
                                    <td class='h4'></td>
                                    <td class='h4'><?= $g_total['prod_qty'][$models_data->month_year] ?></td>
                                    <td class='h4'><?= $g_total['prod_amount'][$models_data->month_year] ?></td>
                                    <td class='h4'> <?= $g_total['sales_qty'][$models_data->month_year] ?></td>
                                    <td class='h4'> <?= $g_total['sales_amount'][$models_data->month_year] ?></td>
                                    <td class='h4'> <?= $inv ?></td>
                                    <td class='h4'> <?= $invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year] ?></td>
                                <?php endforeach; ?>
                            </tr>

                        </table>
                    <?php } else {
                                echo "<center><b>NO DATA AVAILABLE</b></center>";
                            } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById("tbl_models");
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
</script>

<script>
    $(document).ready(() => {
        const table = document.querySelector("#tbl_models");
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
    })
</script>


<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>