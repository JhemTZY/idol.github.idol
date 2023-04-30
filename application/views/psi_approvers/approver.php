<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DWH : APPROVAL <?= $selectedDPRNO; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="<?= base_url() ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="<?= base_url() ?>assets/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Context Menu BOOTSRAP -->
    <link href="<?= base_url() ?>assets2/css/bootstrap-jquery-context-menu/css/context.bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets2/css/bootstrap-jquery-context-menu/css/context.standalone.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?= base_url() ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>assets/tags/tags.css" rel="stylesheet" type="text/css" />

    <link href="<?= base_url() ?>assets/button/button.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/tooltips.css">

    <!-- JQUERY -->
    <script type="text/javascript" src="<?= base_url() ?>assets/js2/jquery-3.4.1.min.js"></script>

    <!-- BOOTSTRAP CONTEXT MENU -->
    <script src="<?= base_url() ?>assets/BS_ContextMenu/dist/BootstrapMenu.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap-select.css">

    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>assets/dist/img/dwhicon/dwhicon_final.png">

    <!-- Select2 -->
    <link href="<?= base_url() ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?= base_url() ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <link href="<?= base_url() ?>assets/bower_components/bootstrap/dist/css/choices.min.css" rel="stylesheet" type="text/css" />

    <!-- CHARTJS -->
    <script src="<?= base_url() ?>assets2/plugins/chart.js/Chart.min.js"></script>

    <!--DATA TABLEs -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/datatables/DataTables.css">


    <!-- *** VUEJS AND AXIOS -->
    <script type="text/javascript" src="<?= base_url() ?>assets/vuejs/vue.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/axios/axios.min.js"></script>
    <!-- VUEJS AND AXIOS *** -->

    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .error {
            color: gold;
            font-weight: normal;
        }

        /* Hide Scrollbar */
      /*  ::-webkit-scrollbar {
            width: 0.1px;
        }*/


        
    </style>
    <script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
        var baseURL = "<?= base_url() ?>";
    </script>

    <!-- TABLE COLUMN AND HEADER FREEZE FOR BIG DATA -->
    <script>
        function freezetable(columnNum) {
            $(function() {
                $('.freeze-table').freezeTable({
                    'columnNum': columnNum,
                });
            });
        }

        function ObjectLength(object) {
            var length = 0;
            for (var key in object) {
                if (object.hasOwnProperty(key)) {
                    ++length;
                }
            }
            return length;
        };
    </script>

</head>

<?php
$approverID = $currentRevision = '';
if (isset($_GET['revision']) && isset($_GET['approverId'])) {
    $approverID = $_GET['approverId'];
    $currentRevision = $_GET['revision'];
    $selectedRevision = $this->db->query("SELECT * FROM tbl_revision WHERE ID=" . $currentRevision . "  ")->row();
    if ($selectedRevision !== NULL) {
        $currentRevision = $selectedRevision->revision_date;
        if ($currentRevision == $selectedDPRNO) {
?>
            <div class="modal fade" id="approver">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title">DATAWAREHOUSE APPROVAL - <b>(
                                    <?= $currentRevision; ?> ) </b> </h3>
                        </div>
                        </br>
                        <div class="modal-body">
                            <div class="card shadow px-3 py-3">
                                <form action="<?php echo base_url(); ?>index.php/Psi_approver/psi_approved_by_status" method="POST" onsubmit = "alert_success()">
                                    <input type="hidden" value="<?= $approverID; ?>" name="userId">
                                    <input type="hidden" value="<?= $_GET['revision'] ?>" name="revisionID">
                                    <div class="input-group">
                                        <textarea class="form-control" name="comment" placeholder="Remarks" oninvalid = "input_required()" required></textarea>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-lg btn-success" name="approved" value="Approved" id="btn_submt"  @click="approved_and_disapproved"><i class="fa fa-check-square"></i> Approved</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="disapprover">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title">DATAWAREHOUSE APPROVAL - <b>(
                                    <?= $currentRevision; ?> ) </b> </h3>
                        </div>
                        </br>
                        <div class="modal-body">
                            <div class="card shadow px-3 py-3">
                                <form action="<?php echo base_url(); ?>index.php/Psi_approver/psi_noted_by_rejected_status" method="POST" onsubmit = "alert('REJECT')">
                                    <input type="hidden" value="<?= $approverID; ?>" name="userId">
                                    <input type="hidden" value="<?= $_GET['revision'] ?>" name="revisionID">
                                    <div class="input-group">
                                        <textarea class="form-control" name="comment" placeholder="Remarks" oninvalid = "rejected()" required></textarea>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-lg btn-danger" name="approved" value="Approved" id="btn_submt" @click="approved_and_disapproved"><i class="fa fa-check-square"></i> Rejected</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
<?php }
    }
} ?>

<body class="hold-transition skin-blue sidebar-mini ">
    <div class="content" id="psiEdit" @click="PageLoad">
        <!-- Content Header (Page header) -->
        <section class="content">
            <div class="row">
               
                <div class="col-xs-12">
                    <?php $approverStatus = $this->db->query("SELECT status from tbl_approved_psi where rev_id = " . $_GET['revision']. " AND approver_id = " . $approverID . "  ")->row()->status;
 if ($approverStatus !== "1" && $approverStatus !== "Rejected") {

?>
                    <div class="box box-success" style="padding:1rem;">
                        <div class="box-header" style="margin-bottom:5rem ;">
                            <h3 class="box-title">PSI ({{ current_revision }}) </h3>
                            <div class="box-tools">


                

 
<button class="btn btn-lg btn-primary" data-toggle='modal' data-target='#approver'><i class="fa fa-thumbs-o-up"></i> APPROVAL</button>
   
 <button class="btn btn-lg btn-danger" data-toggle='modal' data-target='#disapprover'><i class="fa fa-thumbs-o-down"></i> Rejected</button>
<?php } else { ?>
    <button class="btn btn-lg btn-primary" data-toggle='modal' data-target='#approver' hidden><i class="fa fa-thumbs-o-up"></i> APPROVAL</button>
   
 <button class="btn btn-lg btn-danger" data-toggle='modal' data-target='#disapprover' hidden><i class="fa fa-thumbs-o-down"></i> Rejected</button>

        <?php } ?>



                            </div>
                        </div>

                        <div class="box-body">


                            <div class="table-responsive freeze-table">
                                <?php $approverStatus = $this->db->query("SELECT status from tbl_approved_psi where rev_id = " . $_GET['revision']. " AND approver_id = " . $approverID . "  ")->row()->status;
 if ($approverStatus !== "1" && $approverStatus !== "Rejected") {

?> 
                                <table class="table table-bordered" id="tbl_models" style="color:#000">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="3"># of Working Days</th>
                                             <template v-for="month in Months">
                                                   <template v-if="Actual !== month.isActual">
                                            <th class="text-center" colspan="3"> {{ month?.working_days }} Days </th>
                                              </template>
                                                       <template v-else>
                                            <th class="text-center" colspan="1" v-if="Hidden !== month.isHidden" hidden>{{ month?.isActual }}</th>
                                            <th class="text-center" colspan="1" v-else>{{ month?.isActual }}</th>
                                               </template>
                                                </template>
                                        </tr>
                                        <tr style="background-color:rgba(204,255,204,0.9);">
                                            <th class="text-center" rowspan="2">Customer</th>
                                            <th class="text-center" rowspan="2">Item #</th>
                                            <th class="text-center" rowspan="2">Model</th>
                                            <template v-for="month in Months">
                                                <template v-if="Actual !== month.isActual">
                                              
                                            <th class="text-center" colspan="3"> {{ month?.month_year }} </th>
                                        </template>
                                         <template v-else>
                                              
                                            <th class="text-center" colspan="1" v-if="Hidden !== month.isHidden" hidden> {{ month?.month_year }} </th>
                                            <th class="text-center" colspan="1" v-else> {{ month?.month_year }} </th>
                                    </template>
                                            </template>
                                        </tr>
                                        <tr style="background-color:rgba(204,255,204,0.9);">

                                        <template v-for="month in Months">
                                            <template v-if="Actual !== month.isActual">
                                           
                                            <th class="text-center"> <b> PRODN.</b> </th>
                                            <th class="text-center"> <b> SALES</b> </th>
                                            <th class="text-center"> <b> INVTY</b> </th>
                                        </template>

                                        <template v-else>
                                            <th class="text-center" v-if="Hidden !== month.isHidden" hidden> <b> INVTY</b> </th>
                                            <th class="text-center" v-else> <b> INVTY</b> </th>
                                        </template>


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
                                            <template v-if="models.modelDT.isHidden !== '1'">

                                                <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                <td> <b> {{ models.modelDT.models_desc }}</b>

                                                    <p style="color: blue;"> {{ models.modelDT.model_remarks }} </p>
                                                </td>
                                                <template v-for="month in Months">
                                                 <template v-if="Actual !== month.isActual  " >

                                                  <template v-if="models[month?.month_year]?.category==1??'0'">
                                    

                                                    <!-- PROD -->
                                                    <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell;   text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>


                                                    <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> 
                                                    </td>
                                                    <!-- PROD -->


                                                    <!-- Sales -->
                                                    <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>




                                                    <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                                    <!-- Sales -->
                                                    <td style="background-color:white;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                                                </template>
                                            </template>


                                            <template v-else>
                                              <template v-if="models[month?.month_year]?.category==1??'0'">

                                                <td style="background-color:white;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>

                                                <td style="background-color:white;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                                            </template>
                                        </template>


                                        <template v-if="Actual !== month.isActual ">

                                         <template v-if="models[month?.month_year]?.category==2??'0'">
                                           


                                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedProdQty/1000??'NULL')" disabled> </td>


                                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedSalesQty/1000??'NULL')" disabled> </td>
                                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                                        </template>
                                    </template>


                                    <template v-else>
                                     <template v-if="models[month?.month_year]?.category==2??'0'">
                                        <td style="background-color:GoldenRod;" v-if="Hidden !== month.isHidden" hidden data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>


                                        <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                                    </template>


                                </template>




                                <template v-if="Actual !== month.isActual ">
                                 <template v-if="models[month?.month_year]?.category==3??'0'">
                                    <td style="background-color:LightGreen;" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="number_format(models[month?.month_year]?.price??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'price', event, $event.target.id)"> </td>


                                    <!-- PROD -->
                                    <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"   v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>


                                    <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                                    <!-- PROD -->



                                    <!-- Sales -->
                                    <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>


                                    <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                    <!-- Sales -->
                                    <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                                </template>
                            </template>

                            <template v-else>
                             <template v-if="models[month?.month_year]?.category==3??'0'">

                                <td style="background-color:LightGreen;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>

                                <td style="background-color:LightGreen;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                            </template>
                        </template>

                    </template>

                </template>
            </tr>
        </template>
        <template v-if="models.modelDT.cat_code =='SMPL'">
            <tr>
             <template v-if="models.modelDT.isHidden !== '1'">
                <td> <b> {{ models.modelDT.item_code}} </b></td>
                <td> <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                <template v-for="month in Months">
                   <template v-if="Actual !== month.isActual ">
                     <template v-if="models[month?.month_year]?.category==1??'0'">
        
                        <!-- PROD -->
                        <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>

                        <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                        <!-- PROD -->



                        <!-- Sales -->
                        <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>


                        <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                        <!-- Sales -->


                        <td style="background-color:white;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                    </template>
                </template>


                <template v-if="Actual !== month.isActual ">
                 <template v-if="models[month?.month_year]?.category==2??'0'">
                    <td style="background-color:GoldenRod;" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.price??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'price', event, $event.target.id)" disabled> </td>
                    <td style="background-color:GoldenRod;"  data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedProdQty/1000??'NULL')" disabled> </td>
                    <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedSalesQty/1000??'NULL')" disabled> </td>
                    <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                </template>
            </template>

            <template v-else>
             <template v-if="models[month?.month_year]?.category==2??'0'">
                <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"  v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>

                <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"  v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
            </template>
        </template>


        <template v-if="Actual !== month.isActual ">
         <template v-if="models[month?.month_year]?.category==3??'0'">
            <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="number_format(models[month?.month_year]?.price??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'price', event, $event.target.id)"> </td>
            <!-- PROD -->
            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>

            
            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
            <!-- PROD -->



            <!-- Sales -->
            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
            <!-- Sales -->


            <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
        </template>
    </template>



    <template v-else>
     <template v-if="models[month?.month_year]?.category==3??'0'">
        <td style="background-color:LightGreen;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>

        <td style="background-color:LightGreen;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>


    </template>
</template>
</template>
</template>


</tr>
</template>
<template v-if="models.modelDT.cat_code =='EOL'">
    <tr>
     <template v-if="models.modelDT.isHidden !== '1'">
        <td style="background-color:GoldenRod;"> <b> {{ models.modelDT.item_code}} </b></td>
        <td style="background-color:GoldenRod;"> <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
        <template v-for="month in Months">
           <template v-if="Actual !== month.isActual ">
               <template v-if="models[month?.month_year]?.category==1??'0'">


                <!-- PROD -->
                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                <!-- PROD -->


                <!-- Sales -->
                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                <!-- Sales -->



                <td style="background-color:white;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
            </template>
        </template>


        <template v-else>
           <template v-if="models[month?.month_year]?.category==1??'0'">
            <td style="background-color:white;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>

            <td style="background-color:white;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
        </template>
    </template>


    <template v-if="Actual !== month.isActual ">
     <template v-if="models[month?.month_year]?.category==2??'0'">
       
        <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedProdQty/1000??'NULL')" disabled> </td>


        <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedSalesQty/1000??'NULL')" disabled> </td>
        <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
    </template>
</template>

<template v-else>
 <template v-if="models[month?.month_year]?.category==2??'0'">
    <td style="background-color:GoldenRod;"  data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"  v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>



    <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"   v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'inventory', event, $event.target.id)"> </td>


</template>
</template>

<template v-if="Actual !== month.isActual ">
 <template v-if="models[month?.month_year]?.category==3??'0'">
    <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="number_format(models[month?.month_year]?.price??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'price', event, $event.target.id)"> </td>
    <!-- PROD -->
    <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
    <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
    <!-- PROD -->


    <!-- Sales -->
    <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
    <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"  v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_code,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
    <!-- Sales -->



    <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
</template>
</template>



<template v-else>
 <template v-if="models[month?.month_year]?.category==3??'0'">
    <td style="background-color:LightGreen;"  v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
    <td style="background-color:LightGreen;"  v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>


</template>
</template>
</template>
</template>

</tr>
</template>
<template v-if="(models.modelDT.cat_code =='TOTAL')">
    <tr>
        <td colspan='2' style="background-color:rgba(102,255,255,0.2);"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
        <template v-for="month in Months">
            <template v-if="Actual !== month.isActual ">
                <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedProdQty/1000) }} </td>
                <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedSalesQty/1000) }} </td>
                <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedInventory/1000) }}</td>
            </template>


            <template v-else >

                <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-if="Hidden !== month.isHidden" hidden> {{ roundoff(models[month?.month_year]?.mergedInventory/1000) }}</td>



                <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-else> {{ roundoff(models[month?.month_year]?.mergedInventory/1000) }}</td>
            </template>


        </template>
    </tr>
</template>



<template v-if="models.modelDT.cat_code =='EOLTOTAL'">
    <tr>
        <td colspan='2' style="background-color:rgba(255,230,153,0.5);"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
        <template v-for="month in Months">
            <template v-if="Actual !== month.isActual ">
                <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedProdQty/1000) }}</td>
                <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedSalesQty/1000) }}</td>
                <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>
            </template>


            <template v-else>

                <td style="background-color:rgba(255,230,153,0.5);" class="text-right" v-if="Hidden !== month.isHidden" hidden> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>

                <td style="background-color:rgba(255,230,153,0.5);" class="text-right" v-else> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>
            </template>


        </template>
    </tr>
</template>



<template v-if="models.modelDT.cat_code =='GRANDTOTAL'">
    <tr>
        <td colspan='2' style="background-color:rgba(102,255,255,0.2);"> <b>{{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }}<b> </td>
            <template v-for="month in Months">
              <template v-if="Actual !== month.isActual ">
                <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.mergedProdQty/1000)) }} </td>
                <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.mergedSalesQty/1000)) }} </td>
                <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.mergedInventory/1000)) }}</td>
            </template>


            <template v-else>

                <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-if="Hidden !== month.isHidden" hidden> {{ number_format(roundoff(models[month?.month_year]?.mergedInventory/1000)) }}</td>


                <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-else> {{ number_format(roundoff(models[month?.month_year]?.mergedInventory/1000)) }}</td>


            </template>


        </template>

    </tr>
</template>
</template>
</template>
</template>
</tbody>
</table>
                            <?php } else {?>
                                <h1 style="font-size: 100px ; text-align:center;">THANK YOU</h1>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
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

      <script>
                     function change_value(data , cellID){
                        document.getElementById(cellID).value=data;

                    }

                    function one_decimal(data , cellID) {
                      document.getElementById(cellID).value=data;

                  }


                  function two_decimal(number) {
                    let num = number;
                    var data = num.toFixed(3);
                    return data;

                }



            </script>

            

<script>
           function alert_success() {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "PSI APPROVED",
                text: "PSI Submitted to Approvers via Email",
                timer: 4000, 
                showConfirmButton: false,
            })

   }

  
           function input_required() {
             Swal.fire({
                position: 'top',
                icon: 'info',
                title: "REMARKS REQUIRED",
                text: "PLEASE FILL IN THIS FIELD",
                timer: 3000, 
                showConfirmButton: false,
            })
  
   }
</script>

<script> 
    function reject() {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "PSI APPROVED",
                text: "PSI Submitted to Approvers via Email",
                timer: 4000, 
                showConfirmButton: false,
            })

   }

  
           function rejected() {
             Swal.fire({
                position: 'top',
                icon: 'info',
                title: "REMARKS REQUIRED",
                text: "PLEASE FILL IN THIS FIELD",
                timer: 3000, 
                showConfirmButton: false,
            })
  
   }
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
        // Create Automatic Cell
        function createCellID() {
            var counter = 0;
            $(".psiCell").each(function() {
                counter = counter + 1;
                $(this).attr("id", "cell_" + counter);
            });
        }
        // Set Cell in Local Storage
        function set_cell(id) {
            var cell = window.localStorage.getItem("cell");
            if (cell) {
                window.localStorage.removeItem("cell");
                window.localStorage.setItem("cell", id);
            }
        }
            function roundoff(number) {
        let num = number;
        var data = num.toFixed(1);
        return data;
            
        }

         function number_format(number) {
        let formatCurrency = new Intl.NumberFormat('en-US');
        var data = formatCurrency.format(number)
        return data;
    }
    
    </script>

    <script>
        var app = new Vue({
            el: '#psiEdit',
            data: {
                Months: [],
                AllModels: [],
                customers: [],
                psiData: [],
                Approvers: [],
                previousmonth:'<?= date('Y-m-01', strtotime('-1 month')) ?>',
                Actual:'ACTUAL',
                Hidden:'Display',

                CurrentRevisionData: [],
                current_revision: '<?= $selectedDPRNO; ?>',
            },
            methods: {


                sweetalert_denied(message) {
                    swal({
                            title: "Are you sure?",
                            text: "Once rejected, you will not be able to recover this Approval Status!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                swal("Poof! The Status of your file recieved has been deleted!", {
                                    icon: "success",
                                });
                            } else {
                                swal("Thank you And Godbless!");
                            }
                        });
                },

                GeneratePSIedit: function() {
                axios.post("<?= base_url() ?>index.php/Psi_approver/GeneratePSIedit", {
                        SelectedRevision: this.current_revision,
                    })
                    .then((response) => {
                        console.log(response.data);
                        app.customers = response.data.customers;
                        app.Months = response.data.months;
                        app.begin_invty = response.data.begin_invty;
                        app.psiData = response.data.psiData;
                        // OUTSIDE JS FUNCTIONS
                        freezetable(1);
                        setTimeout(() => {
                            createCellID();
                        }, 500);
                    })

            },
                approved_and_disapproved: function(approved_disapproved, approver_id, message) {
                    axios.post("<?= base_url() ?>index.php/Psi_approver/approved_and_disapproved", {
                        approved_disapproved: approved_disapproved,
                        approver_id: approver_id


                    })
                    swal({
                        title: "Good job!",
                        text: "You clicked the button!",
                        icon: "success",
                    });

                },


                getApprovers: function() {
                    axios.post("<?= base_url() ?>index.php/Psi_approver/getApprovers", {

                        })
                        .then((response) => {
                            console.log(response.data);
                            app.Approvers = response.data.MgaApprover;

                        })
                },

                    update_new_plan: function(id, month, model, models_code,  value, psi, e, cellID) {
                const updateTriggerKeys = [13];
                // Update only when these keys are used | Reduce Server Usage
                if (updateTriggerKeys.includes(e.keyCode) || !isNaN(e.key)) {
                    axios.post("<?= base_url() ?>index.php/Psi_approver/update_new_plan", {
                        SelectedRevision: this.current_revision,
                        id: id,
                        month: month,
                        model: model,
                        models_code: models_code,
                        value: value,
                        psi: psi,
                        cellID: cellID
                    })
                     .then((response) => {
                        console.log(response.data);
                        app.month = response.data.month;
                        
                    })
                }
                    // Generate the PSI | Event: Press Enter
                    if (e.keyCode == 13) {
                        this.GeneratePSIedit();
                    }
                          const gap = (app.Months.length * 3) -2 ; 
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
                    axios.post("<?= base_url() ?>index.php/Psi_approver/GetCurrentRevisionData", {
                        SelectedRevision: this.current_revision,
                    }).then((response) => {
                        console.log(response.data);
                        app.CurrentRevisionData = response.data.CurrentRevisionData;
                    })
                },
                PageLoad: function() {
                    // this.GeneratePSIedit();
                }
            },
            created: function() {
                this.GeneratePSIedit();
                this.GetRevisionData();
                this.getApprovers();
            }
        });
    </script>

    <footer class="text-center">
        <div class="">
            <b>Datawarehouse</b> | Version 1.5
        </div>
        <strong>Copyright &copy; 2022 <a href="<?= base_url() ?>"> MIS-System Development Team </a>.</strong> All rights reserved.
    </footer>


    <script src="<?= base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>
    <!-- <script src="<?= base_url() ?>assets/dist/js/pages/dashboard.js" type="text/javascript"></script> -->
    <script src="<?= base_url() ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/dist/js/choice.min.js" type="text/javascript"></script>
    <script src="<?= base_url() ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- <script type="text/javascript" src="<?= base_url() ?>assets/js2/jquery-3.4.1.min.js"></script> -->
    <script type="text/javascript" src="<?= base_url() ?>assets/js2/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js2/select2.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/computation/computation.js"></script>

    <!-- VUEJS AND AXIOS -->
    <script type="text/javascript" src="<?= base_url() ?>assets/vuejs/vue.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/axios/axios.min.js"></script>

    <!-- CONTEXT MENU  -->
    <script type="text/javascript" src="<?= base_url() ?>assets2/css/bootstrap-jquery-context-menu/js/initialize.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets2/css/bootstrap-jquery-context-menu/js/context.js" charset="utf-8"></script>


    <!-- <script type="text/javascript" src="<?= base_url() ?>assets/excel_JS.js"></script> -->
    <!-- Data Tables -->
    <script type="text/javascript" src="<?= base_url() ?>assets/datatables/DataTables.js"></script>

    <script src="<?= base_url() ?>assets2/plugins/flot/jquery.flot.js"></script>


    <!-- TABLE COLUMN AND HEADER FREEZE FOR BIG DATA -->
    <script src="<?= base_url() ?>assets2/js2/jquery-freeze-table-master/dist/js/freeze-table.min.js"></script>


    <!-- /*----------------------------------------------------------------------------------------
        *** SWEET ALERT !
    ---------------------------------------------------------------------------------------- */ -->

    <script type="text/javascript" src="<?= base_url() ?>assets/sweetalert/sweetalert.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/sweetalert/saving.js"></script>
    <!-- /*----------------------------------------------------------------------------------------
      SWEET ALERT ! ***
  ---------------------------------------------------------------------------------------- */ -->

    <!-- SELECT 2 -->
    <script>
        $(document).ready(function() {
            $('.selecting').select2();
        });
    </script>

    <script type="text/javascript">
        var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        var x = $('a[href="' + pageURL + '"]');
        x.addClass('active');
        x.parent().addClass('active');
        var y = $('a[href="' + windowURL + '"]');
        y.addClass('active');
        y.parent().addClass('active');
    </script>


    <script>
        function sweetalert_success(message) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 800
            })
        }

        function sweetalert_failed(message) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 800
            })
        }
    </script>


</body>

</html>