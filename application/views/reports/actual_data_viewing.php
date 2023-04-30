<div class="content-wrapper" id="psiEdit" @click="PageLoad">
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa ion-ios-list" aria-hidden="true"></i> NCFL Plans
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/PSI/psi_edit" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit Mode</a></li>
                 <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/PSI_Viewing/Actual" class="btn btn-lg btn-outline-info" style="color:white"><i class="ion-eye"></i> View Actual Data</a></li>
              
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
                        <div class="box-title">
                                 <select class="form-control " @change="Actual_Data" v-model="selected_months">
                                    <option selected>Beginning Inventory</option>
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

                          
                               </div>
                          <div class="text-center text-black" >
                            <h1 style="font-size: 30px"> PSI ACTUAL DATA </h1>
                        <hr>
                        </div>
                          <div class="box-tools">
                        
                             <button class="btn-lg btn-primary" style="width:100%" onclick="exportTableToExcel('tbl_models', 'PSI')"><i class="fa fa-download"></i> Download</button>
                          
                    </div>
                    <div class="box-body">
                        <div class="table-responsive freeze-table">
                            <table class="table table-bordered" id="tbl_models" style="color:black;">
                                <thead>
                                    <tr>
                                        <th class="text-center" colspan="3" style="color: black;">ACTUAL DATA</th>
                                        
                                         <template  v-for="month in Months">
                                         <template v-if="previousmonth !== month.month_year_numerical">
                                        <th class="text-center" colspan="3" > {{ month?.working_days }} Days </th>
                                    </template>
                                     <template v-else>
                                        <th class="text-center" colspan="3" > {{ month?.isActual }}  </th>
                                    </template>
                                     </template>
                                    </tr>
                                    <tr style="background-color:LightGreen;">
                                        <th class="text-center" rowspan="2">Customer</th>
                                        <th class="text-center" rowspan="2">Item No.</th>
                                        <th class="text-center" rowspan="2">Model</th>
                                        <th class="text-center" colspan="3">{{ selected_months }}</th>
                                        <template  v-for="month in Months">
                                       <template v-if="previousmonth !== month.month_year_numerical">
                                            <th class="text-center" colspan="3"> {{ month?.month_year }} </th>
                                             </template>
                                    </template>
                                    </tr>
                                    <tr style="background-color:LightGreen;">
                                     
                                        <template v-for="month in Months">
                                               <template v-if="previousmonth !== month.month_year_numerical">
                                            <th class="text-center"> <b> PRODN.</b> </th>
                                            <th class="text-center"> <b> SALES</b> </th>
                                            <th class="text-center" > <b> INVTY</b> </th>
                                        </template>

                                         <template v-else>
                                            <th class="text-center"> <b> PRODN.</b> </th>
                                            <th class="text-center"> <b> SALES</b> </th>
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
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td> <b> {{ models.modelDT.models_desc }}  <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </b>
                                                      
                                                    </td>

                                                    <template v-if="Begin.length>0"> 
                                                        <template v-for="month in Begin">
                                                                <template v-if="previousmonth !== month.month_year_numerical">
                                                                 
                                                                  <td style="background-color:white;"> ---- </td>
                                                                  <td style="background-color:white;"> ---- </td>
                                                                  <td style="background-color:white;"> ---- </td>

                                                         </template>
                                           

                                                        <template v-else>
                                                                <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                
                                                              <!--  <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                         -->
                                                          </template>
                                                     </template>
                                                      </template>
                                                  
                                                   




                                                 
                                                    <template v-for="month in Months">
                                                        <template v-if="previousmonth !== month.month_year_numerical">

                                                          <template v-if="models[month?.month_year]?.category==1??'0'">
                                                      
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                </template>
                                                     <template v-if="models[month?.month_year]?.category==2??'0'">

                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

                                                    </template>

                                                      <template v-if="previousmonth !== month.month_year_numerical">
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">


                                                          <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

                                                        
                                                        </template>
                                                        </template>


                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='SMPL'">
                                                <tr>
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td> <b> {{ models.modelDT.models_desc }} </b> <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </td>


                                                      
                                                    <template v-if="Begin.length>0"> 
                                                        <template v-for="month in Begin">
                                                                <template v-if="previousmonth !== month.month_year_numerical">
                                                                 
                                                                  <td style="background-color:LightGreen;"> ---- </td>
                                                                  <td style="background-color:LightGreen;"> ---- </td>
                                                                  <td style="background-color:LightGreen;"> ---- </td>

                                                         </template>
                                           

                                                        <template v-else>
                                                                <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                
                                                              <!--  <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                         -->
                                                          </template>
                                                     </template>
                                                      </template>



                                                    <template v-for="month in Months">
                                                              <template v-if="models[month?.month_year]?.category==1??'0'">
                                                      
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==2??'0'">

                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>



                                            <template v-if="models.modelDT.cat_code =='EOL'">
                                                <tr>
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td> <b> {{ models.modelDT.models_desc }} </b> <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </td>

                                                     
                                                    
   <template v-if="Begin.length>0"> 
                                                        <template v-for="month in Begin">
                                                                <template v-if="previousmonth !== month.month_year_numerical">
                                                                 
                                                                  <td style="background-color:GoldenRod;"> ---- </td>
                                                                  <td style="background-color:GoldenRod;"> ---- </td>
                                                                  <td style="background-color:GoldenRod;"> ---- </td>

                                                         </template>
                                           

                                                        <template v-else>
                                                                <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:GoldenRod;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:GoldenRod;"> {{roundoff(models[month?.month_year]?.inventory/1000)??'NULL'}} </td>
                                                
                                                              <!--  <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                         -->
                                                          </template>
                                                     </template>
                                                      </template>


                                                    <template v-for="month in Months">
                                                                 <template v-if="models[month?.month_year]?.category==1??'0'">
                                                      
                                                        <!-- PROD -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>
                                                    </template>
                                                    
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">


                                                          <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <p style="border: 4px solid red; text-align: center;">{{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </p></td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.prod_qty/1000)??'NULL'}} </td>
                                                        <!-- PROD -->
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <p style="border-bottom:solid; border-color: black; text-align: center;"> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}}</p> </td>
                                                        <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{roundoff(models[month?.month_year]?.sales_qty/1000)??'NULL'}} </td>
                                                        <!-- Sales -->
                                                        <td style="background-color:LightGreen;"> {{roundoff(models[month?.month_year]?.auto_inventory/1000)??'NULL'}} </td>

                                                        
                                                        </template>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='TOTAL'">
                                                <tr style="background-color:LightSkyBlue;">
                                                    <td colspan='2' style="background-color:LightSkyBlue;"> {{ models.modelDT.models_desc }} <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </td>


                                                   



                                                    <template v-if="Begin.length>0"> 
                                                        <template v-for="month in Begin">
                                                                <template v-if="previousmonth !== month.month_year_numerical">
                                                                 <td> --- </td>
                                                                 <td> --- </td>
                                                                   <td> --- </td>
                                                         </template>
                                           

                                                        <template v-else>
                                                
                                                               <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.prod_qty/1000)) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.sales_qty/1000)) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>
                                                        
                                                          </template>
                                                     </template>
                                                      </template>



                                                    <template v-for="month in Months">
                                                        <template v-if="previousmonth !== month.month_year_numerical">
                                        
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.prod_qty/1000)) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.sales_qty/1000)) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='EOLTOTAL'">
                                             <tr style="background-color:GoldenRod;">

                                                    <td colspan='2' style="background-color:GoldenRod;"> {{ models.modelDT.models_desc }} <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </td>

                                                    <template v-if="Begin.length>0"> 
                                                        <template v-for="month in Begin">
                                                                <template v-if="previousmonth !== month.month_year_numerical">
                                                                 
                                                                   <td> --- </td>
                                                                    <td> --- </td>
                                                                     <td> --- </td>
                                                         </template>
                                           

                                                        <template v-else>
                                                
                                                              
                                                        <td style="background-color:GoldenRod;" class="text-center">---</td>
                                                        <td style="background-color:GoldenRod;" class="text-center">---</td>
                                                        <td style="background-color:GoldenRod;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.inventory/1000)) }} </td>
                                                        
                                                          </template>
                                                     </template>
                                                      </template>


                                                    <template v-for="month in Months">
                                                        <template v-if="previousmonth !== month.month_year_numerical">

                                                     
                                                        <td style="background-color:GoldenRod;" class="text-center">---</td>
                                                        <td style="background-color:GoldenRod;" class="text-center">---</td>
                                                        <td style="background-color:GoldenRod;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.inventory/1000)) }} </td>
                                                    </template>
                                                    </template>
                                                </tr>
                                            </template>

                                    <template v-if="models.modelDT.cat_code =='GRANDTOTAL'">
                                                  <tr style="background-color:LightBlue;">

                                                    <td colspan='2' style="background-color:LightBlue;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>

                                                  <template v-if="Begin.length>0"> 
                                                        <template v-for="month in Begin">
                                                                <template v-if="previousmonth !== month.month_year_numerical">
                                                                 
                                                                   <td> --- </td>
                                                                      <td> --- </td>
                                                                         <td> --- </td>
                                                         </template>
                                           

                                                        <template v-else>
                                                
                                                        <td style="background-color:LightBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.prod_qty/1000)) }} </td>
                                                        <td style="background-color:LightBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.sales_qty/1000)) }} </td>
                                                        <td style="background-color:LightBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>
                                                        
                                                          </template>
                                                     </template>
                                                      </template>


                                                    <template v-for="month in Months">
                                                        <template v-if="previousmonth !== month.month_year_numerical">
                                                        
                                                        <td style="background-color:LightBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.prod_qty/1000)) }} </td>
                                                        <td style="background-color:LightBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.sales_qty/1000)) }} </td>
                                                        <td style="background-color:LightBlue;" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>
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
            previousmonth:'<?= date('Y-m-01', strtotime('-1 month')) ?>',

            CurrentRevisionData: [],
            selected_revision: '<?= $selectedDPRNO; ?>',
             selected_months: '',
             get_inv: '',
            selected_month: '',
        },
        methods: {
            Actual_Data: function() {
                axios.post("<?= base_url() ?>index.php/PSI_Viewing/Actual_Data", {
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


            GetRevisionData: function() {
                axios.post("<?= base_url() ?>index.php/PSI_Viewing/GetCurrentRevisionData", {
                    SelectedRevision: this.selected_revision,
                }).then((response) => {
                    // console.log(response.data);
                    app.CurrentRevisionData = response.data.CurrentRevisionData;
                })
            },
            PageLoad: function() {
                // this.Actual_Data();
                this.GetRevisionData();

            }
        },
        created: function() {
            this.Actual_Data();
            this.GetRevisionData();
       
        }
    });
</script>
