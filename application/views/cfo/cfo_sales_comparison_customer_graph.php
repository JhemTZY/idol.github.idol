<div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-line-chart" aria-hidden="true"></i> CFO |</small>
                        Sales Comparison</small>
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/CFO/cfo_sales_comparison" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-table"></i> Excel Mode</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/CFO/cfo_sales_comparison_customer_graph" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-bar-chart"></i> Graph Mode</a></li>
            </ol>
        </nav>
    </section>

    <section class="content">
        <!-- FILTER -->
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-warning">
                    <div class="box-header">
                        <label class="h3"> </label>
                        <a href="<?= base_url() ?>index.php/CFO/cfo_sales_comparison_customer_graph" class="btn btn-lg btn-outline-info"> Customer</a>
                        <a href="<?= base_url() ?>index.php/CFO/cfo_sales_comparison_models_graph" class="btn btn-lg btn-outline-info"> Model</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- GRAPHS -->
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-secondary">
                    <div class="box-header">
                        <h3 class="h1"><b> CUSTOMER CHART </b></h3>
                        <div class="box-tools">
                             <select class="form-control" v-model="divider">
                                <option value="1000">Thousands</option>
                                <option value="10000000">Millions</option>
                                <option value="1">Ones</option>
                            </select>
                            <hr>
                            <label>Chart Size</label>
                            <select class="btn btn-outline-secondary" v-model="chart_size">
                                <option value="col-lg-8">Small</option>
                                <option value="col-lg-10">Medium</option>
                                <option value="col-lg-12">Large</option>
                            </select>
                            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#filter_modal">Filter</button>
                            
                            <div class="input-group">
                                <button class="btn btn-lg btn-info" style="width:49%; padding:2px; " id="btn_export_sales_comparison_pdf"> <i class="fa fa-download"></i> PDF </button>
                                <label style="color:white;">|</label>
                                <!--MAGIC-->
                                <button class="btn btn-lg btn-info" style="width:49%; padding:2px; " id="btn_export_sales_comparison_excel"> <i class="fa fa-download"></i> Excel </button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 50px;" id="cfo_sales_chart">
                        <div class="row">
                            <div :class="chart_size" style="border:dotted; width:100%; height:100%; margin:auto;">
                                <canvas id="cnv_sales_chart"></canvas>
                                <div class="table-responsive" id="tbl_sales_comparison">
                                    <table class="table table-hover" v-if="selected_customers.length > 0" style="width:100%;" id="tbl_sales_comparison">
                                        <thead>
                                            <tr>
                                                <th colspan='2'>Customer</th>
                                                <th colspan='3' v-for="months in month_years"> {{ months }} </th>
                                            </tr>
                                        </thead>

                                        <tbody v-for="customer in SelectedCustomer">
                                            <tr>
                                                <td rowspan="3"> {{ customer.customers_desc }} </td>
                                            </tr>
                                            <tr>
                                                <td>Quantity</td>
                                                <template v-for="months in month_years">
                                                    <td>
                                                        <span style="color:white;" hidden>
                                                            {{ month = months.split(' ') }}
                                                            {{ customersName = customer.customers_desc }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty!=null'>
                                                            <small> Actual</small>
                                                            <hr>
                                                            {{ total_sales_customer[customersName][month].total_sales_qty }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty==null'>
                                                            <small> {{ app.selectedRevision }} </small>
                                                            <hr>
                                                            {{ number_format(total_sales_customer[customersName][month][selectedRevision].total_sales_qty / divider) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span style="color:white;" hidden>
                                                            {{ month = months.split(' ') }}
                                                            {{ customersName = customer.customers_desc }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty!=null'>
                                                            <small> Actual</small>
                                                            <hr>
                                                            {{ total_sales_customer[customersName][month].total_sales_qty }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty==null'>
                                                            <small><?= $selectedDPRNO ?></small>
                                                            <hr>
                                                            {{ number_format(total_sales_customer[customersName][month]['<?= $selectedDPRNO; ?>'].total_sales_qty / divider) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small>Difference</small>
                                                        <hr>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty!==null'>
                                                            {{ total_sales_customer[customersName][month].total_sales_qty - total_sales_customer[customersName][month].total_sales_qty }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty==null' style="color:white;" hidden>
                                                            {{ difference = total_sales_customer[customersName][month][selectedRevision].total_sales_qty - total_sales_customer[customersName][month]['<?= $selectedDPRNO; ?>'].total_sales_qty }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_qty==null'>
                                                            <template v-if='difference < 0' hidden>
                                                                <b style="color:red;"> {{number_format( Math.abs(difference)/ divider) }} </b>
                                                            </template>
                                                            <template v-if='difference >= 0' hidden>
                                                                <b style="color:green;"> {{ number_format(Math.abs(difference)/ divider) }} </b>
                                                            </template>
                                                        </span>
                                                    </td>
                                                </template>

                                            </tr>
                                            <tr>
                                                <td>Amount</td>
                                                <template v-for="months in month_years">
                                                    <td>
                                                        <span style="color:white;" hidden>
                                                            {{ month = months.split(' ') }}
                                                            {{ customersName = customer.customers_desc }}
                                                        </span>
                                                        <span>
                                                            {{ total_sales_customer[customersName][month].total_sales_amount }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_amount==null'>
                                                            {{ number_format(total_sales_customer[customersName][month][selectedRevision].total_sales_amount / divider) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span style="color:white;" hidden>
                                                            {{ month = months.split(' ') }}
                                                            {{ customersName = customer.customers_desc }}
                                                        </span>
                                                        <span>
                                                            {{ total_sales_customer[customersName][month].total_sales_amount }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_amount==null'>
                                                            {{ number_format(total_sales_customer[customersName][month]['<?= $selectedDPRNO; ?>'].total_sales_amount / divider) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small>Difference</small>
                                                        <hr>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_amount!==null'>
                                                            {{ total_sales_customer[customersName][month].total_sales_amount - total_sales_customer[customersName][month].total_sales_amount }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_amount==null' style="color:white;" hidden>
                                                            {{ difference = total_sales_customer[customersName][month][selectedRevision].total_sales_amount - total_sales_customer[customersName][month]['<?= $selectedDPRNO; ?>'].total_sales_amount }}
                                                        </span>
                                                        <span v-if='total_sales_customer[customersName][month].total_sales_amount==null'>
                                                            <template v-if='difference < 0' hidden>
                                                                <b style="color:red;"> {{ number_format(Math.abs(difference) / divider) }} </b>
                                                            </template>
                                                            <template v-if='difference >= 0' hidden>
                                                                <b style="color:green;"> {{ number_format(Math.abs(difference) / divider) }} </b>
                                                            </template>
                                                        </span>
                                                    </td>
                                                </template>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade text-center mx-auto" id="filter_modal">
        <div class="modal-dialog modal-lg    modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h2 text-center"><b>Filters </b> </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-warning">
                                <div class="box-header" style="padding:10px ;"></div>
                                <div class="row" style="margin:0px;">
                                    <div class="col-lg-3" style="box-shadow: 3px 3px 5px #aaaaaa; padding:20px; margin:10px;">
                                        <div>
                                            <label for="">YEAR & QUARTER</label>
                                            <select style='cursor:grab' class="form-control" v-model="input_year">
                                                <option value="">Select Year</option>
                                                <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                                                <option value="<?= date('Y', strtotime('+1 year')) ?>"><?= date('Y', strtotime('+1 year')) ?></option>
                                                <option value="<?= date('Y', strtotime('+2 year')) ?>"><?= date('Y', strtotime('+2 year')) ?></option>
                                            </select>
                                        </div>
                                        <div class="text-left" v-if="input_year>0">
                                            <div v-for="x in 4">
                                                <label>Quarter {{ x }} </label>
                                                <input type="checkbox" :value="x" v-model="selected_quarters" style="cursor:grab;width:20px; height:20px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3" style="box-shadow: 3px 3px 5px #aaaaaa; padding:20px; margin:10px;">

                                        <label for=""> SELECTED REVISION</label>
                                        <select style='cursor:grab' class="form-control " v-model="selectedRevision">
                                            <option value="">Select Revision</option>
                                            <!-- <option value="<?= $selectedDPRNO ?>"> <?= $selectedDPRNO ?></option> -->
                                            <?php
                                            foreach ($revisions as $rev) :
                                            ?>
                                                <option value="<?= $rev->revision ?>"> <?= $rev->revision ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-5" style="box-shadow: 3px 3px 5px #aaaaaa; padding:20px; margin:10px;">
                                        <label for="">Customers <span> <button class="btn btn-sm btn-info" @click="select_all_customers"><i class="fa fa-check-circle fa-danger"></i> Check All</button> </span> </label>
                                        <div class="table-responsive" v-for="row in customers">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <th style="width:10%;">
                                                        <input type="checkbox" :value="row.customers_id" v-model="selected_customers" style="cursor:grab;width:20px; height:20px;">
                                                    </th>
                                                    <th class="text-center">
                                                        <label> {{ row.customers_desc }} </label>
                                                    </th>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <!-- Wag burahin, magic yang label haha -->
                                        <label for="" style="color:white;">.</label>
                                        <button class="btn btn-lg btn-success btn-block" @click="fetch_salesCompa_graph" data-dismiss="modal"> Submit </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?= base_url() ?>assets2/plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url() ?>assets2/plugins/chart.js/chariz.min.js"></script>

<!-- JSPDF -->
<script src="<?= base_url() ?>assets2/js2/html2canvas/html2canvas.js"></script>
<script src="<?= base_url() ?>assets2/js2/js2pdf/jspdf.min.js"></script>

<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        console.table(tableHTML);
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

<script>
    $(document).ready(() => {
        $("#btn_export_sales_comparison_pdf").click(() => {
            var doc = new jsPDF('p', 'mm', 'letter');
            html2canvas(document.querySelector("#cfo_sales_chart")).then(canvas => {
                var imgData = canvas.toDataURL("image/jpeg", 1.0);
                const imgProps = doc.getImageProperties(imgData);
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                doc.addImage(imgData, 'JPG', 0, 0, pdfWidth, pdfHeight);
                doc.save('CFO-SalesComparison-Graph.pdf');
            });

        });
        $("#btn_export_sales_comparison_excel").click(() => {
            exportTableToExcel("tbl_sales_comparison", "CFO Sales Comparison");
        });
    })
</script>


<script>
    $(document).on('keydown', function(e) {
        // You may replace `m` with whatever key you want
        if ((e.metaKey || e.ctrlKey) && (String.fromCharCode(e.which).toLowerCase() === '1')) {
            $("#filter_modal").removeClass('fade');
            $("#filter_modal").modal('show');
        }
    });
</script>


<!-- VUE JS SCRIPT FOR THIS PAGE ONLY -->
<script>
    let chart = new Chart('cnv_sales_chart');
    var app = new Vue({
        el: '#app',
        data: {
            // FILTERS | Clicks of Check all customers.
            pindot: 1,
            // BOX TOOLS | Customize Chart Size
            chart_size: 'col-lg-12',

            // *** POST DATA 
            selected_quarters: [],
            selected_customers: [],
            selected_models: [],
            input_year: '',
            selectedRevision: '',
             divider: 1000,
            // POST DATA ***

            /*---------------------------------------------------------------------
                        *** GRAPH DATA 
            -----------------------------------------------------------------------*/
            month_years: '',
            customers: '',
            SelectedCustomer: '',
            total_sales_customer: '',
            /*---------------------------------------------------------------------
                            GRAPH DATA ***
            -----------------------------------------------------------------------*/
        },
        methods: {
            get_customer: function() {
                axios.post("<?= base_url() ?>index.php/CFO/get_customers", {})
                    .then((response) => {
                        app.customers = response.data.customers;
                    });
            },
            fetch_salesCompa_graph: () => {

                axios.post("<?= base_url() ?>index.php/CFO/fetch_salesCompa_Customer_Graph", {
                    selectedQuarter: app.selected_quarters,
                    inputYear: app.input_year,
                    SelectedCustomers: app.selected_customers,
                    selectedRevision: app.selectedRevision,

                }).then((response) => {
                    console.log(response.data);

                    app.SelectedCustomer = response.data.selected_customer;
                    app.month_years = response.data.monthYear;
                    app.total_sales_customer = response.data.total_sales_customer;

                    let salesQuantity = [];
                    let salesAmount = [];
                    for (x in response.data.grandTotal_sales_customer) {
                        // console.log(x);
                        if (response.data.grandTotal_sales_customer[x].Sales_Quantity != null) {
                            salesQuantity.push(response.data.grandTotal_sales_customer[x].Sales_Quantity);
                        } else {
                            salesQuantity.push(response.data.grandTotal_sales_customer[x][app.selectedRevision].Sales_Quantity);
                            salesQuantity.push(response.data.grandTotal_sales_customer[x]["<?= $selectedDPRNO ?>"].Sales_Quantity);
                        }

                        if (response.data.grandTotal_sales_customer[x].Sales_Amount != null) {
                            salesAmount.push(response.data.grandTotal_sales_customer[x].Sales_Amount);
                        } else {
                            salesAmount.push(response.data.grandTotal_sales_customer[x][app.selectedRevision].Sales_Amount);
                            salesAmount.push(response.data.grandTotal_sales_customer[x]["<?= $selectedDPRNO ?>"].Sales_Amount);

                        }
                    }
                    app.generate_chart('cnv_sales_chart', salesAmount, salesQuantity);
                    $("#tbl_sales_comparison").addClass("freeze-table");
                })
            },
            select_all_customers: () => {
                app.pindot += 1;
                if (app.pindot % 2 === 0) {
                    var damiNgCustomer = app.customers.length;
                    app.selected_customers = [];
                    for (x = 0; x < damiNgCustomer; x++) {
                        app.selected_customers.push(app.customers[x].customers_id);
                    }
                } else {
                    app.selected_customers = [];
                }

            },
            generate_chart: (chartId, salesAmount, salesQuantity) => {
                /*------------------------------------------------------------------------------------------------
                                UNDER DEVELOPMENT | NEW CHART | STACKED CHART
                ------------------------------------------------------------------------------------------------*/
                customerData = [];

                if (app.SelectedCustomer.length >= app.customers.length) {
                    // OVERALL QUANTITY BASED ON THE SELECTED CUSTOMERS PER QUARTER
                    var oa_qty = {};
                    oa_qty['label'] = "OVERALL QTY";
                    oa_qty['borderColor'] = "#063970";
                    oa_qty['borderWidth'] = 4;
                    oa_qty['data'] = salesQuantity.filter(Number);
                    oa_qty['order'] = 1;
                    oa_qty['type'] = 'line';
                    oa_qty['tension'] = 0;
                    oa_qty['fill'] = false;
                    oa_qty['yAxisID'] = 'y1';

                    // OVERALL AMOUNT BASED ON THE SELECTED CUSTOMERS PER QUARTER
                    var oa_amount = {};
                    oa_amount['label'] = "OVERALL AMOUNT";
                    oa_amount['borderColor'] = "#75975e";
                    oa_amount['borderWidth'] = 4;
                    oa_amount['data'] = salesAmount.filter(Number);
                    oa_amount['order'] = 1;
                    oa_amount['type'] = 'line';
                    oa_amount['tension'] = 0;
                    oa_amount['fill'] = false;
                    oa_amount['yAxisID'] = 'y1';

                    customerData.push(oa_qty, oa_amount)
                }

                for (customer in app.SelectedCustomer) {
                    var data = {};
                    customerNames = app.SelectedCustomer[customer].customers_desc;
                    data['label'] = app.SelectedCustomer[customer].customers_code + ' AMT';
                    let sales_amount = [];
                    for (month in app.month_years) {
                        months = app.month_years[month];
                        if (app.total_sales_customer[customerNames][months].total_sales_qty == null) {
                            sales_amount.push(app.total_sales_customer[customerNames][months][app.selectedRevision].total_sales_amount);
                            sales_amount.push(app.total_sales_customer[customerNames][months]['<?= $selectedDPRNO ?>'].total_sales_amount);
                        } else {
                            sales_amount.push(app.total_sales_customer[customerNames][months].total_sales_amount);
                        }
                    }
                    data['data'] = sales_amount;
                    data['backgroundColor'] = app.SelectedCustomer[customer].customer_color_A;
                    data['order'] = 2;
                    data['type'] = 'bar';
                    data['stack'] = 1;
                    data['borderRadius'] = 5;
                    customerData.push(data);
                }

                for (customer in app.SelectedCustomer) {
                    var data = {};
                    customerNames = app.SelectedCustomer[customer].customers_desc;
                    data['label'] = app.SelectedCustomer[customer].customers_code + ' AMT2';
                    let sales_amount = [];
                    for (month in app.month_years) {
                        months = app.month_years[month];
                        if (app.total_sales_customer[customerNames][months].total_sales_qty == null) {
                            sales_amount.push('');
                            sales_amount.push('');
                        } else {
                            sales_amount.push(app.total_sales_customer[customerNames][months].total_sales_amount);
                        }
                    }
                    data['data'] = sales_amount;
                    data['backgroundColor'] = app.SelectedCustomer[customer].customer_color_A;
                    data['order'] = 2;
                    data['type'] = 'bar';
                    data['stack'] = 2;
                    data['borderRadius'] = 5;
                    customerData.push(data);
                }

                if (app.SelectedCustomer.length < app.customers.length) {
                    for (customer in app.SelectedCustomer) {
                        var salesQty = {};
                        customerNames = app.SelectedCustomer[customer].customers_desc;
                        salesQty['label'] = customerNames + ' QTY';
                        salesQty['borderColor'] = app.SelectedCustomer[customer].customer_color_B;
                        salesQty['borderWidth'] = 3;
                        let sales_quantity = [];
                        for (month in app.month_years) {
                            months = app.month_years[month];
                            if (app.total_sales_customer[customerNames][months].total_sales_qty == null) {
                                sales_quantity.push(app.total_sales_customer[customerNames][months][app.selectedRevision].total_sales_qty);
                                sales_quantity.push(app.total_sales_customer[customerNames][months]['<?= $selectedDPRNO ?>'].total_sales_qty);
                            } else {
                                sales_quantity.push(app.total_sales_customer[customerNames][months].total_sales_qty);
                            }
                        }
                        salesQty['data'] = sales_quantity;
                        salesQty['order'] = 1;
                        salesQty['type'] = 'line';
                        salesQty['tension'] = 0;
                        salesQty['fill'] = false;
                        salesQty['yAxisID'] = 'y1';

                        customerData.push(salesQty);
                    }
                }

                var ChartDataCount = customerData[0].data.length;
                var xValues = [];

                for (customer in app.SelectedCustomer) {
                    customerNames = app.SelectedCustomer[customer].customers_desc;
                    for (month in app.month_years) {
                        months = app.month_years[month];
                        if (app.total_sales_customer[customerNames][months].total_sales_qty == null) {
                            xValues.push(app.selectedRevision, "<?= $selectedDPRNO; ?>");
                            // xValues.splice(ChartDataCount);

                        }
                        if (app.total_sales_customer[customerNames][months].total_sales_qty !== null) {
                            xValues.push('Actual');
                            // xValues.splice(ChartDataCount);
                        }
                    }
                    xValues.splice(ChartDataCount);
                    xValues.splice(ChartDataCount);
                }

                console.table(customerData);

                salesChartData = {
                    labels: xValues,
                    datasets: customerData
                };

                salesChart_options = {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            position: 'left',
                        },
                        y1: {
                            stacked: false,
                            position: 'right',
                        }
                    },
                    title: {
                        display: true,
                        text: "SALES COMPARISON GRAPH"
                    }
                };
                let cnv_SalesChart_Data = {
                    type: "bar",
                    data: salesChartData,
                    options: salesChart_options
                };

                // SALES CHART DESTROY THEN INITIALIZE
                chart.destroy();
                chart = new Chart(chartId, cnv_SalesChart_Data);
            },

        },
        created: function() {
            this.get_customer();
        }
    });
</script>