<!-- *** Content wrapper --> 
<div class="content-wrapper" id="psiEdit" @click="PageLoad">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa ion-ios-list" aria-hidden="true"></i> NCFL Plans
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/PSI_EDIT_BETA" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit Mode</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/add" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-folder-o"></i> Create Plan</a></li>
                <?php
                $RevStatus = $this->db->query("SELECT status FROM tbl_revision WHERE revision_date='$selectedDPRNO' ")->row()->status;
                if ($RevStatus !== "Pending" && $RevStatus !== "Rejected") {
                ?>
                    <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-warning" style="color:white; " data-toggle="modal" data-target="#create_new_revision_modal"> <i class="fa fa-paperclip"></i> New Revision</a></li>
                <?php } else { ?>
                    <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-warning disabled" style="color:white; "> <i class="fa fa-paperclip"></i> New Revision</a></li>
                <?php } ?>

                <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-info" style="color:white; " data-toggle="modal" data-target="#generate_upload_file"> <i class="fa fa-download"></i> Download</a></li>
                <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-info" style="color:white; " data-toggle="modal" data-target="#upload_plan_modal"> <i class="fa fa-upload"></i> Upload</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/home" class="btn btn-lg btn-dark" style="color:white; "> <i class="fa fa-gear"></i> Maintenance</a></li>


            </ol>
        </nav>
    </section>
    <section class="content">
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
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    <button class="btn btn-info btn-md" id="btn_ViewPSIApprovers"> <i class="fa fa-users"></i> <small> APPROVERS STATUS</small> </button>
                    <button class="btn btn-info btn-md" id="btn_submit_psi"> <i class="fa fa-envelope"></i> <small> SUBMIT PSI</small> </button>
                    <a href="<?= base_url() ?>index.php/task/psi_revision_viewing" class="btn btn-success btn-md " type="submit"> <i class="fa fa-eye"></i> VIEW PSI </a>

                </div>
            </div>
            <br>
            <div class="box-header">
                <div class="row" id="psi_approvers_badge">
                    <?php
                    $approvers = $this->db->query(
                        "SELECT tbl_approver.approver_id,header,name,role,hierarchy_order,tbl_approved_psi.status, tbl_revision.revision_date ,tbl_approved_psi.remarks
                        FROM tbl_approver 
                        INNER JOIN tbl_users 
                        ON tbl_approver.approver_id=tbl_users.userId 
                        INNER JOIN tbl_roles
                        ON tbl_users.roleId=tbl_roles.roleId
                        INNER JOIN tbl_approved_psi
                        ON tbl_approver.approver_id = tbl_approved_psi.approver_id
                        INNER JOIN tbl_revision
                        ON tbl_approved_psi.rev_id=tbl_revision.ID
                        WHERE documentType='PSI'
                        AND tbl_revision.revision_date='$selectedDPRNO'
                        ORDER BY hierarchy_order ASC
                         "
                    )->result();
                    foreach ($approvers as $approver) :
                    ?>
                        <div class="col-md-4">
                            <?php
                            if ($approver->status == 1) {
                                echo "<div class='info-box bg-green'> <span class='info-box-icon'><i class='fa fa-thumbs-up'></i></span>";
                            } else if ($approver->status == "Rejected") {
                                echo "<div class='info-box bg-red'> <span class='info-box-icon'><i class='fa fa-thumbs-down'></i></span>";
                            } else {
                                echo "<div class='info-box bg-aqua'> <span class='info-box-icon'><i class='fa fa-circle-o'></i></span>";
                            }
                            ?>

                            <div class="info-box-content">
                                <span class="info-box-text"><?= $approver->header; ?>
                                    <?php if (!empty($approver->remarks)) { ?>
                                        <a href="#message" data-toggle="tooltip" data-placement="top" title="<?= $approver->remarks; ?>"><i class='fa fa-envelope'></i></a></span>
                            <?php } ?>
                            <span class="info-box-number"><?= $approver->name; ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <?= $approver->role; ?>
                            </span>
                            </div>
                        </div>
                </div>
            <?php
                    endforeach;
            ?>
            </div>


            <hr>
            <div class="box-header ">
            <div class="box-body">
                        <div class="table-responsive freeze-table">
                            <table class="table table-bordered" id="tbl_models" style="color:black;">
                                <thead>
                                    <tr>
                                        <th class="text-center" colspan="3">No. of Working Days</th>
                                        <th class="text-center" colspan="3" v-for="month in Months"> {{ month?.working_days }} Days </th>
                                    </tr>
                                    <tr style="background-color:PaleGreen;">
                                        <th class="text-center" rowspan="2">Customer</th>
                                        <th class="text-center" rowspan="2">Item No.</th>
                                        <th class="text-center" rowspan="2">Model</th>
                                        <th class="text-center" colspan="3" v-for="month in Months"> {{ month?.month_year }} </th>
                                    </tr>
                                    <tr style="background-color:PaleGreen;">
                                        <!-- <th colspan="3"></th> -->
                                        <template v-for="month in Months">
                                            <th class="text-center"> <b> PRICE </b> </th>
                                            <th class="text-center"> <b> PROD QTY</b> </th>
                                            <!-- Sales -->
                                                       
                                            <!-- Sales -->
                                            <th class="text-center"> <b> PROD AMOUNT</b> </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(modelsID,customer) in psiData">
                                        <tr>
                                            <td :rowspan="ObjectLength(psiData[customer])+1"> <b> {{ customer.substring(customer.indexOf('/'),0) }} </b> </td>
                                        </tr>
                                        <template v-for="models in modelsID">
                                            <template v-if="models.modelDT.cat_code =='MP'">
                                                <tr>
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td> <b> {{ models.modelDT.models_desc }} <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </b>
                                                      
                                                    </td>
                                                     <template v-for="month in Months">
                                                           <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;" class="text-right">{{models[month?.month_year]?.price}}</td>
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> {{ number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{ number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                        <!-- PROD -->
                                                       
                                                        <td style="background-color:white;" class="text-right">{{number_format(models[month?.month_year]?.prod_amount)}}</td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.inventory) }} </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;" class="text-right">  {{number_format(models[month?.month_year]?.prod_amount)}} </td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
               
                </div>
        </div>
    </section>


</div>
<!-- Content wrapper *** Dont Remove, magkakaroon ng whitespace sa baba ng sidebar -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>




<script>
    var app = new Vue({
        el: '#psiEdit',
        data: {
            Months: [],
            AllModels: [],
            customers: [],
            psiData: [],

            CurrentRevisionData: [],
            selected_revision: '<?= $selectedDPRNO; ?>',
             selected_months: '',
        },
        methods: {
            GeneratePSIedit: function() {
                axios.post("<?= base_url() ?>index.php/PSI_Viewing/GeneratePSIedit", {
                        SelectedRevision: this.selected_revision,
                        SelectedMonths: this.selected_months,
                    })
                    .then((response) => {
                        console.log(response.data);
                        app.customers = response.data.customers;
                        app.Months = response.data.months;
                        app.psiData = response.data.psiData;
                        // OUTSIDE JS FUNCTIONS
                        freezetable(1);
                        setTimeout(() => {
                            createCellID();
                        }, 500);
                    })

            },
            update_new_plan: function(id, value, psi, e, cellID) {
                const updateTriggerKeys = [13];
                // Update only when these keys are used | Reduce Server Usage
                if (updateTriggerKeys.includes(e.keyCode) || !isNaN(e.key)) {
                    axios.post("<?= base_url() ?>index.php/PSI_Viewing/update_new_plan", {
                        SelectedRevision: this.selected_revision,
                        id: id,
                        value: value,
                        psi: psi
                    })
                }
                // Generate the PSI | Event: Press Enter
                if (e.keyCode == 13) {
                    this.GeneratePSIedit();
                }
                const gap = app.Months.length * 4;
                if (e.key == "ArrowLeft") {
                    cellIDNum = parseInt(cellID.substring(5));
                    cellIDNum--;
                    $("#cell_" + cellIDNum).focus();
                    set_cell('cell_' + cellIDNum);
                } else if (e.key == "ArrowRight") {
                    cellIDNum = parseInt(cellID.substring(5));
                    cellIDNum++;
                    $("#cell_" + cellIDNum).focus();
                    set_cell('cell_' + cellIDNum);
                } else if (e.key == "ArrowUp") {
                    cellIDNum = parseInt(cellID.substring(5));
                    cellIDNum = cellIDNum - gap;
                    $("#cell_" + cellIDNum).focus();
                    set_cell('cell_' + cellIDNum);
                } else if (e.key == "ArrowDown") {
                    cellIDNum = parseInt(cellID.substring(5));
                    cellIDNum = cellIDNum + gap;
                    $("#cell_" + cellIDNum).focus();
                    set_cell('cell_' + cellIDNum);
                }
            },

            GetRevisionData: function() {
                axios.post("<?= base_url() ?>index.php/PSI_Viewing/GetCurrentRevisionData", {
                    SelectedRevision: this.selected_revision,
                }).then((response) => {
                    // console.log(response.data);
                    app.CurrentRevisionData = response.data.CurrentRevisionData;
                })
            },
            PageLoad: function() {
                // this.GeneratePSIedit();
                this.GetRevisionData();
            }
        },
        created: function() {
            this.GeneratePSIedit();
            this.GetRevisionData();
        }
    });
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

         function number_format(number) {
        let formatCurrency = new Intl.NumberFormat('en-US');
        var data = formatCurrency.format(number)
        return data;
    }
    </script>





