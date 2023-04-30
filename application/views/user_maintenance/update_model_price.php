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
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/update_model_price" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Model </a></li>
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
                <div class="box">
                    <div class="box-header text-center">
                        <div class="box-title">
                            <div class="h2 text-black text-bold ">UPDATE MODEL ACTIVE PRICE</div>
                        </div>
                        <div class="box-tools">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." id="txtbx_search">
                            </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-hover " id="tbl_models">
                            <thead>
                                <tr>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Model</th>
                                    <th class="text-center">Active Price</th>
                                    <th class="text-center">Active Status</th>
                                    <th class="text-center">Price History</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <?php foreach ($customers as $c) :
                                $models = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id='$c->customers_id' ORDER BY sorting_code ASC ")->result(); ?>
                                <tbody>
                                    <tr>
                                        <td rowspan="<?= count($models) + 2 ?>"><?= $c->customers_desc; ?></td>
                                        <?php foreach ($models as $mdl) : ?>
                                    </tr>
                                    <tr style="padding: 0;">
                                        <td><?= $mdl->models_desc ?></td>
                                        <td class="text-center"><?= $mdl->active_price ?></td>
                                        <td class="text-center"><?= $mdl->status ?></td>
                                        <td class="text-center">
                                            <select class="selecting" style="width:100px;" id="txtbx_new_active_price<?= $mdl->models_id ?>">
                                                <?php
                                                $price_history = $this->db->query("SELECT * FROM tbl_model_price_history WHERE model_id='$mdl->models_id' ")->result();
                                                foreach ($price_history as $history) :
                                                ?>
                                                    <option value="<?= $history->model_price; ?>" selected><?= $history->model_price; ?></option>
                                                <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-lg btn-outline-info" data-toggle="modal" data-target="#mdl_add_modelPriceHistory" id="<?= $mdl->models_id ?>" name="<?= $mdl->models_desc ?>" onclick="get_mdl_data(id,name,<?= $mdl->active_price ?>)"> <i class="ion-plus"></i> Add Price</button>
                                            <button class="btn btn-lg btn-outline-success" id="<?= $mdl->models_id ?>" name="<?= $mdl->models_desc ?>" onclick="update_model_active_price(id,name)">Change Price</button>
                                        </td>
                                    </tr>
                            <?php
                                        endforeach;
                                    endforeach; ?>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="">
        <div class="modal fade text-center mx-auto" id="mdl_add_modelPriceHistory">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content text-center mx-auto">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="modal-title fw-bold h1 text-center"><b>ADD NEW MODEL PRICE</b> </div>
                        <center>
                            <b>
                                <div class="h2" style="padding:0;" id="mdl_txt_modelName"></div>
                            </b>
                        </center>
                    </div>
                    <div class="modal-body text-center mx-auto">
                        <label for="">NEW PRICE</label>
                        <input class="form-control2 form-control-lg text0-center" type="text" id="txtModelid" hidden>
                        <hr>
                        <input class="form-control2 form-control-lg text0-center" type="text" id="txt_newModelPrice">
                        <hr>
                        <button class="buttons btn-block mt-3" id="btn_save_newModelPrice">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        $("#btn_save_newModelPrice").click(() => {
            NewPrice = $("#txt_newModelPrice").val();
            Model_ID = $("#txtModelid").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>index.php/User_Maintenance/add_modelPriceHistory",
                data: {
                    NewPrice,
                    Model_ID
                },
                success: (data) => {
                    // console.log(data);
                    alert_success("You saved a new Price");
                    setTimeout(() => {
                        location.reload();
                    }, 700)
                }
            });
        })
    });


    function update_model_active_price(model_id, model_name) {
        newPrice = $("#txtbx_new_active_price" + model_id).val()
        $.ajax({
            type: "POST",
            url: "<?= base_url() ?>index.php/User_Maintenance/update_model_active_price",
            data: {
                model_id,
                newPrice
            },
            success: (data) => {
                alert_success("Updated the Price of " + model_name + " to " + newPrice);
                setTimeout(() => {
                    location.reload();
                }, 3000)
            }
        })
    }

    function get_mdl_data(modelID, modelName, current_price) {
        $("#mdl_txt_modelName").html("***" + modelName + "***");
        $("#txt_newModelPrice").attr("placeholder", "ex: " + current_price)
        $("#txtModelid").val(modelID);
    }

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

<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer">
    <?php } ?>