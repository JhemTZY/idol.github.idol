<div class="content-wrapper" id="app">
<section class="content-header ">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> NCFL Plan Report
                    </h1>
                </li>
               <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/Prod_Sales_Amount/Prod_Sales_report" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-file-o"></i> PLAN</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/Prod_Sales_Amount/new_ncfl_actual_report" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-check-circle"></i> ACTUAL</a></li> 
                <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-info" style="color:white; " data-toggle="modal" data-target="#upload_plan_modal"> <i class="fa fa-upload"></i> Upload</a></li>
            </ol>
        </nav>
        <div class="row">
           <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                $success = $this->session->flashdata('success');
                $warning = $this->session->flashdata('warning');

                if (isset($error)) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } else if (isset($success)) { ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } else if (isset($warning)) { ?>
                    <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('warning'); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
    </section>

    <section class="content">


        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header ">
                        <h1> ACTUAL PROD/SALES </h1>
                        <div class="box-tools " style="width:150px;">
                            <label class="" for="">Month</label>

                            <select class="form-control " @change="get_actual_data" v-model="selected_month">
                                <?php
                                $begin = new DateTime(date("Y-01-01"));
                                $end = new DateTime(date("Y-12-31"));
                                $daterange = new DatePeriod($begin, new DateInterval('P1M'), $end);
                                foreach ($daterange as $date) :
                                ?>
                                    <option value="<?= $date->format("F-Y"); ?>"><?= $date->format("F-Y"); ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                            <!-- <button class="btn btn-lg btn-outline-dark btn-block" @click="get_actual_data">Submit</button> -->
                        </div>

                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-bordered" id="tbl_actual_data">
                            <thead>
                                <tr>
                                    <th colspan="2"></th>
                                    <th colspan="3">{{ selected_month }}</th>
                                </tr>
                                <tr>
                                    <td>Customer</td>
                                    <td>Model</td>
                                    <td>Actual Price</td>
                                    <td>Actual Production Quantity</td>
                                    <td>Actual Sales Quantity</td>
                                </tr>
                            </thead>
                            <tbody style="cursor:cell;">
                                <tr v-for="row in actual_data">
                                    <td>{{ row.customers_desc }}</td>
                                    <td>{{ row.models_desc }}</td>
                                    <td>{{ row.actual_price }}</td>
                                    <td>{{ row.actual_prod_qty }}</td>
                                    <td>{{ row.actual_sales_qty }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-------------------------------------------------------------------------
                           *** UPLOAD PLAN MODAL
    --------------------------------------------------------------------------->
    <div class="modal fade text-center mx-auto" id="upload_plan_modal">
        <div class="modal-dialog modal-md    modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h2 text-center"><b>UPLOAD ACTUAL DATA </b> </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="form-group text-center mx-auto">
                        <div class="box box-info" style="padding:15px ;">
                            <div class="box-header">
                                <div class="box-body px-5 py-5 ">
                                    <?php $this->load->helper("form"); ?>
                                    <form class="px-3 py-3" action="<?php echo base_url(); ?>index.php/Prod_Sales_Amount/upload_actual_data" method="post" enctype="multipart/form-data">
                                        Upload excel file :
                                        <input type="file" name="uploadFile" id="uploadFile" value="" style="display: inline-block; padding: 6px 12px; cursor:grab; " /><br><br>
                                        <input class="form-control btn btn-primary btn-lg" type="submit" name="submit" value="Upload" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------
                         UPLOAD PLAN MODAL ***
    --------------------------------------------------------------------------->
</div>

<script>
    $(document).ready(() => {
        setInterval(() => {
            const table = document.querySelector("#tbl_actual_data");
            let headerCell = null;
            for (let row of table.rows) {
                const firstCell = row.cells[0];
                if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
                    headerCell = firstCell;
                } else {
                    headerCell.rowSpan++;
                    firstCell.remove();
                    // firstCell.css("background-color:#fff");
                }
            }
        },10)
    })
</script> 

<script type="text/javascript" src="<?php echo base_url('assets/vuejs/vue.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/axios/axios.min.js'); ?>"></script>



<!-- <script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById("tbl_actual_data");
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
 -->

<!-- VUE JS -->
<script>
    var app = new Vue({
        el: '#app',
        data: {
            actual_data: '',
            selected_month: '',
            txt_random: '',
        },
        methods: {
            get_actual_data: function() {
                axios
                    .post("<?= base_url() ?>index.php/Prod_Sales_Amount/get_actual_data", {
                        "selectedMonth": this.selected_month,
                    })
                    .then(function(response) {
                        // console.log(response);
                        if (response.data.length > 0) {
                            app.actual_data = response.data;
                        } else {
                            app.actual_data = '';
                        }
                        // console.log(response.data);
                    })

                // REMOVE DUPLICATES IN TABLE
                setTimeout(() => {
                    const table = document.querySelector("#tbl_actual_data");
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
                }, 100)

            },

        },
        created: function() {
            this.get_actual_data();
        }
    });
</script>