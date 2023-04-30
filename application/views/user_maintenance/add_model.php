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
            <div class="col-lg-12">
                <div class="row">
                    <!-- <div class="col-md-3">
                        <div class="box box-danger" style="padding:15px ;">
                            <div class="box-header">
                                <h3 class="box-title">Upload New Model </h3>
                                <div class="box-body px-5 py-5 ">
                                    <?php $this->load->helper("form"); ?>
                                    <form class="px-3 py-3" action="<?php echo base_url(); ?>index.php/task/importFile" method="post" enctype="multipart/form-data">
                                        <input type="file" name="uploadFile" id="uploadFile" value="" style="display: inline-block; padding: 6px 12px; cursor:grab; " /><br><br>
                                        <input class="form-control btn btn-primary btn-lg" type="submit" name="submit" value="Upload" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-4">
                        <div class="box box-primary" style="padding:1rem ;">
                            <div class="box-header" style="padding: 0;">
                                <h1 class="text-center" style="font-weight:bolder ;">MODEL</h1>
                                <hr>
                            </div>
                            <div class="box-body">
                                <!-- <form>  -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="txt_model_item_code" required placeholder="Item Code :">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="txt_model_name" required placeholder="Model Name :">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="txt_model_price" required placeholder="Price">
                                    </div>
                                    <br>
                                    <div class="input-group">

                                        <select class="form-control" id="txt_model_Category" required>
                                            <option disabled selected>Category :</option>
                                            <?php foreach ($category as $cat) : ?>
                                                <option value="<?= $cat->cat_id ?>"><?= $cat->cat_code ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <select class="form-control" id="txt_selected_customer" required>
                                            <option disabled selected>Customer Name :</option>
                                            <?php foreach ($customers as $c) : ?>
                                                <option value="<?= $c->customers_id ?>"><?= $c->customers_desc ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <button class="text-center btn btn-lg btn-outline-dark btn-block" id="btn_add_new_model">SUBMIT</button>
                                    <!-- </form> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box box-danger" style="padding:1rem ;">
                            <div class="box-header" style="padding: 0;">
                                <h1 class="text-center" style="font-weight:bolder ;">CUSTOMER</h1>
                                <hr>
                            </div>
                            <div class="box-body text-center">
                                <input type="text" class="form-control form-control-lg text-center" id="txt_customer_code" placeholder="Customers Code" required> <br>
                                <input type="text" class="form-control form-control-lg text-center" id="txt_customer_name" placeholder="Customer Name" required><br>
                                <button class="text-center btn btn-lg btn-outline-dark btn-block" type="submit" id="btn_add_new_customer">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>




<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>

<script>
    $(document).ready(() => {
        $("#btn_add_new_model").click(() => {
            var Item_Code = $("#txt_model_item_code").val();
            var model_name = $("#txt_model_name").val();
            var model_Price = $("#txt_model_price").val();
            var Category = $("#txt_model_Category").val();
            var customerId = $("#txt_selected_customer").val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>index.php/User_Maintenance/add_new_model",
                data: {
                    itemCode: Item_Code,
                    modelName: model_name,
                    modelPrice: model_Price,
                    CATEGORY: Category,
                    customerID: customerId

                },
                success: (data) => {
                    console.log(data);
                    if (data == 1) {
                          Swal.fire("SAVE DATA!", "success");
                                     Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "SAVE DATA!",
                text: "Check your New Model !",
                 timer: 2000,
                showConfirmButton: false,
            })
                        setTimeout(() => {
                            location.reload();
                        }, 3000)
                    } else if (data == 0) {
                               Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Something is wrong",
                text: "Please Check your fill out form !",
                 timer: 2000,
                showConfirmButton: false,
            })
                    }
                }
            })
        });

        $("#btn_add_new_customer").click(() => {
            var customer_name = $("#txt_customer_name").val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>index.php/User_Maintenance/add_new_customer",
                data: {
                    customerName: customer_name,
                },
                success: (data) => {
                    // alert_success(data);
                    if (data == 1) {
                          Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "SAVE DATA!",
                text: "Check your New Model !",
                 timer: 2000,
                showConfirmButton: false,
            })
                        setTimeout(() => {
                            location.reload();
                        }, 3000)
                    } else if (data == 0) {
                                     Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Something is wrong",
                text: "Please Check your fill out form !",
                 timer: 2000,
                showConfirmButton: false,
            })
                    }
                }
            })
            alert_success();
        })

        function alert_success(message) {
             Swal.fire({
                position: 'center',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 3000
            })
        }

        function alert_err(message) {
             Swal.fire({
                position: 'center',
                icon: 'warning',
                title: message,
                showConfirmButton: false,
                timer: 3000
            })
        }
    });
</script>


<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>