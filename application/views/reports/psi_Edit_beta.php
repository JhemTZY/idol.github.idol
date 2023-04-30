<!-- *** Content wrapper -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa ion-ios-list" aria-hidden="true"></i> NCFL Plans
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/PSI_EDIT_BETA" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit Mode</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/add" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-folder-o"></i> Create Plan</a></li>
                <?php
                $RevStatus = $this->db->query("SELECT status FROM tbl_revision WHERE revision_date='$selectedDPRNO' ")->row()->status;
                if ($RevStatus !== "Pending" && $RevStatus !== "Rejected") {
                ?>
                    <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-warning" style="color:white; " data-toggle="modal" data-target="#create_new_revision_modal"> <i class="fa fa-paperclip"></i> New Revision</a></li>
                <?php } else { ?>
                    <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-warning disabled" style="color:white; "> <i class="fa fa-paperclip"></i> New Revision</a></li>
                <?php } ?>

                <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-info" style="color:white; " data-toggle="modal" data-target="#generate_upload_file"> <i class="fa fa-download"></i> Download</a></li>
                <li class="breadcrumb-item"><a class="btn btn-lg btn-outline-info" style="color:white; " data-toggle="modal" data-target="#upload_plan_modal"> <i class="fa fa-upload"></i> Upload</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/User_Maintenance/home" class="btn btn-lg btn-dark" style="color:white; "> <i class="fa fa-gear"></i> Maintenance</a></li>


            </ol>
        </nav>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                $success = $this->session->flashdata('success');
                $warning = $this->session->flashdata('warning');

                if (isset($error)) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } else if (isset($success)) { ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } else if (isset($warning)) { ?>
                    <div class="alert alert-warning">
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
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <div class="box-tools">
                    <button class="btn btn-info btn-md" id="btn_ViewPSIApprovers"> <i class="fa fa-users"></i> <small> APPROVERS STATUS</small> </button>
                    <button class="btn btn-info btn-md" id="btn_submit_psi"> <i class="fa fa-envelope"></i> <small> SUBMIT PSI</small> </button>
                    <a href="<?= base_url() ?>index.php/task/psi_revision_viewing" class="btn btn-success btn-md " type="submit"> <i class="fa fa-eye"></i> VIEW PSI </a>

                </div>
            </div>
            <br>
            <div class="box-header">
                <div class="row" id="psi_approvers_badge">
                    <?php
                    $approvers = $this->db->query(
                        "SELECT tbl_approver.approver_id,header,name,role,hierarchy_order,tbl_approved_psi.status, tbl_revision.revision_date ,tbl_approved_psi.remarks
                        FROM tbl_approver 
                        INNER JOIN tbl_users 
                        ON tbl_approver.approver_id=tbl_users.userId 
                        INNER JOIN tbl_roles
                        ON tbl_users.roleId=tbl_roles.roleId
                        INNER JOIN tbl_approved_psi
                        ON tbl_approver.approver_id = tbl_approved_psi.approver_id
                        INNER JOIN tbl_revision
                        ON tbl_approved_psi.rev_id=tbl_revision.ID
                        WHERE documentType='PSI'
                        AND tbl_revision.revision_date='$selectedDPRNO'
                        ORDER BY hierarchy_order ASC
                         "
                    )->result();
                    foreach ($approvers as $approver) :
                    ?>
                        <div class="col-md-4">
                            <?php
                            if ($approver->status == 1) {
                                echo "<div class='info-box bg-green'> <span class='info-box-icon'><i class='fa fa-thumbs-up'></i></span>";
                            } else if ($approver->status == "Rejected") {
                                echo "<div class='info-box bg-red'> <span class='info-box-icon'><i class='fa fa-thumbs-down'></i></span>";
                            } else {
                                echo "<div class='info-box bg-aqua'> <span class='info-box-icon'><i class='fa fa-circle-o'></i></span>";
                            }
                            ?>

                            <div class="info-box-content">
                                <span class="info-box-text"><?= $approver->header; ?>
                                    <?php if (!empty($approver->remarks)) { ?>
                                        <a href="#message" data-toggle="tooltip" data-placement="top" title="<?= $approver->remarks; ?>"><i class='fa fa-envelope'></i></a></span>
                            <?php } ?>
                            <span class="info-box-number"><?= $approver->name; ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <?= $approver->role; ?>
                            </span>
                            </div>
                        </div>
                </div>
            <?php
                    endforeach;
            ?>
            </div>


            <hr>
            <div class="box-header ">
                <?php $revision_status = $this->db->query('SELECT status FROM tbl_revision WHERE isActive="1" ')->row(); ?>
                <h3 class="box-title tags"> <a class="color2">EDIT MODE</a>
                    NCFL Plan (<?= $selectedDPRNO; ?> ) -
                    <?php if (isset($revision_status)) {
                        echo strtoupper($revision_status->status);
                    } ?>
                </h3>
            </div>

            <?php
            if (isset($current_rev) && $selectedDPRNO != "" && count($customers) > 0) {
            ?>
                <div class="table-responsive freeze-table">
                    <table class="table table-bordered table-hover " id="tbl_models">
                        <thead class="small lead text-center">
                            <tr>
                                <th rowspan="2">Customer</th>
                                <th rowspan="2">Model</th>
                            </tr>
                            <tr>
                                <?php foreach ($current_rev as $cur_rev) : ?>
                                    <th rowspan="2" colspan="4">
                                        <?= $cur_rev->month_year ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <th rowspan="2"> </th>
                                <th rowspan="2"> </th>
                            </tr>
                            <tr>
                                <?php foreach ($current_rev as $cur_rev) : ?>
                                    <th>Monthly Price</th>
                                    <th>Production Quantity</th>
                                    <th>Sales Quantity</th>
                                    <th>Inventory</th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($customers as $c) :
                                $models = $this->db->query("SELECT  * FROM tbl_models WHERE customer_models_id='$c->customers_id' AND status='active' ORDER BY sorting_code ASC ")->result();
                                if (count($models) > 0) {
                            ?>
                                    <tr>
                                        <td class="text-bold" rowspan="<?= count($models) + 2 ?>"><?= $c->customers_desc; ?> </td>
                                        <?php foreach ($models as $model) : ?>
                                    </tr>
                                    <?php
                                            $models_prices = $this->db->query("SELECT DISTINCT active_price FROM tbl_models WHERE models_id = '$model->models_id' AND status='active'  ")->row();
                                            foreach ($models_prices as $models_price) :
                                    ?>
                                        <td class="text-bold" rowspan="<?= count($models_price) ?>" id="lbl_models_desc" name="<?php echo $model->models_id; ?>" oncontextmenu="rclick_model(<?php echo $model->models_id ?>)"><?php echo $model->models_desc; ?> </td>
                                        <?php
                                                $models_datas = $this->db->query("SELECT * FROM tbl_new_plan INNER JOIN tbl_models ON tbl_new_plan.model=tbl_models.models_id WHERE model='$model->models_id' AND tbl_models.status='active' AND revision='$selectedDPRNO' AND month_year_numerical BETWEEN '$psiEdit_dateFrom' AND '$psiEdit_dateTo' ORDER BY month_year_numerical ASC  ")->result();
                                                $inv = 0;
                                                foreach ($models_datas as $models_data) :
                                                    $inv +=  ($models_data->prod_qty  - $models_data->sales_qty);

                                                    // Compute total of production quantity per customer per month
                                                    if (isset($customer_total[$models_data->customer]['prod_qty'][$models_data->month_year])) {
                                                        $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] += $models_data->prod_qty;
                                                    } else {
                                                        $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] = $models_data->prod_qty;
                                                    }
                                                    // Compute total of production amount per customer per month
                                                    if (isset($customer_total[$models_data->customer]['prod_amount'][$models_data->month_year])) {
                                                        $customer_total[$models_data->customer]['prod_amount'][$models_data->month_year] += $models_data->prod_qty * $models_data->price;
                                                    } else {
                                                        $customer_total[$models_data->customer]['prod_amount'][$models_data->month_year] = $models_data->prod_qty * $models_data->price;
                                                    }
                                                    // Compute total of sales quantity per customer per month
                                                    if (isset($customer_total[$models_data->customer]['sales_qty'][$models_data->month_year])) {
                                                        $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year] += $models_data->sales_qty;
                                                    } else {
                                                        $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year] = $models_data->sales_qty;
                                                    }
                                                    // Compute total of sales amount per customer per month
                                                    if (isset($customer_total[$models_data->customer]['sales_amount'][$models_data->month_year])) {
                                                        $customer_total[$models_data->customer]['sales_amount'][$models_data->month_year] += $models_data->sales_qty * $models_data->price;
                                                    } else {
                                                        $customer_total[$models_data->customer]['sales_amount'][$models_data->month_year] = $models_data->sales_qty * $models_data->price;
                                                    }

                                                    // Compute total of INVENTORY AMOUNT per customer per month
                                                    if (isset($inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year])) {
                                                        $inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year] += $inv * $models_data->price;
                                                    } else {
                                                        $inventoryAmount_Total[$models_data->customer]['inventory_amount_total'][$models_data->month_year] = $inv * $models_data->price;
                                                    }

                                                    // Compute Grand total of INVENTORY AMOUNT per customer per month
                                                    if (isset($invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year])) {
                                                        $invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year] += $inv * $models_data->price;
                                                    } else {
                                                        $invAmount_grandTotal['invAmount_grandTotal'][$models_data->month_year] = $inv * $models_data->price;
                                                    }

                                                    // Compute Grand total of sales quantity 
                                                    if (isset($g_total['sales_qty'][$models_data->month_year])) {
                                                        $g_total['sales_qty'][$models_data->month_year] += $models_data->sales_qty;
                                                    } else {
                                                        $g_total['sales_qty'][$models_data->month_year] = $models_data->sales_qty;
                                                    }
                                                    // Compute Grand total of sales amount 
                                                    if (isset($g_total['sales_amount'][$models_data->month_year])) {
                                                        $g_total['sales_amount'][$models_data->month_year] += $models_data->sales_qty * $models_data->price;
                                                    } else {
                                                        $g_total['sales_amount'][$models_data->month_year] = $models_data->sales_qty * $models_data->price;
                                                    }
                                                    // Compute Grand total of production quantity 
                                                    if (isset($g_total['prod_qty'][$models_data->month_year])) {
                                                        $g_total['prod_qty'][$models_data->month_year] += $models_data->prod_qty;
                                                    } else {
                                                        $g_total['prod_qty'][$models_data->month_year] = $models_data->prod_qty;
                                                    }
                                                    // Compute Grand total of production amount 
                                                    if (isset($g_total['prod_amount'][$models_data->month_year])) {
                                                        $g_total['prod_amount'][$models_data->month_year] += $models_data->prod_qty * $models_data->price;
                                                    } else {
                                                        $g_total['prod_amount'][$models_data->month_year] = $models_data->prod_qty * $models_data->price;
                                                    }
                                        ?>
                                            <td class="text-right"> <a style='cursor:cell;' class='text-black' data-toggle='modal' data-target='#update_price' id='<?= $models_data->auto_id ?>' onclick='get_models_by_id(id)'><?= $models_data->price ?></a></td>
                                            <td> <input class="text-right form-control input-sm testing" style='cursor:cell; width:100%; padding:3px;' type="text" onclick="set_cell(id)" onkeyup="update_prod_quantity(<?= $models_data->auto_id ?>,value)" value="<?= $models_data->prod_qty ?>"></td>
                                            <td> <input class="text-right form-control input-sm testing" style='cursor:cell; width:100%; padding:3px;' type="text" onclick="set_cell(id)" onkeyup="update_sales_quantity(<?= $models_data->auto_id ?>,value)" value="<?= $models_data->sales_qty ?>"></td>
                                            <td> <input class="text-right form-control input-sm " style='cursor:cell; width:100%; padding:3px;' readonly value="<?= $inv ?>"> </td>
                            <?php
                                                endforeach;
                                            endforeach;
                                        endforeach;
                                    }
                            ?>
                        </tbody>
                        <tbody class="text-right">
                            <tr class="bg-info">
                                <?php
                                if (count($models) > 0) {
                                ?>
                                    <td colspan='2'>Total</td>
                                    <?php
                                    $inv = 0;
                                    $models_datas = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$selectedDPRNO' AND month_year_numerical BETWEEN '$psiEdit_dateFrom' AND '$psiEdit_dateTo' ORDER BY month_year_numerical ASC  ")->result();
                                    foreach ($models_datas as $models_data) :
                                        $inv += $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] - $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year]; ?>
                                        <td> </td>
                                        <td class='h4'><?= $customer_total[$models_data->customer]['prod_qty'][$models_data->month_year] ?></td>
                                        <td class='h4'><?= $customer_total[$models_data->customer]['sales_qty'][$models_data->month_year] ?></td>
                                        <td class='h4'><?= $inv ?></td>
                                <?php
                                    endforeach;
                                } ?>
                            </tr>
                        </tbody>

                    <?php endforeach; ?>
                    <tr class="h3 bg-blue">
                        <td colspan="2">Grand Total</td>
                        <?php
                        $inv = 0;
                        $models_datas = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$model->models_id' AND revision='$selectedDPRNO' AND month_year_numerical BETWEEN '$psiEdit_dateFrom' AND '$psiEdit_dateTo' ORDER BY month_year_numerical ASC  ")->result();
                        foreach ($models_datas as $models_data) :
                            $inv += $g_total['prod_qty'][$models_data->month_year] - $g_total['sales_qty'][$models_data->month_year]; ?>
                            <td> </td>
                            <td class='h4'><?= $g_total['prod_qty'][$models_data->month_year] ?></td>
                            <td class='h4'><?= $g_total['sales_qty'][$models_data->month_year] ?></td>
                            <td class='h4'><?= $inv ?></td>
                        <?php endforeach; ?>
                    </tr>
                    </table>
                <?php } else {
                echo "<center><b>NO DATA AVAILABLE</b></center>";
            } ?>
                </div>
        </div>
    </section>



    <!-- ***UPDATE MODEL PRODUCTION QUANTITY MODAL -->
    <div class="modal fade" id="update_prod_qty">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">UPDATE PRODUCTION QUANTITY - <b id="modelname1"></b> -
                        <b id="model_month_year1"></b> </h4>
                </div>
                </br>
                <div class="modal-body">
                    <div class="card shadow px-3 py-3">
                        <form action="<?php echo base_url(); ?>index.php/task/update_model_prod_qty" method="POST">
                            <div class="input-group">
                                <label for="" class="form-control">CURRENT PRODUCTION
                                    QUANTITY</label>
                                <input type="text" class="form-control" id="current_prod_qty" readonly>
                            </div>
                            <br>
                            <div class="input-group">
                                <label class="form-control">NEW PRODUCTION QUANTITY</label>
                                <input type="text" class="form-control" name="modelID" id="model_id1" hidden>
                                <input type="text" class="form-control" name="new_prod_qty" required>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block btn-lg">UPDATE</button>
                    </form>
                </div>
            </div>
        </div><!-- /.box -->
    </div>
    <!-- UPDATE MODEL PRODUCTION QUANTITY MODAL*** -->

    <!-- ***UPDATE MODEL SALES QUANTITY MODAL -->
    <div class="modal fade" id="update_sales_qty">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">UPDATE SALES QUANTITY - <b id="modelname2"></b> - <b id="model_month_year2"></b> </h4>
                </div>
                </br>
                <div class="modal-body">
                    <div class="card shadow px-3 py-3">
                        <form action="<?php echo base_url(); ?>index.php/task/update_model_sales_qty" method="POST">
                            <div class="input-group">
                                <label for="" class="form-control">CURRENT SALES QUANTITY</label>
                                <input type="number" class="form-control" id="current_salesqty" readonly>
                            </div>
                            <br>
                            <div class="input-group">
                                <label class="form-control">NEW SALES QUANTITY</label>
                                <input type="text" class="form-control" name="modelID" id="model_id2" hidden>
                                <input type="text" class="form-control" name="new_sales_qty" required>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block btn-lg">UPDATE</button>
                    </form>
                </div>
            </div>
        </div><!-- /.box -->
    </div>
    <!-- UPDATE MODEL SALES QUANTITY MODAL*** -->

    <!-- ***UPDATE MODEL PRICE MODAL -->
    <div class="modal fade" id="update_price">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title"> <i class="ion-ios-pricetag"></i> UPDATE PRICE - <b id="modelname3"></b> - <b id="model_month_year3"></b> </h4>
                </div>
                </br>
                <div class="modal-body">
                    <div class="card shadow px-3 py-3">
                        <form action="<?php echo base_url(); ?>index.php/task/update_model_monthly_price" method="POST">
                            <div class="input-group">
                                <label for="" class="form-control">CURRENT PRICE</label>
                                <input type="number" class="form-control" id="active_price" readonly>
                            </div>
                            <br>
                            <div class="input-group">
                                <label class="form-control">NEW PRICE</label>
                                <input type="text" class="form-control" name="modelID" id="model_id3" hidden>
                                <input type="text" class="form-control" name="new_monthly_price" required>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block btn-lg">UPDATE</button>
                    </form>
                </div>
            </div>
        </div><!-- /.box -->
    </div>
    <!-- UPDATE MODEL PRICE MODAL*** -->

    <!-------------------------------------------------------------------------
                           *** CREATE NEW REVISION MODAL
    --------------------------------------------------------------------------->
    <div class="modal fade text-center mx-auto" id="create_new_revision_modal">
        <div class="modal-dialog modal-sm    modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h2 text-center"><b>CREATE NEW REVISION </b> </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="form-group text-center mx-auto">
                        <form action="<?= base_url() ?>index.php/task/create_new_revision" method="POST">
                            <input class="form-control" name="revision_date" id="revision_date" Placeholder="e.g.  REV-10-2-22" />
                            <hr>
                            <button type="submit" class="button-50 bt-1" role="button">Create</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------
                         CREATE NEW REVISION MODAL ***
    --------------------------------------------------------------------------->

    <!-------------------------------------------------------------------------
                           *** GENERATE UPLOAD FILE MODAL
    --------------------------------------------------------------------------->
    <div class="modal fade text-center mx-auto" id="generate_upload_file">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h3 text-center"><b>THIS CONTAINS THE PSI TEMPLATE TO UPLOAD </b> </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-body">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;" id="txt_selected_month">
                                            <option value="">Select Month</option>
                                            <?php foreach ($current_rev as $cur_rev) : ?>
                                                <option value="<?= $cur_rev->month_year ?>"><?= $cur_rev->month_year ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <hr>
                                        <button type="submit" class="btn btn-lg btn-block btn-success" role="button" id="btn_generatePSIupload_template">Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <select class="form-control " style="width: 100%;" readonly>
                                            <option value=""><?= $selectedDPRNO ?></option>
                                        </select>
                                        <hr>
                                        <button type="submit" class="btn btn-lg btn-block btn-info" role="button" id="btn_generatePSIupload_template_all">Download</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl_PSIupload_Template" hidden>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Model</td>
                                    <td>Month-Year</td>
                                    <td>Revision</td>
                                    <td>Monthly Price</td>
                                    <td>Production Quantity</td>
                                    <td>Sales Quantity</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $models = $this->db->query("SELECT  * FROM tbl_models ORDER BY sorting_code ASC ")->result();
                                foreach ($models as $m) :
                                ?>
                                    <tr>
                                        <td><?= $m->models_id ?></td>
                                        <td><?= $m->models_desc ?></td>
                                        <td class='lbl_selected_month'>'</td>
                                        <td><?= $selectedDPRNO ?></td>
                                        <td><?= $m->active_price ?></td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl_PSIupload_Template_all" hidden>
                            <thead>
                                <tr>
                                    <td>ID</td>
                                    <td>Model</td>
                                    <td>Month-Year</td>
                                    <td>Revision</td>
                                    <td>Monthly Price</td>
                                    <td>Production Quantity</td>
                                    <td>Sales Quantity</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $models = $this->db->query(
                                    "SELECT  * FROM tbl_new_plan 
                                    INNER JOIN tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$selectedDPRNO' AND month_year_numerical
                                    BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31", strtotime("+6 month")) . "' 
                                    ORDER BY tbl_customer.sorting_code,tbl_models.sorting_code,month_year_numerical ASC "
                                )->result();
                                foreach ($models as $m) :
                                ?>
                                    <tr>
                                        <td><?= $m->models_id ?></td>
                                        <td><?= $m->models_desc ?></td>
                                        <td>'<?= $m->month_year ?></td>
                                        <td><?= $m->revision ?></td>
                                        <td><?= $m->price ?></td>
                                        <td><?= $m->prod_qty ?></td>
                                        <td><?= $m->sales_qty ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------
                         GENERATE UPLOAD FILE MODAL ***
    --------------------------------------------------------------------------->

    <!-------------------------------------------------------------------------
                           *** UPLOAD PLAN MODAL
    --------------------------------------------------------------------------->
    <div class="modal fade text-center mx-auto" id="upload_plan_modal">
        <div class="modal-dialog modal-md    modal-dialog-centered">
            <div class="modal-content text-center mx-auto">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title fw-bold h2 text-center"><b>UPLOAD PLANS </b> </div>
                </div>
                <div class="modal-body text-center mx-auto">
                    <div class="form-group text-center mx-auto">
                        <div class="box box-info" style="padding:15px ;">
                            <div class="box-header">
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
            </div>
        </div>
    </div>
    <!-------------------------------------------------------------------------
                         UPLOAD PLAN MODAL ***
    --------------------------------------------------------------------------->
</div>
<!-- Content wrapper *** Dont Remove, magkakaroon ng whitespace sa baba ng sidebar -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>


<script>
    function get_models_by_id(model_id) {
        // console.log(model_id);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/task/get_models_by_id'); ?>",
            data: {
                modelID: model_id
            },
            success: (data) => {
                // console.log(data);
                d = JSON.parse(data);
                $('#modelname3').html(d[0].models_desc);
                $('#model_month_year3').html(d[0].month_year);
                $('#active_price').val(d[0].price);
                $('#model_id3').val(d[0].auto_id);
            }
        })
    }
</script>

<script>
    function update_prod_quantity(model_id, value) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/task/update_model_prod_qty",
            data: {
                new_prod_qty: value,
                modelID: model_id
            },
            success: (data) => {
                // console.log(data);
                setTimeout(() => {
                    window.location.reload();
                }, 5000 * 60)
            }
        })
    }

    function update_sales_quantity(model_id, value) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>index.php/task/update_model_sales_qty",
            data: {
                new_sales_qty: value,
                modelID: model_id
            },
            success: (data) => {
                // console.log(data);
                setTimeout(() => {
                    window.location.reload();
                }, 5000 * 60)
            }
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

        $("#btn_generatePSIupload_template").click(() => {
            var date = $("#txt_selected_month").val();
            exportTableToExcel("tbl_PSIupload_Template", "PSI-Upload-Template-Monthly-" + date);
        });

        $("#btn_generatePSIupload_template_all").click(() => {
            exportTableToExcel("tbl_PSIupload_Template_all", "PSI-Upload-Template-Six-Months-" + "<?= $selectedDPRNO ?>");
        });

        $("#txt_selected_month").change(() => {
            var date = $("#txt_selected_month").val();
            $(".lbl_selected_month").html("'" + date);
        })

    })
</script>
<script>
    function exportTableToExcel(tableID, filename = '') {
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        // Specify file name
        filename = filename ? filename + '.xls' : 'excel_data.xls';

        // Create download link element
        downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
        }
    }
</script>

<script>
    $(document).ready(() => {
        $("#psi_approvers_badge").hide();
        $("#btn_ViewPSIApprovers").on('click', () => {
            $("#psi_approvers_badge").toggle();
        });
        // $("#btn_ViewPSIApprovers").on('mouseleave', () => {
        //     $("#psi_approvers_badge").hide();
        // });

        // SUBMIT PSI
        $("#btn_submit_psi").on('click', () => {
            // alert("Sending Email.... Please Wait");
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>index.php/task/SubmitPSI",
                success: (data) => {
                    // console.log(data);
                    alert_success("PSI Submitted to Approvers via Email");
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000)
                }
            })

        });
    })

    function alert_success(message) {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 1000
        })
    }

    function alert_danger(message) {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 1000
        })
    }
</script>


<script>
    // RIGHT CLICK ON MODELS DESCRIPTION
    let selected_model = '';

    function rclick_model(model_id) {
        selected_model = model_id;
        $("td[name=" + model_id + "]").addClass('bg-olive');
        setTimeout(() => {
            $("td[name=" + model_id + "]").removeClass('bg-olive');
        }, 3000)
    }
    var menu = new BootstrapMenu('#lbl_models_desc', {
        actions: [
            // {
            //     name: "Duplicate",
            //     iconClass: 'fa-copy text-teal',
            //     onClick: function(row) {
            //         $.ajax({
            //             type: "POST",
            //             url: "<?= base_url() ?>index.php/task/duplicate_model",
            //             data: {
            //                 SelectedModel: selected_model
            //             },
            //             success: (data) => {
            //                 alert_success(data);
            //             }

            //         })
            //     },

            // },
            {
                name: "Hide",
                iconClass: 'fa-eye-slash text-black',
                onClick: function(row) {
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url() ?>index.php/task/hideModel",
                        data: {
                            SelectedModel: selected_model
                        },
                        success: (data) => {
                            alert_success(data);
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000)
                        }
                    })
                },

            },
        ]
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        gotocell();
        freezetable();
        var myRoww = 0;
        $(".testing").each(function() {
            myRoww = myRoww + 1;
            $(this).attr("id", "arrow" + myRoww);
        });
    });
</script>

<script type="text/javascript">
    let tracker = 1;

    function magbalik(ibalik) {
        if (tracker < 0) {
            tracker = 1;
            return tracker;
        }
    }

    $(document).on('keydown', function(e) {
        magbalik(tracker);

        var cell = window.localStorage.getItem("cell") || '';
        if (cell.length > 0) {
            tracker = parseInt(cell.substring(6));
            magbalik(tracker);
            // console.log(cell.substr(6));
        } else {
            tracker = 1;
            magbalik(tracker);
        }
        // console.log(tracker);
        var gap = <?= count($current_rev) ?> * 2; // Number of Months * Number of inputs per month
        if (e.keyCode == '38') {
            // up arrow
            tracker = tracker - gap;
            $("#arrow" + tracker).focus();
            window.localStorage.setItem("cell", "#arrow" + tracker)
        } else if (e.keyCode == '40') {
            // down arrow
            tracker = tracker + gap;
            $("#arrow" + tracker).focus();
            window.localStorage.setItem("cell", "#arrow" + tracker)
        } else if (e.keyCode == '37') {
            // left arrow
            tracker = tracker - 1;
            $("#arrow" + tracker).focus();
            window.localStorage.setItem("cell", "#arrow" + tracker)
        } else if (e.keyCode == '39') {
            // right arrow
            tracker = tracker + 1;
            $("#arrow" + tracker).focus();
            window.localStorage.setItem("cell", "#arrow" + tracker)
        }
    });
</script>

<script>
    // Go to the last selected cell based on the stored data on localstorage.
    function gotocell() {
        setTimeout(() => {
            var cell = window.localStorage.getItem("cell");
            $("" + cell + "").focus();
            console.log(cell);
        }, 100)
    }
    // Set cell 
    function set_cell(id) {
        window.localStorage.removeItem("cell");
        window.localStorage.setItem("cell", '#' + id);
    }
</script>

<?php if ($page == "ps_plan_form") { ?>
    <div class="card-footer"> </div>
<?php } ?>