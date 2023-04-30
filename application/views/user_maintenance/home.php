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

                  <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/Update_customer_isActive" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Customer </a></li>

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
            


                    <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-aqua">
              <span class="info-box-icon"><i class="fa fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Models</span>
                <span class="info-box-number"><?php echo count($models); ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: <?php echo count($models); ?>%"></div>
                </div>
                <span class="progress-description">
                <?php echo count($models); ?> Models Available
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->


          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-green">
              <span class="info-box-icon"><i class="ion ion-ios-people"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Customers</span>
                <span class="info-box-number"><?php echo count($customers); ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: <?php echo count($customers); ?>%"></div>
                </div>
                <span class="progress-description">
                <?php echo count($customers); ?> Customers Available
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
                    

          
              
    
        <!-- <div class="box" style="padding:1rem ;">
                    <div class="box-header h3" style="padding: 1rem;">
                        <h3 class="box-title"> HOME </h3>
                        <div class="box-tools">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." id="txtbx_search">
                            </div>
                        </div>
                        <div class="box-body px-3 py-3 table-responsive">
                            <table class="table table-bordered table-hover " id="tbl_models">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Model</th>
                                        <th>Active Price</th>
                                    </tr>
                                </thead>
                                <?php foreach ($customers as $c) :
                                    $models = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id='$c->customers_id' ")->result(); ?>
                                    <tbody>
                                        <tr>
                                            <td rowspan="<?= count($models) + 2 ?>"><?= $c->customers_desc; ?></td>
                                            <?php foreach ($models as $mdl) : ?>
                                        </tr>
                                        <tr>
                                            <td><?= $mdl->models_desc ?></td>
                                            <td> <?= $mdl->active_price ?></td>
                                        </tr>
                                <?php
                                            endforeach;
                                        endforeach; ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->

                
    </section>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>


<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>