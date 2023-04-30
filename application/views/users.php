<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="color :white">
            <i class="ion ion-android-people"></i> User Management
            <small style="color :yellow">Add, Edit, Delete</small>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>index.php/addNewUser"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) {
                ?>
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
       <div class="row">
                 <div class="col-lg-12">
          <div class="box box-primary box-solid">
            <div class="box-header with-border">
              <h1 class="box-title">Customers Maintenance</h1>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
             <div class="box-body">
                  
                        <hr>
                        <div class="table-responsive freeze-table">
                            <table class="table table-bordered table-hover" id="tbl_models">
                                <thead>
                                    <tr>
                                <th>ID NUMBER</th>
                                <th>NAME</th>
                                <th>PASSWORD</th>
                                <th>ROLE</th>
                                <th>CREATED ON</th>
                                         </tr>
                                </thead>
                                <tbody>
                                   
                            <?php
                            if (!empty($userRecords)) {
                                foreach ($userRecords as $record) {
                            ?>
                                        <tr>
                                            <td><?php echo $record->email ?></td>
                                        <td><?php echo $record->name ?></td>
                                        <td><?php echo $record->pass ?></td>
                                        <td><?php echo $record->role ?></td>
                                        <td><?php date_default_timezone_set("Asia/Manila");
                                            echo date("Y-m-d"); ?></td>
                                        </tr>
                                  <?php
                                }
                            }
                            ?>
                        </table>
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
  $(function () {
    $("#tbl_models").DataTable();
 
  });
</script>

