<div class="content-wrapper">
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i> NCFL Plan <small style="color :yellow"> Prod & Sales </small>
                    </h1>
                </li>
               

              <?php
              if ($role_text == "NCFL PLAN (PSI)" || $role_text == "System Administrator" ) {
              ?>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/PSI/psi_edit" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit Mode</a></li>
                
                  <?php } ?>


                <?php
              if ($role_text == "BU HEAD" ) {
              ?>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/Psi_BU/psi_edit" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit Mode</a></li>
              
          <?php } ?>
            </ol>
        </nav>
    </section>
    <div class="col-md-4">
        <?php
        $this->load->helper('form');
        $error = $this->session->flashdata('error');
        $success = $this->session->flashdata('success');
        $warning = $this->session->flashdata('warning');

        if (isset($error)) {
        ?>
            <div class="callout callout-danger">
                <i class="icon fa fa-ban"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php } else if (isset($success)) { ?>
            <div class="callout callout-success">
                <i class="icon fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php } else if (isset($warning)) { ?>
            <div class="callout callout-warning">
                <i class="icon fa fa-warning"></i> 
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
    </br>
    </br>
    </br>
    </br>
    <!-- <section class="content" style="cursor:grab">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-danger" style="padding:15px ;">
                    <div class="box-header">
                        <h3 class="box-title">Upload Sales Planning System </h3>
                        <p style="color: #293fde;">Update Production and Sales Quantity</p>
                        <div class="box-body px-5 py-5 ">
                            <?php $this->load->helper("form"); ?>
                            <form class="px-3 py-3" action="<?php echo base_url(); ?>index.php/task/importFile" method="post" enctype="multipart/form-data">
                                Upload excel file :
                                <input type="file" name="uploadFile" id="uploadFile" value="" style="display: inline-block; padding: 6px 12px; cursor:grab; " /><br><br>
                                <input class="form-control btn btn-primary btn-lg" type="submit" name="submit" value="Upload" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->


     <div class="col-xs-6">
          <div class="box box-success box-solid">
            <div class="box-header with-border">
             <h1 class="box-title">Sales Planning System - </h3>
                            <p class="box-title" style="color: white;"> Generate Custom Months </p>

              <div class="box-tools pull-right">
               
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="card">
                            <div class="card-body">
                                <?php $this->load->helper("form"); ?>
                                <form role="form" action="<?php echo base_url() ?>index.php/task/new_plan" method="post" role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Customer</label>
                                                <select class="form-control selecting" id="customers" name="customers" maxlength="256">
                                                    <option value="">Please select a Customer</option>
                                                    <?php $selected = "";
                                                    foreach ($customers as $cusRows) :
                                                        $selected = $cusRows->customers_id == $taskTitle ? "selected" : ""; ?>
                                                        <option value="<?php echo $cusRows->customers_id; ?>" <?php echo $selected; ?>>
                                                            <?php echo $cusRows->customers_desc; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="models_details_id">Model</label> <input type="checkbox" id="checkbox" style="width:20px; height:20px; cursor:grab;"> Select All
                                                <select class="form-control selecting" id="models_details_id" name='models_details_id[]' multiple>

                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Revision</label>
                                                <input type="text" placeholder="Revision" class="form-control" name="model_revision" value="<?= $selectedDPRNO; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Start Month</label>
                                                <input class="form-control" id="start_month" name="start_month" value="August-2022" readonly>
                                                <!-- <?php echo $selectedMonth ; ?> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Number of Months</label>
                                                <input class="form-control" id="" name="numberofMonths" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-primary rounded btn-lg btn-block">Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
            </div>
            <!-- /.box-body -->
       
            </div>
        </div>


        <div class="col-md-6">
          <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">   <h1 class="box-title">Sales Planning System - </h3>
                            <p class="box-title" style="color: white;"> Generate 1 Month</p></h3>

              <div class="box-tools pull-right">
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div class="card-body">
                                <?php $this->load->helper("form"); ?>
                                <form role="form" action="<?php echo base_url() ?>index.php/task/new_plan_one_month" method="post" role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Customer</label>
                                                <select class="form-control selecting" id="customers2" name="customers" maxlength="256">
                                                    <option value="">Please select a Customer</option>
                                                    <?php $selected = "";
                                                    foreach ($customers as $cusRows) :
                                                        $selected = $cusRows->customers_id == $taskTitle ? "selected" : ""; ?>
                                                        <option value="<?php echo $cusRows->customers_id; ?>" <?php echo $selected; ?>>
                                                            <?php echo $cusRows->customers_desc; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="models_details_id2">Model</label> <input type="checkbox" id="checkbox2" style="width:20px; height:20px; cursor:grab;">Select All
                                                <select class="form-control selecting" id="models_details_id2" name='models_details_id[]' multiple> </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Revision</label>
                                                <input type="text" placeholder="Revision" class="form-control" name="model_revision" value="<?= $selectedDPRNO; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="description">Start Month</label>
                                                <input type="month" class="form-control" id="oneMonth" name="oneMonth">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <button class="btn btn-primary button-3 rounded btn-lg btn-block" id="btn_generate_1_month">Generate</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
             </div>





     



<script src="<?php echo base_url(); ?>assets/js/addRole.js" type="text/javascript"></script>


<script>
    $(document).ready(() => {
        // $("#btn_generate_1_month").click(() => {
        //     Swal.fire({
        //         position: 'center',
        //         icon: 'success',
        //         title: 'Your work has been saved',
        //         showConfirmButton: false,
        //         timer: 5000
        //     })
        // });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#customers').change(function() {
            var id = $(this).val();

            $.ajax({
                url: "<?php echo site_url('index.php/task/get_model_customer'); ?>",
                method: "POST",
                data: {
                    id: id
                },
                async: true,
                dataType: 'json',
                success: function(data) {

                    var html = '';
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<option value=' + data[i].models_id + '>' + data[i].models_desc + '</option>';
                    }
                    $('#models_details_id').html(html);
                }
            });
            return false;
        });
        $('#customers2').change(function() {
            var id = $(this).val();

            $.ajax({
                url: "<?php echo site_url('index.php/task/get_model_customer'); ?>",
                method: "POST",
                data: {
                    id: id
                },
                async: true,
                dataType: 'json',
                success: function(data) {

                    var html = '';
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<option value=' + data[i].models_id + '>' + data[i].models_desc + '</option>';
                    }
                    $('#models_details_id2').html(html);
                }
            });
            return false;
        });
    });
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('ul.pagination li a').click(function(e) {
            e.preventDefault();
            var link = jQuery(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "index.php/add/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>

<?php
if ($page == "ps_plan_form") {
?>
    <div class="card-footer">

    <?php
}
    ?>



    <script>
        $("#checkbox").click(function() {
            if ($("#checkbox").is(':checked')) {
                $("#models_details_id > option").prop("selected", "selected");
                $("#models_details_id").trigger("change");
            } else {
                $("#models_details_id > option").removeAttr("selected");
                $("#models_details_id").trigger("change");
            }
        });

        $("#button").click(function() {
            alert($("#models_details_id").val());
        });
    </script>





    <script>
        $("#checkbox2").click(function() {
            if ($("#checkbox2").is(':checked')) {
                $("#models_details_id2 > option").prop("selected", "selected");
                $("#models_details_id2").trigger("change");
            } else {
                $("#models_details_id2 > option").removeAttr("selected");
                $("#models_details_id2").trigger("change");
            }
        });

        $("#button").click(function() {
            alert($("#models_details_id2").val());
        });
    </script>


    </body>

    </html>