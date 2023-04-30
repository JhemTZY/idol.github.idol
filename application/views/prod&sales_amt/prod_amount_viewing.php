<div class="content-wrapper" id="psiEdit" @click="PageLoad">
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-product-hunt" aria-hidden="true"></i> NCFL Plan <small style = "color : yellow">PRODUCTION AMOUNT</small>
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
                            <h1 style="font-size: 40px"> PRODUCTION AMOUNT ({{ selected_revision }}) </h1>
                            <hr style="width:55%">
                             <button class="btn-lg btn-primary" style="width:23%" onclick="exportTableToExcel('tbl_models', 'PRODUCTION AMOUNT<?= $selectedDPRNO; ?>')"><i class="fa fa-download"></i> Download</button>

                        </div>
                          <div class="box-tools">
                        
                            <div class="input-group">
                                <select type="text" class="psiCell form-control" v-model="selected_revision">
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
                                                     <template v-if="models.modelDT.isHidden !== '1'">

                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td  id="lbl_models_desc" @contextmenu="rclick_model(models.modelDT.models_id)"> <b> {{ models.modelDT.models_desc }} <a style="color: blue; font-size: 10px;">{{ models.modelDT.model_remarks }}</a>  </b>
                                                      
                                                    </td>
                                                    <template v-for="month in Months">
                                                           <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;" class="text-right">{{number_format(models[month?.month_year]?.price)}}</td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                        <!-- Sales -->
                                                       
                                                        <td style="background-color:white;" class="text-right"> {{number_format(models[month?.month_year]?.prod_amount)}} </td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.prod_qty)}} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.inventory)}}</td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;" class="text-right"> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;" class="text-right">   {{number_format(models[month?.month_year]?.prod_amount)}}  </td>
                                                    </template>
                                                    </template>
                                        </template>

                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='SMPL'">
                                                <tr>
                                            <template v-if="models.modelDT.isHidden !== '1'">

                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td id="lbl_models_desc" @contextmenu="rclick_model(models.modelDT.models_id)"> <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                           <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;" class="text-right">{{number_format(models[month?.month_year]?.price)}}</td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="" class = "psiCell form-control" name="" style="text-align: center; width: 90px; outline:none!important; border:0;outline:0;" :value = "number_format(models[month?.month_year]?.prod_qty)"  @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.models_code,  $event.target.value, 'prod_qty', event, $event.target.id)" >  </td>
                                                        <!-- Sales -->
                                                       
                                                        <td  class="text-right"> {{number_format(models[month?.month_year]?.prod_amount)}} </td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.prod_qty)}} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right">  <input type="" class = "psiCell form-control" name="" style="text-align: center; width: 90px; outline:none!important; border:0;outline:0;" :value = "number_format(models[month?.month_year]?.inventory)"> </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;" class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;" class="text-right"> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;" class="text-right">   {{number_format(models[month?.month_year]?.prod_amount)}}  </td>
                                                    </template>
                                                    </template>
                                            </template>
                                                </tr>
                                        </template>
                                            <template v-if="models.modelDT.cat_code =='EOL'">
                                                <tr>
                                            <template v-if="models.modelDT.isHidden !== '1'">
                                                    <td> <b> {{ models.modelDT.item_code}} </b></td>
                                                    <td id="lbl_models_desc" @contextmenu="rclick_model(models.modelDT.models_id)"> <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                          <template v-if="models[month?.month_year]?.category==1??'0'">
                                                        <td style="background-color:white;" class="text-right">{{number_format(models[month?.month_year]?.price)}}</td>
                                                        <!-- Sales -->
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                        <td style="background-color:white;" class="text-right" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                        <!-- Sales -->
                                                       
                                                        <td style="background-color:white;" class="text-right"> {{number_format(models[month?.month_year]?.prod_amount)}} </td>
                                                    </template>
                                                      <template v-if="models[month?.month_year]?.category==2??'0'">
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.prod_qty)}} </td>
                                                      
                                                        <td style="background-color:Khaki;" class="text-right"> {{number_format(models[month?.month_year]?.inventory)}} </td>
                                                    </template>
                                                     <template v-if="models[month?.month_year]?.category==3??'0'">
                                                    
                                                        <td style="background-color:LightGreen;"  class="text-right"> {{ number_format(models[month?.month_year]?.price) }} </td>
                                                        <td style="background-color:LightGreen;"  class="text-right"> {{number_format(models[month?.month_year]?.prod_qty)}}  </td>
                                                     
                                                      
                                                        <td style="background-color:LightGreen;"  class="text-right">   {{number_format(models[month?.month_year]?.prod_amount)}}  </td>
                                                    </template>
                                                     </template>
                                            </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='TOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:PaleTurquoise;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
                                                    <template v-for="month in Months">
                                                        <td style="background-color:PaleTurquoise;" class="text-center">---</td>
                                                        <td style="background-color:PaleTurquoise;" class="text-right"> {{ number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                        <!-- Sales -->
                                                       
                                                        <!-- Sales -->
                                                        <td style="background-color:PaleTurquoise;" class="text-right"> {{ number_format(models[month?.month_year]?.TOTAL_PROD_AMOUNT) }}</td>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='EOLTOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:Khaki;"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks  }} </td>
                                                    <template v-for="month in Months">
                                                      
                                                        <td style="background-color:Khaki;" class="text-center">---</td>
                                                        <td style="background-color:Khaki;" class="text-center">{{ number_format(models[month?.month_year]?.prod_qty) }}</td>
                                                        <td style="background-color:Khaki;" class="text-right"> {{ number_format(models[month?.month_year]?.inventory) }} </td>
                                                    </template>
                                                </tr>
                                            </template>
                                            <template v-if="models.modelDT.cat_code =='GRANDTOTAL'">
                                                <tr>
                                                    <td colspan='2' style="background-color:LightSkyBlue;"> <b>GRAND TOTAL</b> </td>
                                                    <template v-for="month in Months">
                                                        
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> --- </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.prod_qty) }} </td>
                                                        <td style="background-color:LightSkyBlue;" class="text-right"> {{ number_format(models[month?.month_year]?.TOTAL_PROD_AMOUNT) }}</td>
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


</script>



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
                axios.post("<?= base_url() ?>index.php/Prod_Sales_Amount/GeneratePSIedit", {
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


              update_new_plan: function(id, month,models_code, value, psi, e, cellID) {
                const updateTriggerKeys = [13];
                // Update only when these keys are used | Reduce Server Usage
                if (updateTriggerKeys.includes(e.keyCode) || !isNaN(e.key)) {
                    axios.post("<?= base_url() ?>index.php/Prod_Sales_Amount/update_new_plan", {
                        SelectedRevision: this.selected_revision,
                        id: id,
                        month: month,
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
                if (e.keyCode === 13) {
                    this.GeneratePSIedit();
                }
           const gap = (app.Months.length * 4) -9;
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
                axios.post("<?= base_url() ?>index.php/Prod_Sales_Amount/GetCurrentRevisionData", {
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



