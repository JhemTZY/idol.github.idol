<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 style="
    color: white;
">
      <i class="fa fa-user-circle-o" aria-hidden="true"></i> Datawarehouse Production Plan
      <small style="
    color: #dede29;
">Add, Edit, Revision, Delete</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12 text-right">
        <div class="form-group">
        <a class="button-73" class='clickable-row' data-toggle="modal" data-target="#myRev">New Revision</a>
          <a class="buttons button5" href="<?php echo base_url(); ?>index.php/task/PSI_Report"> <i class="fa fa-list"></i> PSI Report</a>
          <a class="buttons button3" href="<?php echo base_url(); ?>index.php/task/add"> <i class="fa fa-plus"></i> Create Plan</a>
        


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
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Datawarehouse Production Plan</h3>
            <div class="box-tools">
              <form action="<?php echo base_url(); ?>index.php/taskListing" method="POST" id="searchList">

                <div class="input-group">
                  <select type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control selecting" style="width: 150px;">
                    <option value=""><?php echo $selectedDPRNO; ?></option>


                  </select>

                  <div class="input-group-btn">
                    <a class="btn btn-lg btn-danger searchList" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i></a>
                  </div>
                </div>
              </form>
            </div>
          </div><!-- /.box-header -->

          <div class="box-body table-responsive no-padding">

            <table class="table table-bordered table-hover " id="myTable">
              <thead>
                <tr>
                  <th rowspan="2">Customer</th>
                  <th rowspan="2">Model</th>
                  <th rowspan="2">Revision</th>
                  <th rowspan="2">Month_Year Range</th>
                  <th rowspan="2">Price</th>
                  <th rowspan="2">Production Quantity</th>
                  <th rowspan="2">Sales Quantity</th>

                </tr>
              </thead>
              </tbody>

              <?php if (!empty($records)) {

                foreach ($records as $record) { ?>




                  <tr>
                    <td><?= $record->customer ?></td>
                    <td><?= $record->model ?></td>
                    <td><?= $record->revision ?></td>
                    <td><?= $record->month_year ?></td>
                    <td><?= $record->price ?></td>
                    <td><?= $record->prod_qty ?></td>
                    <td><?= $record->sales_qty ?></td>

                <?php }
              } ?>




            </table>
            <a style=" position: absolute; top: 7px; left: 273px;" class="pull-right btn btn-primary btn-xs" href="export/generateXls"><i class="fa fa-file-excel-o"></i> Export Data</a>




            <!-- The Modal -->
            <div class="modal fade" id="myModal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">REVISION</h4>

                  </div>

                  </br>
                  <!-- Modal body -->
                  <div class="modal-body">

                    <div class="row">
                      <button type="button" class="button" data-toggle="modal" data-target="#myRev">ADD</button>





                      <h1 style="position: absolute; top: -36px; left: 399px;"><?= $selectedDPRNO ?></h1>


                      <script>
                        jQuery(document).ready(function($) {
                          $(".button").click(function() {
                            window.location = $(this).data("target");
                          });
                        });
                      </script>
                      </tr>
                      </tbody>
                      </table>
                    </div>
                  </div>


                </div>
              </div><!-- /.box -->
            </div>
          </div>




          <!-- The Modal -->
          <div class="modal fade" id="myRev">
            <div class="modal-dialog modal-sm">
              <div class="modal-content" style="
      position: relative;
    top: 101px;
    left: 28px;
">

                <!-- Modal Header -->
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">REVISION
                    <small style="
    color: #4a1b9d;
">( Upload , Encode )</small>
                  </h4>

                </div>

                </br>
                <!-- Modal body -->
                <div class="modal-body">

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="role">REVISION UPLOADING</label>
                        <?php echo $error; ?>

                        <?php echo form_open_multipart('index.php/taskListing'); ?>

                        <input type="file" name="userfile" size="100" />
                      </div>
                    </div>

                    <div class="col-md-6" style="
    position: absolute;
    top: 15px;
    left: 199px;
">
                      <div class="form-group">
                        <label for="role">REVISION ENCODING</label>
                        <a class="btn btn-success" href="<?php echo base_url(); ?>index.php/revision/revision"> <i class="fa fa-plus"></i> Encoding</a>


                      </div>
                    </div>
                  </div>
                </div><!-- /.box-body -->.


                <!-- Modal footer -->
                <div class="modal-footer">
                  <input type="submit" class="btn btn-success" value="Upload">
                </div>

              </div>
            </div>
  </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>


<?php if ($page == "ps_plan_form") { ?>
  <div class="card-footer">

  <?php }
  ?>