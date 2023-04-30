<div class="content-wrapper" id="psi_model">
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
                    <a href="<?= base_url() ?>index.php/Category/Category" class="btn btn-lg btn-outline-success" style="color:white">Category</a>
                    <a href="<?= base_url() ?>index.php/User_Maintenance/update_model_price" class="btn btn-lg btn-outline-success" style="color:white">PRICE UPDATE </a>
                    <a href="<?= base_url() ?>index.php/User_Maintenance/edit_model" class="btn btn-lg btn-outline-success" style="color:white">EDIT MODELS </a>
                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-xs-3"></div>
            <div class="col-xs-12">
                <div class="box text-center">
                    <div class="box-header ">

                             <input type="text" class="form-control" style="width: 25%" placeholder="Search Model..." id="txtbx_search">
                                <h1> PSI ({{ current_revision }})  </h1>
                        <div class="box-tools">
                            <select type="text" class="form-control" v-model="selected_customers" @change='datas'>
                                <template v-for="customer in customers">
                                    <option :value="customer.customers_id"> {{customer.customers_desc }} </option>
                                </template>
                            </select>
                       
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="h2 text-black text-bold ">Category Per Month</div>
                       

                        <div class="table-bordered freeze-table">
                            <table class="table table-striped" style="color:#000;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">MODELS</th>
                                          <th class="text-center" v-for="month in Months"> {{ month?.month_year }} </th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_models">
                                    <template v-for="(cus,customer) in get_model_data">

                                        <tr v-for="(mod,models) in get_model_data[customer]">
                                            <td> {{ models }} </td>
                                           
                                           <template v-for = "month in Months">
                                            <td>
                                                <select class="form-control" @change="update_new_plan(mod[month?.month_year]?.auto_id, $event.target.value, 'category', event, $event.target.id)">
                                                   <option disabled selected>{{mod[month?.month_year]?.cat_code}}</option>
                                                    <template v-for="row in category">
                                                        <option :value="row.cat_id">{{ row.cat_code }}</option>
                                                    </template>
                                                </select>
                                            </td>
                                              </template>
                                            
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

<script>
    $(document).ready(() => {
        $("#txtbx_search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tbl_models tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<script>
    var app = new Vue({
        el: '#psi_model',
        data: {
           
            get_model: [],
            Months: [],
            get_model_data: [],
            category: [],
            customers: '',
            /* POST DATA */
            selected_customers: ' ',
             current_revision: '<?= $selectedDPRNO; ?>',

        },
        methods: {
            get_customer: function() {
                axios.post("<?= base_url() ?>index.php/Category/get_model_customers", {})
                    .then((response) => {
                        console.log(response.data);
                        app.customers = response.data.customers;
                    });
            },

            datas: function() {
                axios.post("<?= base_url() ?>index.php/Category/psi_model_maintenance", {
                        SelectedCustomers: app.selected_customers,
                    })
                    .then((response) => {
                        console.log(response.data);
                        app.get_model = response.data.get_model;
                        app.get_model_data = response.data.get_model_data;
                         app.Months = response.data.months;
                         app.category = response.data.category;
                      
                    })
            },
          
            
               GetRevisionData: function() {
                axios.post("<?= base_url() ?>index.php/Category/GetCurrentRevisionData", {
                    SelectedRevision: this.current_revision,
                }).then((response) => {
                    // console.log(response.data);
                    app.CurrentRevisionData = response.data.CurrentRevisionData;
                })
            },
            update_new_plan: function(id, value, psi, e, cellID) {
               
                    axios.post("<?= base_url() ?>index.php/Category/update_new_plan", {
                        SelectedRevision: this.current_revision,
                        id: id,
                        value: value,
                        psi: psi
                        }).then((response) => {
                    console.log(response.data);
                    })
             
            }
        },
        created: function() {
            /*this.datas();*/
            this.get_customer();
        }
    });
</script>
