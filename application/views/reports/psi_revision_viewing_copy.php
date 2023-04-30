<div class="content-wrapper" id="psimerged">
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-file-o"></i> PSI
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/PSI_EDIT_BETA"
                        class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit
                        Mode</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/psi_revision_viewing"
                        class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-eye"></i> Viewing
                        Mode</a></li>
            </ol>
        </nav>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success" style="padding:3rem;">
                    <div class="box-header" style="margin-bottom:5rem ;">
                        <h3 class="box-title">PSI {{ '('+selected_revision+')' }} </h3>
                        <div class="box-tools">
                            <div class="input-group">
                                <select type="text" class="form-control" v-model="selected_revision">
                                    <option :value="null" disabled>Select Revision</option>
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
                                <div class="input-group-btn">
                                    <button class="btn btn-lg btn-dark" @click="generateMergedPSI"> <i
                                            class="fa fa-search"></i> </button>
                                </div>
                            </div>
                            <select class="form-control" v-model="divider">
                                <option value="1000">Thousands</option>
                                <option value="10000000">Millions</option>
                                <option value="1">Ones</option>
                            </select>
                            <button v-if="MonthsInRevision.length>0" class="btn btn-success btn-block"
                                onclick="exportTableToExcel('tbl_models', 'PSI' )">Export to Excel </button>

                        </div>
                    </div>
                    <div class="box-body">
                        <hr>
                        <div class="table-responsive freeze-table">
                            <table class="table table-bordered table-hover " id="tbl_models">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Item Code</th>
                                        <th>Model</th>
                                        <th colspan="3" v-for="months in MonthsInRevision"> {{ months.month_year }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <template v-for="months in MonthsInRevision">
                                            <th class="small"> Production Quantity </th>
                                            <th class="small"> Sales Quantity </th>
                                            <th class="small"> Inventory </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(psi,customer) in psiMerged">
                                        <tr>
                                            <td :rowspan="ObjectLength(psiMerged[customer])+1"> <b> {{ customer }} </b>
                                            </td>
                                        </tr>
                                        <tr v-for="(psi,models) in psiMerged[customer]">
                                            <td> <b>{{psi.item_code}}</b> </td>
                                            <td> <b>{{ models }}</b> </td>
                                            <template v-for="months in MonthsInRevision">
                                                <td style="text-align:right;">
                                                    {{ number_format(psiMerged[customer][models][months.month_year].mergedProdQty / divider) }}
                                                </td>
                                                <td style="text-align:right;">
                                                    {{ number_format(psiMerged[customer][models][months.month_year].mergedSalesQty / divider) }}
                                                </td>
                                                <td style="text-align:right;">
                                                    {{ number_format(psiMerged[customer][models][months.month_year].mergedInventory / divider) }}
                                                </td>
                                            </template>
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

    function number_format(number) {
        let formatCurrency = new Intl.NumberFormat('en-US');
        var data = formatCurrency.format(number)
        return data;
    }
</script>

<script>
    var app = new Vue({
        el: '#psimerged',
        data: {
            MonthsInRevision: [],
            distinctModels: [],
            distinctCustomers: [],
            psiMerged: [],
            divider: 1000,
            /* POST DATA */
            selected_revision: '<?= $selectedDPRNO; ?>',
        },
        methods: {
            generateMergedPSI: function () {
                axios.post("<?= base_url() ?>index.php/task/generateMergedPSI", {
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
            },

        },
        created: function () {
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