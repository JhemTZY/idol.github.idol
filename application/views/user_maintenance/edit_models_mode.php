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
                     <a href="<?= base_url() ?>index.php/Category/Category" class="btn btn-lg btn-outline-success" style="color:white">CATEGORY</a>
                    <a href="<?= base_url() ?>index.php/User_Maintenance/update_model_price" class="btn btn-lg btn-outline-success" style="color:white">PRICE UPDATE </a>
                    <a href="<?= base_url() ?>index.php/User_Maintenance/edit_model" class="btn btn-lg btn-outline-success" style="color:white">EDIT MODELS </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header ">
                        <div class="box-title">
                            <input type="text" class="form-control" placeholder="Search Model..." id="txtbx_search">
                        </div>

                        <div class="box-tools">
                            <select type="text" class="form-control" v-model="selected_customers" @change='datas'>
                                <template v-for="customer in customers">
                                    <option :value="customer.customers_id"> {{customer.customers_desc }} </option>
                                </template>
                            </select>
                            
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="h2 text-black text-bold ">MODEL MAINTENANCE</div>
                       

                        <div class="table-bordered freeze-table">
                            <table class="table table-striped" style="color:#000;">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">MODELS</th>
                                        <th style="text-align: center;">ITEMCODE</th>
                                        <th style="text-align: center;">Active or Inactive</th>
                                        <th style="text-align: center;">Hide Models</th>
                                        <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">SORTING CODE</th>
                                        <th style="text-align: center;">REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_models">
                                    <template v-for="(cus,customer) in get_model_data">
                                        <tr v-for="(mod,models) in get_model_data[customer]">
                                            <td> {{ mod.models_desc }} </td>
                                            <td>
                                                <input class="form-control" type="text" :value="mod.item_code" @keyup="update_tbl_models(mod.models_id, $event.target.value, 'itemCode')">
                                            </td>
                                            <td>
                                                <input v-if='mod.status=="Active"' checked type="checkbox" class="larger" style="width: 50px; height:50px;" value="Inactive" @change="update_tbl_models(mod.models_id, $event.target.value, 'Checking')">
                                                <input v-if='mod.status=="Inactive"' type="checkbox" class="larger" style="width: 50px; height:50px;" value="Active" @change="update_tbl_models(mod.models_id, $event.target.value, 'Checking')">
                                            </td>

                                             

                                                <td>
                                                
                                                <input v-if='mod.isHidden=="0"' checked type="checkbox" class="larger" style="width: 50px; height:50px;" value="1" @change="update_tbl_models(mod.models_id, $event.target.value, 'Checkbox')">
                                                <input v-if='mod.isHidden=="1"' type="checkbox" class="larger" style="width: 50px; height:50px;" value="0" @change="update_tbl_models(mod.models_id, $event.target.value, 'Checkbox')">
                                            </td>

                                             <td>
                                                <select class="form-control" @change="update_tbl_models(mod.models_id, $event.target.value, 'Category')">
                                                   <option disabled selected>{{mod.cat_code}}</option>
                                                    <template v-for="row in category">
                                                        <option :value="row.cat_id">{{ row.cat_code }}</option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td> <input class="form-control" type="text" :value="mod.sorting_code" @keyup="update_tbl_models(mod.models_id, $event.target.value, 'SortCode')"></td>
                                            <td><textarea class="form-control" :value="mod.model_remarks" @keyup="update_tbl_models(mod.models_id, $event.target.value, 'Remarks')"></textarea></td>
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
            category: [],
            get_model_data: [],
            customers: '',
            /* POST DATA */
            selected_customers: ' ',

        },
        methods: {
            get_customer: function() {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/get_model_customers", {})
                    .then((response) => {
                        console.log(response.data);
                        app.customers = response.data.customers;
                    });
            },

            datas: function() {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/psi_model_maintenance", {
                        SelectedCustomers: app.selected_customers,
                    })
                    .then((response) => {
                        console.log(response.data);
                        app.get_model = response.data.get_model;
                        app.get_model_data = response.data.get_model_data;
                        app.category = response.data.category;
                    })
            },
            update_tbl_models: function(id, value, column) {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/update_tbl_models", {
                    SelectedCustomers: app.selected_customers,
                    id: id,
                    value: value,
                    column: column
                }).then((response) => {
                    // console.log(response.data);
                    this.datas();
                })
            },

        },
        created: function() {
            /*this.datas();*/
            this.get_customer();
        }
    });
</script>


<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>