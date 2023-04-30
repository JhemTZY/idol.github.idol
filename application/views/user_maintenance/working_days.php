<div class="content-wrapper" id="working_day">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: yellow;">
                        <i class="fa fa-cog fa-spin" aria-hidden="true"></i> PSI Maintenance
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/home" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/add_model" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-android-add-circle"></i> Add</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/edit_model" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Model </a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/approvers" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-users fa-lg"></i> Approvers</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/all_revisions" class="btn btn-lg btn-outline-warning" style="color:white"><i class="ion-edit"></i> Revisions</a></li>
   <?php
              if ($role_text == "NCFL PLAN (PSI)" || $role_text == "System Administrator" ) {
              ?>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/PSI/psi_edit" class="btn btn-lg btn-dark" style="color:white"><i class="ion-document"></i> PSI</a></li>
                  <?php } ?>

                  
                <?php
              if ($role_text == "BU HEAD" ) {
              ?>
              <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/Psi_BU/psi_edit" class="btn btn-lg btn-dark" style="color:white"><i class="ion-document"></i> PSI</a></li>
          <?php } ?>
            </ol>
        </nav>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a href="<?= base_url() ?>index.php/User_Maintenance/working_days" class="btn btn-lg btn-outline-success" style="color:white">WORKING DAYS </a>
                     <a href="<?= base_url() ?>index.php/Category/Category" class="btn btn-lg btn-outline-success" style="color:white">CATEGORY</a>
                    <a href="<?= base_url() ?>index.php/User_Maintenance/update_model_price" class="btn btn-lg btn-outline-success" style="color:white">PRICE UPDATE </a>
                    <a href="<?= base_url() ?>index.php/User_Maintenance/edit_model" class="btn btn-lg btn-outline-success" style="color:white">EDIT MODELS </a>
                </div>
            </div>
        </div>
        <div class="row">
           <div class="col-md-12">
                <div class="box text-center" style="display: inline-block;">
                    <div class="box-body">
                        <h1>Working Days Maintenance</h1>
                        <div class="table-responsive freeze-table">
                            <table class="table table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Month Year</th>
                                        <th style="text-align: center;">Working Days</th>
                                        <th style="text-align: center;">Type</th>
                                        <th style="text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="months in MonthsInRevision">
                                        <tr>
                                            <td rowspan="1" style="font-weight: bold;">{{ months.month_year }}</td>
                                            <td><input type="text" class="form-control text-center" :value="months.working_days" @keyup="working_days_updates(months.month_year, $event.target.value, 'WorkingDays')"></td>
                                            <td>
                                                <select class="form-control"   @change="plans_or_actual(months.month_year, $event.target.value, 'IsActual')">
                                                     <option disabled selected>{{months.isActual}}</option>
                                                    <option value="PLAN">PLAN</option>
                                                    <option value="ACTUAL">ACTUAL</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control"   @change="hidden_or_not(months.month_year, $event.target.value, 'IsHidden')">
                                                     <option disabled selected>{{months.isHidden}}</option>
                                                    <option value="Hidden">Hidden</option>
                                                    <option value="Display">Display</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>    
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
</section>



<script>
    var app = new Vue({
        el: '#working_day',
        data: {
            MonthsInRevision: [],
        },
        methods: {
            Mc_per_Q: function() {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/working_month", {

                    })
                    .then((response) => {
                        console.log(response.data);
                        app.MonthsInRevision = response.data.months;


                    })
            },
            working_days_updates: function(month_year, value, column) {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/working_updates", {
                    SelectedCustomers: app.selected_customers,
                    month_year: month_year,
                    value: value,
                    column: column
                }).then((response) => {
                    console.log(response.data);
                    this.Mc_per_Q();
                })
            },
             plans_or_actual: function(month_year, value, column) {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/plans_or_actual", {
                    SelectedCustomers: app.selected_customers,
                    month_year: month_year,
                    value: value,
                    column: column
                }).then((response) => {
                    console.log(response.data);
                    this.Mc_per_Q();
                })
            },

              hidden_or_not: function(month_year, value, column) {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/hidden_or_not", {
                    SelectedCustomers: app.selected_customers,
                    month_year: month_year,
                    value: value,
                    column: column
                }).then((response) => {
                    console.log(response.data);
                    this.Mc_per_Q();
                })
            },


            GetRevisionData: function() {
                axios.post("<?= base_url() ?>index.php/PSI/GetCurrentRevisionData", {
                    SelectedRevision: this.current_revision,
                }).then((response) => {
                    console.log(response.data);
                    app.CurrentRevisionData = response.data.CurrentRevisionData;
                })
            },

        },

        created: function() {
            this.Mc_per_Q();
            this.GetRevisionData();
        }
    });
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

<script type="text/javascript">
    $(document).ready(function() {

        freezetable();

    });
</script>


<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>