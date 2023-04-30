<div class="content-wrapper" id="mc_vue_block">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-product-hunt" aria-hidden="true"></i> NCFL Plan <small style="color : yellow"> Production Amount </small>
                    </h1>
                </li>
            </ol>
        </nav>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header ">
                        <h3 class="box-title tags"><a class="color3">Production QTY & AMT BLOCK</a> 
                        </h3>

                    

                        <div class="box-tools">
                      <button class="button-33" onclick="exportTableToExcel('tbl_models', 'MC_Block')"><i class="fa fa-download" style="color : black"></i> Export Data</button>

                        </div>
                    </div>

                    <div class="box-body">

                  
                              <div class="table-responsive freeze-table">
                            <table class="table table-bordered table-hover " id="tbl_models">
                                <thead>
                                    <tr>
                                        <th >Month Year</th>
                                         <th >MATERIAL COST</th>

                                   <template v-for ="distinctCustomers in Customer">
                                    

                               
                                       

                                        <th :style="{ background: distinctCustomers.customer_color_A}">{{ distinctCustomers.customers_desc }}</th>
                                        <th>Total</th>


                                        
                                    </template>

                                   
                              
                                </thead>
                                <tbody>

                            
                                                                                
                                     <template v-for="months in MonthsInRevision">
                                         <tr>
                                            <td rowspan = "3"  style="background-color: LawnGreen; font-weight: bold;">{{ months.month_year }}</td>
                                            <td >Prodn Amt (M USD)</td>
                                          </tr>
                                          <tr>
                                            <td  >Prodn Qty</td>
                                          </tr>
                                          <tr>
                                            <td  >Matl Cost (M USD)</td>
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
        el: '#mc_vue_block',
        data: {
            MonthsInRevision: [],
              Customer: [],
 
        },
        methods: {
            Mc_per_Q: function() {
                axios.post("<?= base_url() ?>index.php/Material_cost/mc_block_data", {
                      
                    })
                    .then((response) => {
                        console.log(response.data);
                        app.MonthsInRevision = response.data.months;
                      app.Customer = response.data.distinctCustomers;
                       
                    })
            }
        },
        created: function() {
            this.Mc_per_Q();
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