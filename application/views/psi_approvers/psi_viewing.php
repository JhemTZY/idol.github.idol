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

<body class="hold-transition skin-blue sidebar-mini ">
    <div class="content" id="psiEdit" @click="PageLoad">
        <!-- Content Header (Page header) -->
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
                        <div class="box-title">
                           <!--    <select type="text" class="form-control" v-model="selected_months">
                                    <option disabled selected>Beginning Inventory</option>

                                      <?php
                                    if (!empty($month_years)) {
                                        foreach ($month_years as $month) : ?>
                                            <option value="<?php echo $month->month_year ?>">
                                                <?php echo $month->month_year ?>
                                            </option>
                                    <?php
                                        endforeach;
                                    } ?>
                                </select>
                            <hr>
                             <div class="input-group-btn">
                               <input style="width:100%" class=" btn btn-lg btn-success " type="submit"  @click="GeneratePSIedit">
                                </div>
                                <hr> -->
                         </div>
                          <div class="text-center text-black">
                            <h1 style="font-size: 40px"> PSI ({{ selected_revision }}) </h1>
                            <!-- <h1 style="font-size: 40px"> PSI ({{ selected_months }}) </h1> -->
                            <hr>
                             <button style="width: 20%" class="btn-lg btn-primary" style="width:100%" onclick="exportTableToExcel('tbl_models', 'PSI <?= $selectedDPRNO; ?>')"><i class="fa fa-download"></i> Download</button>

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
                                        <template v-for="month in Months">
                                            <template v-if="Actual !== month.isActual">
                                             <th class="text-center" colspan="3" > {{ month?.working_days }} Days </th>
                                            </template>
                                             <template v-else>
                                             <th class="text-center" colspan="1" > </th>
                                            </template>
                                        </template>
                                       
                                    </tr>
                                    <tr style="background-color:LightGreen;">
                                        <th class="text-center" rowspan="2">Customer</th>
                                        <th class="text-center" rowspan="2">Item No.</th>
                                        <th class="text-center" rowspan="2">Model</th>
                                        <template v-for = "month in Months">
                                              <template v-if="Actual !== month.isActual">
                                         <th class="text-center" colspan="3"> {{ month?.month_year }} </th>
                                         </template>
                                          <template v-else>
                                         <th class="text-center" colspan="1"> {{ month?.month_year }} </th>
                                         </template>
                                        </template>
                                       
                                    </tr>
                                    <tr style="background-color:LightGreen;">
                                     
                                        <template v-for="month in Months">
                                               <template v-if="Actual !== month.isActual">
                                            <th class="text-center"> <b> PRODN.</b> </th>
                                            <th class="text-center"> <b> SALES</b> </th>
                                            <th class="text-center" > <b> INVTY</b> </th>
                                        </template>
                                         <template v-else>
                                            <th class="text-center" > <b> INVTY</b> </th>
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
                                                    <td> <b> {{ models.modelDT.models_desc }}   {{ models.modelDT.model_remarks }} </b>
                                                      
                                                    </td>
                                                    <template v-for="month in Months">
                                                             <template v-if="Actual !== month.isActual">
                                                          <template v-if="models[month?.month_year]?.category==1??'0'">
                                                      
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                    </template>

                                                          <template v-else>
                                                          <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                    </template>
                                                       
                                                             <template v-if="Actual !== month.isActual">
                                                    <template v-if="models[month?.month_year]?.category==2??'0'">

                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;"> {{models[month?.month_year]?.inventory/1000??'NULL'}} </td>

                                                    </template>
                                                </template>



                                                <template v-else>
                                                    <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:GoldenRod;"> {{models[month?.month_year]?.inventory/1000??'NULL'}} </td>

                                                    </template>
                                                </template>
                                                    
                                                             <template v-if="Actual !== month.isActual">
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">


                                                          <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

                                                        </template>
                                                        </template>


                                                        <template v-else>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

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
                                                             <template v-if="Actual !== month.isActual">
                                                              <template v-if="models[month?.month_year]?.category==1??'0'">
                                                      
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                </template>



                                                 <template v-else>
                                                              <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                </template>


                                                             <template v-if="Actual !== month.isActual">
                                                     <template v-if="models[month?.month_year]?.category==2??'0'">

                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;"> {{models[month?.month_year]?.inventory/1000??'NULL'}} </td>

                                                    </template>
                                                </template>

                                                  <template v-else>
                                                     <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:GoldenRod;"> {{models[month?.month_year]?.inventory/1000??'NULL'}} </td>
                                                    </template>
                                                </template>

                                                             <template v-if="Actual !== month.isActual">
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                          <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                        </template>
                                                        </template>


                                                        <template v-else>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
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
                                                             <template v-if="Actual !== month.isActual">
                                                                 <template v-if="models[month?.month_year]?.category==1??'0'">
                                                      
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                </template>



                                                  <template v-else>
                                                                 <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                </template>

                                                             <template v-if="Actual !== month.isActual">
                                                     <template v-if="models[month?.month_year]?.category==2??'0'">

                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;"> {{models[month?.month_year]?.inventory/1000??'NULL'}} </td>

                                                    </template>
                                                </template>


                                                   <template v-else>
                                                     <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:GoldenRod;"> {{models[month?.month_year]?.inventory/1000??'NULL'}} </td>

                                                    </template>
                                                </template>


                                                     
                                                             <template v-if="Actual !== month.isActual">
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">


                                                          <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="text-decoration: underline; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

                                                        
                                                        </template>
                                                    </template>


                                                      <template v-else>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                        </template>
                                                    </template>
                                                    </template>
                                                </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='TOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:LightSkyBlue;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                         <template v-if="Actual !== month.isActual">
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ roundoff(models[month?.month_year]?.prod_qty/1000)  }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ roundoff(models[month?.month_year]?.sales_qty/1000) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ roundoff(models[month?.month_year]?.auto_inventory/1000) }}</td>
                                                    </template>
                                                     <template v-else>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ roundoff(models[month?.month_year]?.auto_inventory/1000)}}</td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='EOLTOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:GoldenRod;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                         <template v-if="Actual !== month.isActual">
                                                          <td style="background-color:GoldenRod;" class="text-right"> {{ roundoff(models[month?.month_year]?.prod_qty/1000) }} </td>
                                                        <td style="background-color:GoldenRod;" class="text-right"> {{ roundoff(models[month?.month_year]?.sales_qty/1000) }} </td>
                                                        <td style="background-color:GoldenRod;" class="text-right"> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>
                                                    </template>
                                                     <template v-else>
                                                        <td style="background-color:GoldenRod;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.inventory/1000)) }} </td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>

                                    <template v-if="models.modelDT.cat_code =='GRANDTOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:LightSkyBlue;"> <b>GRAND TOTAL</b> </td>
                                                    <template v-for="month in Months">
                                                          <template v-if="Actual !== month.isActual">
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.prod_qty/1000) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.sales_qty/1000) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.auto_inventory/1000) }}</td>
                                                    </template>
                                                    <template v-else>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.auto_inventory/1000) }}</td>
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
    // Create Automatic Cell
    function createCellID() {
        var counter = 0;
        $(".psiCell").each(function() {
            counter = counter + 1;
            $(this).attr("id", "cell_" + counter);
        });
    }
    function roundoff(number) {
        let num = number;
        var data = num.toFixed(1);
        return data;
            
        }
    // Set Cell in Local Storage
    function set_cell(id) {
        var cell = window.localStorage.getItem("cell");
        if (cell) {
            window.localStorage.removeItem("cell");
            window.localStorage.setItem("cell", id);
        }

    }
</script>


<script>
    $(document).ready(() => {
        $("#btn_generatePSIupload_template").click(() => {
            var date = $("#txt_selected_month").val();
            exportTableToExcel("tbl_PSIupload_Template", "PSI-Upload-Template-Monthly-" + date);
        });

        $("#btn_generatePSIupload_template_all").click(() => {
            exportTableToExcel("tbl_PSIupload_Template_all", "PSI-Upload-Template-Six-Months-" + "<?= $selectedDPRNO ?>");
        });

        $("#txt_selected_month").change(() => {
            var date = $("#txt_selected_month").val();
            $(".lbl_selected_month").html("'" + date);
        })

    })
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
            Begin: [],
            AllModels: [],
            customers: [],
            psiData: [],
            Actual:'ACTUAL',
            Hidden:'Display',

            CurrentRevisionData: [],
            selected_revision: '<?= $selectedDPRNO; ?>',
             selected_months: '',
             get_inv: '',
            selected_month: '',
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
                        app.Begin = response.data.get_invty;
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