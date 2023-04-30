<div class="content-wrapper" id="app">
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
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/update_model_price" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Model</a></li>
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
        </nav>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="box" style="padding:10px;">
                    <div class="box-header">
                        <div class="box-tools">
                            <!-- <button class="btn btn-lg    btn-success"> <i class="fa fa-plus"></i> Add PSI Approver</button> -->
                        </div>
                    </div>
                    <div class="box-body">
                        <!-- <hr style="border-width: 5px ;"> -->
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id = "tbl_models">
                                    <thead>
                                        <th> <i class="fa fa-user" style="color:#000;"></i> Approver </th>
                                        <th> <i class="fa fa-envelope-o" style="color:#000;"></i> Email</th>
                                        <th> <i class="fa fa-vcard-o" style="color:#000;"></i> Header</th>
                                        <th> <i class="fa fa-th-list" style="color:#000;"></i> Hierarchy</th>
                                        <th> <i class="fa fa-podcast" style="color:#000;"></i> Active Status</th>
                                        <th> <i class="fa fa-gear" style="color:#000;"></i> Action</th>
                                    </thead>
                                    <tbody>
                                        <template v-for="psiApprover in psiApprovers">
                                            <tr>
                                                <td> {{ psiApprover.name }} </td>
                                                <td> {{ psiApprover.email }} </td>
                                                <td> {{ psiApprover.header }} </td>
                                                <td> {{ psiApprover.hierarchy_order }} </td>
                                                <td v-if='psiApprover.isActive==1'> Active </td>
                                                <td v-if='psiApprover.isActive!=1'> Inactive </td>
                                                <td> <button class="btn btn-lg bg-olive-active " data-toggle="modal" data-target="#modal_updateApprover" @click="SelectApprover(psiApprover.auto_id,psiApprover.header,psiApprover.email,psiApprover.hierarchy_order,psiApprover.name,psiApprover.approver_id,psiApprover.isActive)">Update</button> </td>
                                            </tr>
                                        </template>
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- *** UPDATE APPROVER MODAL -->
    <div class="modal fade" id="modal_updateApprover">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-olive-active">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">UPDATE APPROVER DATA </h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label> SELECT APPROVER</label>
                        <select class="form-control" v-model='NewApproverData.userID'>
                            <option :value="NewApproverData.userID">Current: {{ NewApproverData.name }} </option>
                            <template v-for="AllUser in AllUsers">
                                <option :value="AllUser.userId"> {{ AllUser.name }} </option>
                            </template>
                        </select>
                    </div>
                    <div class="form-group">
                        <label> APPROVER EMAIL</label>
                        <input class="form-control" type="text" v-model="NewApproverData.email">
                    </div>
                    <div class="form-group">
                        <label> HEADER </label>
                        <input class="form-control" type="text" v-model="NewApproverData.header">
                    </div>
                    <div class="form-group">
                        <label> HIERARCHY </label>
                        <input class="form-control" type="number" v-model="NewApproverData.hierarchy">
                    </div>
                    <div class="form-group">
                        <label> STATUS </label>
                        <select class="form-control" v-model="NewApproverData.activeStatus">
                            <option value="1">ACTIVE</option>
                            <option value="0">INACTIVE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn bg-olive-active btn-block btn-lg" @click="UpdateApprover()">UPDATE</button>

                </div>
            </div>
        </div>
    </div>
    <!-- UPDATE APPROVER MODAL*** -->

</div>

<!-- *** VUEJS AND AXIOS -->
<script type="text/javascript" src="<?php echo base_url('assets/vuejs/vue.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/axios/axios.min.js'); ?>"></script>
<!-- VUEJS AND AXIOS *** -->



<script>
  $(function () {
    $("#tbl_models").DataTable();
 
  });
</script>


<script>
    var app = new Vue({
        el: '#app',
        data: {
            NewApproverData: {
                autoID: '',
                userID: '',
                name: '',
                email: '',
                header: '',
                hierarchy: '',
                activeStatus: '',
            },

            psiApprovers: [],
            AllUsers: [],
        },
        methods: {
            GetApprovers: function() {
                axios.post("<?= base_url() ?>index.php/User_Maintenance/GetApprovers", {}).then((response) => {
                    app.psiApprovers = response.data.PSI_Approvers;
                    app.AllUsers = response.data.all_users;
                });
            },
            SelectApprover: function(approverId, approverHeader, approverEmail, hierarchy, name, userID, status) {
                app.NewApproverData.autoID = approverId;
                app.NewApproverData.userID = userID;
                app.NewApproverData.email = approverEmail;
                app.NewApproverData.header = approverHeader;
                app.NewApproverData.hierarchy = hierarchy;
                app.NewApproverData.name = name;
                app.NewApproverData.activeStatus = status;
            },
            UpdateApprover: function() {
                // console.log(app.NewApproverData);
                axios.post("<?= base_url() ?>index.php/User_Maintenance/UpdateApprover", {
                    NewApproverData: app.NewApproverData
                }).then((response) => {
                    // console.log(response.data);
                    this.GetApprovers();

                });
                      Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "Save !",
                timer: 2000, 
                showConfirmButton: false,
            });

                        }
        },
        created: function() {
            this.GetApprovers();
        }
    })
</script>