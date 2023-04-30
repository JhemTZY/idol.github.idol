<div class="content-wrapper">
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
            <div class="col-md-12">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) { ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php }
                ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) { ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php }
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-10" style="position:relative; left:100px;">
                        <div class="box" style="padding:1rem ;">
                            <div class="box-header h3 text-center" style="padding: 1rem;">
                                <h3 class="box-title"> <b>REVISIONS LIST</b></h3>
                                <div class="box-tools"></div>
                                <div class="box-body  table-responsive">
                                    <table class="table table-bordered table-hover " id="tbl_revision">
                                        <thead>
                                            <tr>
                                                <td>Revision Name</td>
                                                <td>Date Created</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $revisions = $this->db->query("SELECT * FROM tbl_revision ORDER BY date_created DESC")->result();
                                            foreach ($revisions as $revision) :
                                            ?>
                                                <tr>
                                                    <td><?= $revision->revision_date ?></td>
                                                    <td><?= $revision->date_created ?></td>
                                                    <td><button data-toggle="modal" data-target="#mdl_edit_revision_name" class="btn btn-lg btn-outline-success" id="<?= $revision->ID ?>" name="<?= $revision->revision_date ?>" onclick="edit_revision(id,name)">Edit</button> </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                </div>
            </div>
        </div>

        <!-------------------------------------------------------------------------
                           *** CHANGE REVISION NAME MODAL
    --------------------------------------------------------------------------->
        <div class="modal fade text-center mx-auto" id="mdl_edit_revision_name">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content text-center mx-auto">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="modal-title fw-bold h2 text-center"><b>CHANGE REVISION NAME </b> </div>
                    </div>
                    <div class="modal-body text-center mx-auto">
                        <div class="form-group text-center mx-auto">
                            <form action="<?= base_url() ?>index.php/User_Maintenance/change_revision_name" method="POST">
                                <input class="form-control" type="text" name="RevID" id="txt_revision_ID" hidden>
                                <input class="form-control" type="text" name="OldRevName" id="txt_oldRevName" hidden/>
                                <input class="form-control" type="text" name="NewRevName" id="txt_new_revision_name" Placeholder="e.g.  REV-10-2-22" />
                                <hr>
                                <button type="submit" class="button-50 bt-1" role="button" id="btn_change_rev_name">UPDATE</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-------------------------------------------------------------------------
                         CHANGE REVISION NAME MODAL ***
    --------------------------------------------------------------------------->

    </section>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

<script>
    function edit_revision(id, name) {
        // console.log(id);
        $("#txt_oldRevName").val(name);
        $("#txt_new_revision_name").val(name);
        $("#txt_revision_ID").val(id);
    }
</script>

<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>