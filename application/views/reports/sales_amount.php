<div class="content-wrapper" id="psiEdit" @click="PageLoad">
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-product-hunt" aria-hidden="true"></i> NCFL Plan <small style = "color : yellow">Sales Amount</small>
                    </h1>
                </li>
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
            <div class="col-xs-12">
                <div class="box box-success" style="padding:1rem;">
                    <div class="box-header" style="margin-bottom:5rem ;">
                         <div class="text-center text-black">
                            <h1 style="font-size: 40px"> PSI ({{ selected_revision }}) </h1>
                            <hr style="width:23%">
                             <button class="btn-lg btn-primary" style="width:23%" onclick="exportTableToExcel('tbl_models', 'Prod_Amount_<?= $selectedDPRNO; ?>')"><i class="fa fa-download"></i> Download</button>

                        </div>
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
                                  </div>
                                <hr>
                                <div class="input-group-btn">
                               <input style="width:100%" class=" btn btn-lg btn-success " type="submit"  @click="GeneratePSIedit">
                                </div>
                            </div>
                              </div>
                   
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
                                            <th class="text-center"> <b> SALES QTY</b> </th>
                                            <!-- Sales -->
                                                       
                                            <!-- Sales -->
                                            <th class="text-center"> <b> SALES AMOUNT</b> </th>
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
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <!-- PROD -->
                                                       
                                                        <td style="background-color:white;" class="text-right">{{number_format(models[month?.month_year]?.sales_amount)}}</td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.inventory) }} </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;" class="text-right">  {{number_format(models[month?.month_year]?.sales_amount)}} </td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='SMPL'">
                                                <tr>
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td> <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                           <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;" class="text-right">{{models[month?.month_year]?.price}}</td>
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <!-- PROD -->
                                                       
                                                        <td  class="text-right">{{number_format(models[month?.month_year]?.sales_amount)}}</td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.inventory) }} </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;" class="text-right">  {{number_format(models[month?.month_year]?.sales_amount)}} </td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='EOL'">
                                                <tr>
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td > <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                          <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;" class="text-right">{{models[month?.month_year]?.price}}</td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <!-- Sales -->
                                                       
                                                        <td style="background-color:white;" class="text-right">{{number_format(models[month?.month_year]?.sales_amount)}}</td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.inventory) }} </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;"  class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;"  class="text-right"> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;"  class="text-right">  {{number_format(models[month?.month_year]?.sales_amount)}} </td>
                                                    </template>
                                                     </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='TOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:PaleTurquoise;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                        <td style="background-color:PaleTurquoise;" class="text-center">---</td>
                                                        <td style="background-color:PaleTurquoise;" class="text-right"> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <!-- Sales -->
                                                       
                                                        <!-- Sales -->
                                                        <td style="background-color:PaleTurquoise;" class="text-right"> {{ number_format(models[month?.month_year]?.TOTAL_SALES_AMOUNT) }}</td>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='EOLTOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:Khaki;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks  }} </td>
                                                    <template v-for="month in Months">
                                                      
                                                        <td style="background-color:Khaki;" class="text-center">---</td>
                                                        <td style="background-color:Khaki;" class="text-center">{{ number_format(models[month?.month_year]?.sales_qty) }}</td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{ number_format(models[month?.month_year]?.inventory) }} </td>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='GRANDTOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:LightSkyBlue;"> <b>GRAND TOTAL</b> </td>
                                                    <template v-for="month in Months">
                                                        
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.sales_qty) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.TOTAL_SALES_AMOUNT) }}</td>
                                                    </template>
                                                </tr>
                                            </template>
                                        </template>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

<!-------------------------------------------------------------------------
                           *** CREATE NEW REVISION MODAL
    --------------------------------------------------------------------------->
<div class="modal fade text-center mx-auto" id="create_new_revision_modal">
    <div class="modal-dialog modal-sm    modal-dialog-centered">
        <div class="modal-content text-center mx-auto">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title fw-bold h2 text-center"><b>CREATE NEW REVISION </b> </div>
            </div>
            <div class="modal-body text-center mx-auto">
                <div class="form-group text-center mx-auto">
                    <form action="<?= base_url() ?>index.php/task/create_new_revision" method="POST">
                        <input class="form-control" name="revision_date" id="revision_date" Placeholder="e.g.  REV-10-2-22" />
                        <hr>
                        <button type="submit" class="button-50 bt-1" role="button">Create</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------------------------------
                         CREATE NEW REVISION MODAL ***
    --------------------------------------------------------------------------->


     <!-------------------------------------------------------------------------
                           *** GENERATE UPLOAD FILE MODAL
    --------------------------------------------------------------------------->
    <div class="modal fade text-center mx-auto" id="generate_upload_file">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h3 text-center"><b>THIS CONTAINS THE PSI TEMPLATE TO UPLOAD </b> </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-body">
                                    <div class="form-group">

                                       <select class="form-control" style="width: 100%;" id="txt_selected_month">
                                            <option value="">Select Month</option>
                                            <template v-for="month in Months">
                                                <option :value="month?.month_year"> {{ month?.month_year }} </option>
                                            </template>
                                        </select>
                                        <hr>
                                        <button type="submit" class="btn btn-lg btn-block btn-success" role="button" id="btn_generatePSIupload_template">Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">

                                        <select class="form-control " style="width: 100%;" readonly>
                                            <option value="">{{ selected_revision }}</option>
                                        </select>
                                        <hr>
                                        <button type="submit" class="btn btn-lg btn-block btn-info" role="button" id="btn_generatePSIupload_template_all">Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl_PSIupload_Template" hidden>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Model</td>
                                    <td>Month-Year</td>
                                    <td>Revision</td>
                                    <td>Monthly Price</td>
                                    <td>Production Quantity</td>
                                    <td>Sales Quantity</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $models = $this->db->query(
                                    "SELECT  * FROM tbl_new_plan 
                                    INNER JOIN tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$selectedDPRNO'
                                    BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31", strtotime("+6 month")) . "' 
                                    ORDER BY tbl_customer.sorting_code,tbl_models.sorting_code,month_year_numerical ASC "
                                )->result();
                                foreach ($models as $m) :
                                ?>
                                    <tr>
                                        <td><?= $m->models_id ?></td>
                                        <td><?= $m->models_desc ?></td>
                                        <td class='lbl_selected_month'>'</td>
                                        <td><?= $selectedDPRNO ?></td>
                                        <td><?= $m->active_price ?></td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl_PSIupload_Template_all" hidden>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Model</td>
                                    <td>Month-Year</td>
                                    <td>Revision</td>
                                    <td>Monthly Price</td>
                                    <td>Production Quantity</td>
                                    <td>Sales Quantity</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $models = $this->db->query(
                                    "SELECT  * FROM tbl_new_plan 
                                    INNER JOIN tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$selectedDPRNO'
                                    BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31", strtotime("+6 month")) . "' 
                                    ORDER BY tbl_customer.sorting_code,tbl_models.sorting_code,month_year_numerical ASC "
                                )->result();
                                foreach ($models as $m) :
                                ?>
                                    <tr>
                                        <td><?= $m->models_id ?></td>
                                        <td><?= $m->models_desc ?></td>
                                        <td>'<?= $m->month_year ?></td>
                                        <td><?= $m->revision ?></td>
                                        <td><?= $m->price ?></td>
                                        <td><?= $m->sales_qty ?></td>
                                        <td><?= $m->sales_qty ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------
                         GENERATE UPLOAD FILE MODAL ***
    --------------------------------------------------------------------------->

    <!-------------------------------------------------------------------------
                           *** UPLOAD PLAN MODAL
    --------------------------------------------------------------------------->
    <div class="modal fade text-center mx-auto" id="upload_plan_modal">
        <div class="modal-dialog modal-md    modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h2 text-center"><b>UPLOAD PLANS </b> </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="form-group text-center mx-auto">
                        <div class="box box-info" style="padding:15px ;">
                            <div class="box-header">
                                <div class="box-body px-5 py-5 ">
                                    <?php $this->load->helper("form"); ?>
                                    <form class="px-3 py-3" action="<?php echo base_url(); ?>index.php/PSI/importFile" method="post" enctype="multipart/form-data">
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

         function number_format(number) {
        let formatCurrency = new Intl.NumberFormat('en-US');
        var data = formatCurrency.format(number)
        return data;
    }
    </script>