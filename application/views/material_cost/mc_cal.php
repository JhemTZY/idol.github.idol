<div class="content-wrapper" id="mc_cal">
    <section class="content-header ">
       
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success" style="padding:3rem;">
                    <div class="box-header" style="margin-bottom:5rem ;">
                        <h3 class="box-title"> MATERIAL COST </h3>
                        <div class="box-tools">
                           
                            <button v-if="MonthsInRevision.length>0" class="btn btn-success btn-block" onclick="exportTableToExcel('tbl_models', 'PSI' )">Export to Excel </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive freeze-table">
                            <table class="table table-bordered table-hover " id="tbl_models">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Model</th>
                                        <th colspan="4" v-for="months in MonthsInRevision"> {{ months.month_year }} </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <template v-for="months in MonthsInRevision">
                                            <th class="small"> Production Quantity </th>
                                            <th class="small"> Sales Quantity </th>
                                              <th class="small"> MTL COST </th>
                                            <th class="small"> Inventory </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(psi,customer) in psiMerged">
                                        <tr>
                                            <td :rowspan="ObjectLength(psiMerged[customer])+1"> <b> {{ customer }} </b></td>
                                        </tr>
                                        <tr v-for="(psi,models) in psiMerged[customer]">
                                            <td> <b>{{ models }}</b> </td>
                                           <!--  <template v-for="months in MonthsInRevision">
                                                <td style="text-align:right;"> {{ psiMerged[customer][models][months.month_year].mergedProdQty }} </td>
                                                <td style="text-align:right;"> {{ psiMerged[customer][models][months.month_year].mergedSalesQty }} </td>
                                                <td style="text-align:right;"> {{ psiMerged[customer][models][months.month_year].mergedInventory }} </td>
                                            </template> -->
                                        </tr>
                                    </template>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

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
    var app = new Vue({
        el: '#mc_cal',
        data: {
            MonthsInRevision: [],
            distinctModels: [],
            distinctCustomers: [],
            psiMerged: [],

            /* POST DATA */
            selected_revision: '<?=$selectedDPRNO;?>',
        },
        methods: {
            generateMergedPSI: function() {
                axios.post("<?= base_url() ?>index.php/Material_cost/Material_cost_per_quarter", {
                        SelectedRevision: this.selected_revision,
                    })
                    .then((response) => {
                        console.log(response.data);
                        app.MonthsInRevision = response.data.months;
                        app.distinctModels = response.data.distinctModels;
                        app.distinctCustomers = response.data.distinctCustomers;
                        app.psiMerged = response.data.psiMergedData;
                        freezetable();
                    })
            }
        },
        created: function() {
            this.generateMergedPSI();
        }
    });
</script>


<script>
    setInterval(() => {
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
    }, 50)
</script>