<div class="content-wrapper" id="psiEdit" @click="PageLoad">
    <section class="content-header ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color:transparent ;">
                <li class="breadcrumb-item">
                    <h1 style="color: white;">
                        <i class="fa ion-ios-list" aria-hidden="true"></i> NCFL Plans
                    </h1>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/PSI/psi_edit" class="btn btn-lg btn-outline-success" style="color:white"><i class="ion-edit"></i> Edit Mode</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url() ?>index.php/task/add" class="btn btn-lg btn-outline-success" style="color:white"><i class="fa fa-folder-o"></i> Create Plan</a></li>
                <li v-if="CurrentRevisionData.status!=='Pending'??'Rejected'" class="breadcrumb-item"><a class="btn btn-lg btn-outline-warning" style="color:white; " data-toggle="modal" data-target="#create_new_revision_modal"> <i class="fa fa-paperclip"></i> New Revision</a></li>
                <li v-if="CurrentRevisionData.status=='Pending'??'Rejected'" class="breadcrumb-item"><a class="btn btn-lg btn-outline-warning disabled" style="color:white; "> <i class="fa fa-paperclip"></i> New Revision</a></li>
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
            <div class="col-xs-12">
                <div class="box box-success" style="padding:1rem;">
                    <div class="box-header" style="margin-bottom:5rem ;">
                        <div class="box-title text-black">
                            <h1> PSI ({{ current_revision }}) - {{ CurrentRevisionData.status }} </h1>
                        </div>
                        <div class="box-tools">

                            <!-- <a class="btn btn-success btn-lg" href="http://10000.216.2.202/Datawarehouse_approval/" target="_blank"> <i class="fa fa-envelope"></i> <small> SUBMIT PSI</small> </a> -->
                            <button class="btn btn-info btn-lg" id="btn_submit_psi"> <i class="fa fa-envelope"></i> <small> SUBMIT PSI</small> </button>
                            <a class="btn btn-success btn-lg " href="<?= base_url() ?>index.php/PSI_Viewing/PSI_View"> <i class="fa fa-eye"></i> <small>VIEW PSI </small></a>


                        </div>


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
                                foreach ($approvers as $approver):
                                    ?>
                                    <div class="col-md-4">
                                        <?php
                                        if ($approver->status == 1) {
                                            echo "<div class='info-box bg-green'> <span class='info-box-icon'><i class='fa fa-thumbs-up'></i></span>";
                                        } else if ($approver->status == "Rejected") {
                                            echo "<div class='info-box bg-red'> <span class='info-box-icon'><i class='fa fa-thumbs-down'></i></span>";
                                        } else {
                                            echo "<div class='info-box bg-yellow'> <span class='info-box-icon'><i class='fa fa-circle-o'></i></span>";
                                        }
                                        ?>

                                        <div class="info-box-content">
                                            <span class="info-box-text"><?= $approver->header; ?>
                                            <?php if (!empty($approver->remarks)) { ?>
                                                <a href="#message" data-toggle="tooltip" data-placement="right" title="<?= $approver->remarks; ?>"><i class='fa fa-envelope'></i></a></span>

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







                        <div class="box-body">
                            <div class="table-responsive freeze-table">
                                <table class="table table-bordered" id="tbl_models" style="color:#000">
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="3"># of Working Days</th>
                                            <!-- <th></th> -->
                                            <template v-for="month in Months">
                                                <template v-if="Actual !== month.isActual ">

                                                    <th class="text-center" colspan="4" > {{ month?.working_days }} Days </th>

                                                </template>

                                                <template v-else>
                                                    <th class="text-center" colspan="1" v-if="Hidden !== month.isHidden" hidden> {{ month?.isActual }}  </th>
                                                    <th class="text-center" colspan="1" v-else> {{ month?.isActual }}  </th>
                                                </template>
                                            </template>

                                        </tr>
                                        <tr style="background-color:rgba(204,255,204,0.9);">
                                            <th class="text-center" rowspan="2">Customer</th>
                                            <th class="text-center" rowspan="2">Item #</th>
                                            <th class="text-center" rowspan="2">Model</th>
                                            <!-- <th class="text-center"  style=" width:100px;"  v-for = "beginning in begin_invty">{{beginning?.month_year}}</th> -->
                                            <template v-for="month in Months">
                                                <template v-if="Actual !== month.isActual ">
                                                    <th class="text-center" colspan="4"> {{ month?.month_year }} </th>
                                                </template>
                                                <template v-else>
                                                    <th class="text-center" colspan="1" v-if="Hidden !== month.isHidden" hidden> {{ month?.month_year }} </th>
                                                    <th class="text-center" colspan="1" v-else> {{ month?.month_year }} </th>
                                                </template>
                                            </template>

                                        </tr>

                                        <tr style="background-color:rgba(204,255,204,0.9);">
                                            <!--  <th class="text-center" > <b> INVTY  </b> </th> -->


                                            <template v-for="month in Months">
                                                <template v-if="Actual !== month.isActual ">
                                                    <th class="text-center"> <b> PRICE</b> </th>
                                                    <th class="text-center"> <b> PRODN QTY</b> </th>
                                                    <th class="text-center"> <b> SALES QTY</b> </th>
                                                    <th class="text-center"> <b> INVTY</b> </th>
                                                </template>

                                                <template v-else>
                                                    <th class="text-center"  v-if="Hidden !== month.isHidden" hidden> <b> INVTY</b> </th>
                                                    <th class="text-center"  v-else> <b> INVTY</b> </th>
                                                </template>


                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(modelsID,customer) in psiData">
                                            <template v-if="(customer ==  row.name) || (row.name == 'System Administrator') || (row.name == 'NCFL PLAN (PSI)')">
                                                <tr>
                                                    <td :rowspan="ObjectLength(psiData[customer])+1"> <b> {{ customer.substring(customer.indexOf('/'),0) }} </b> </td>
                                                </tr>
                                                <template v-for="models in modelsID">
                                                    <template v-if="models.modelDT.cat_code =='MP'">
                                                        <tr>
                                                            <template v-if="models.modelDT.isHidden !== '1'">

                                                                <td > <b> {{ models.modelDT.item_code}} </b></td>
                                                                <td  id="lbl_models_desc" @contextmenu="rclick_model(models.modelDT.models_id)"> <b> {{ models.modelDT.models_desc }}</b>

                                                                    <p style="color: blue;"> {{ models.modelDT.model_remarks }} </p>
                                                                </td>
                                                                <template v-for="month in Months">
                                                                    <template v-if="Actual !== month.isActual  " >

                                                                        <template v-if="models[month?.month_year]?.category==1??'0'">

                                                                             <td style="background-color:white;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="number_format(models[month?.month_year]?.price??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'price', event, $event.target.id)"> </td>


                                                                            <!-- PROD -->
                                                                            <td  style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"> <input type="text" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)"  class="psiCell form-control" style="cursor:cell; border: 4px solid red;  text-align:center; font-size:1.2rem; width:90px; " :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>

                            


                            <td   style="background-color:white;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL'"> <input type="text" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> 
                            </td>
                            <!-- PROD -->

                            <!-- Sales -->
                            <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                            > <input type="text" class="psiCell form-control" style="cursor:cell;  text-decoration-line: underline;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                            @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>




                            <td style="background-color:white;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                            > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                            @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                            <!-- Sales -->



                            <td v-if="models[month?.month_year]?.isDuplicate=='0'??'0' "  style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="roundoff(models[month?.month_year]?.auto_inventory??'NULL')" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>

                                <td v-if="models[month?.month_year]?.isDuplicate=='1'??'0' "  style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="roundoff(models[month?.month_year]?.auto_inventory??'NULL')" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="0" disabled> </td>
                        </template>
                    </template>


                    <template v-else>
                        <template v-if="models[month?.month_year]?.category==1??'0'">

                            <td style="background-color:white;" v-if="Hidden !== month.isHidden" hidden  :title="roundoff(models[month?.month_year]?.auto_inventory??'NULL')"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>

                            <td style="background-color:white;" v-else  :title="roundoff(models[month?.month_year]?.auto_inventory??'NULL')"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>


                        </template>
                    </template>


                    <template v-if="Actual !== month.isActual ">

                        <template v-if="models[month?.month_year]?.category==2??'0'">



                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedProdQty/1000??'NULL')" disabled> </td>


                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedSalesQty/1000??'NULL')" disabled> </td>
                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                        </template>
                    </template>


                    <template v-else>
                        <template v-if="models[month?.month_year]?.category==2??'0'">
                            <td style="background-color:GoldenRod;" v-if="Hidden !== month.isHidden" hidden data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>


                            <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                        </template>


                    </template>




                    <template v-if="Actual !== month.isActual ">
                        <template v-if="models[month?.month_year]?.category==3??'0'">



                            <!-- PROD -->
                            <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"   v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>


                            <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                            <!-- PROD -->



                            <!-- Sales -->
                            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                            > <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                            @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>


                            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' " data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                            > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                            @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                            <!-- Sales -->
                            <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>
                        </template>
                    </template>

                    <template v-else>
                        <template v-if="models[month?.month_year]?.category==3??'0'">

                            <td style="background-color:LightGreen;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>

                            <td style="background-color:LightGreen;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>
                        </template>
                    </template>

                </template>

            </template>
        </tr>
    </template>
    <template v-if="models.modelDT.cat_code =='SMPL'">
        <tr>
            <template v-if="models.modelDT.isHidden !== '1'">
                <td> <b> {{ models.modelDT.item_code}} </b></td>
                <td> <b  id="lbl_models_desc" @contextmenu="rclick_model(models.modelDT.models_id)"> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                <template v-for="month in Months">
                    <template v-if="Actual !== month.isActual ">
                        <template v-if="models[month?.month_year]?.category==1??'0'">

                            <!-- PROD -->
                            <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>

                            <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                            <!-- PROD -->



                            <!-- Sales -->
                            <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                            v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                            @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>


                            <td style="background-color:white;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                            v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                            @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                            <!-- Sales -->


                            <td style="background-color:white;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>
                        </template>
                    </template>


                    <template v-if="Actual !== month.isActual ">
                        <template v-if="models[month?.month_year]?.category==2??'0'">
                            <td style="background-color:GoldenRod;" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.price??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'price', event, $event.target.id)" disabled> </td>
                            <td style="background-color:GoldenRod;"  data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedProdQty/1000??'NULL')" disabled> </td>
                            <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedSalesQty/1000??'NULL')" disabled> </td>
                            <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                        </template>
                    </template>

                    <template v-else>
                        <template v-if="models[month?.month_year]?.category==2??'0'">
                            <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"  v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>

                            <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"  v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                        </template>
                    </template>


                    <template v-if="Actual !== month.isActual ">
                        <template v-if="models[month?.month_year]?.category==3??'0'">

                            <!-- PROD -->
                            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>

                            
                            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                            <!-- PROD -->



                            <!-- Sales -->
                            <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                              :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                              @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                              <td style="background-color:LightGreen;" v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                                  :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                                  @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                  <!-- Sales -->


                                  <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>
                              </template>
                          </template>



                          <template v-else>
                            <template v-if="models[month?.month_year]?.category==3??'0'">
                                <td style="background-color:LightGreen;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>

                                <td style="background-color:LightGreen;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>


                            </template>
                        </template>
                    </template>
                </template>


            </tr>
        </template>


        <template v-if="models.modelDT.cat_code =='EOL'">
            <tr>
                <template v-if="models.modelDT.isHidden !== '1'">
                    <td style="background-color:GoldenRod;"> <b> {{ models.modelDT.item_code}} </b></td>
                    <td style="background-color:GoldenRod;"  id="lbl_models_desc" @contextmenu="rclick_model(models.modelDT.models_id)"> <b> {{ models.modelDT.models_desc }} </b> {{ models.modelDT.model_remarks }} </td>
                    <template v-for="month in Months">
                        <template v-if="Actual !== month.isActual ">
                            <template v-if="models[month?.month_year]?.category==1??'0'">


                                <!-- PROD -->
                                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                                <!-- PROD -->


                                <!-- Sales -->
                                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                                v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                                @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                <td style="background-color:white;"  data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                                v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                                @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                <!-- Sales -->



                                <td style="background-color:white;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                            </template>
                        </template>


                        <template v-else>
                            <template v-if="models[month?.month_year]?.category==1??'0'">
                                <td style="background-color:white;" v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>

                                <td style="background-color:white;" v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                            </template>
                        </template>


                        <template v-if="Actual !== month.isActual ">
                            <template v-if="models[month?.month_year]?.category==2??'0'">

                                <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'" > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedProdQty/1000??'NULL')" disabled> </td>


                                <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                                > <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedSalesQty/1000??'NULL')" disabled> </td>
                                <td style="background-color:GoldenRod;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>
                            </template>
                        </template>

                        <template v-else>
                            <template v-if="models[month?.month_year]?.category==2??'0'">
                                <td style="background-color:GoldenRod;"  data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"  v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>



                                <td style="background-color:GoldenRod;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.inventory/1000??'NULL'"   v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.inventory/1000??'NULL')" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'inventory', event, $event.target.id)"> </td>


                            </template>
                        </template>

                        <template v-if="Actual !== month.isActual ">
                            <template v-if="models[month?.month_year]?.category==3??'0'">

                                <!-- PROD -->
                                <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                                <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="models[month?.month_year]?.mergedProdQty??'NULL'"  v-if="models[month?.month_year]?.isProdEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedProdQty/1000)??'NULL' , $event.target.id)" @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'prod_qty', event, $event.target.id)"> </td>
                                <!-- PROD -->


                                <!-- Sales -->
                                <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                                v-if="models[month?.month_year]?.isSalesEdit=='1'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell;  text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                                @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                <td style="background-color:LightGreen;" data-toggle="tooltip" data-placement="right" :title="two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'"
                                v-if="models[month?.month_year]?.isSalesEdit=='0'??'0' "> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :placeholder="roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL'" @focus="change_value(two_decimal(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" @blur="one_decimal(roundoff(models[month?.month_year]?.mergedSalesQty/1000)??'NULL' , $event.target.id)" 
                                @keyup="update_new_plan(models[month?.month_year]?.auto_id, month?.month_year , models[month?.month_year]?.model, models[month?.month_year]?.models_id,  $event.target.value, 'sales_qty', event, $event.target.id)"> </td>
                                <!-- Sales -->



                                <td style="background-color:LightGreen;"> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>
                            </template>
                        </template>



                        <template v-else>
                            <template v-if="models[month?.month_year]?.category==3??'0'">
                                <td style="background-color:LightGreen;"  v-if="Hidden !== month.isHidden" hidden> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.auto_inventory/1000??'NULL')" disabled> </td>
                                <td style="background-color:LightGreen;"  v-else> <input type="text" class="psiCell form-control" style="cursor:cell; text-align:center; font-size:1.2rem; width:90px;" :value="roundoff(models[month?.month_year]?.mergedInventory/1000??'NULL')" disabled> </td>


                            </template>
                        </template>
                    </template>
                </template>

            </tr>
        </template>
        <template v-if="(models.modelDT.cat_code =='TOTAL')">
            <tr>
                <td colspan='2' style="background-color:rgba(102,255,255,0.2);"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
                <template v-for="month in Months">
                    <template v-if="Actual !== month.isActual ">
                          <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> --- </td>
                        <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedProdQty/1000) }} </td>
                        <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedSalesQty/1000) }} </td>
                        <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ roundoff(models[month?.month_year]?.auto_inventory/1000) }}</td>
                    </template>


                    <template v-else >

                        <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-if="Hidden !== month.isHidden" hidden> {{ roundoff(models[month?.month_year]?.auto_inventory/1000) }}</td>



                        <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-else> {{ roundoff(models[month?.month_year]?.auto_inventory/1000) }}</td>
                    </template>


                </template>
            </tr>
        </template>



        <template v-if="models.modelDT.cat_code =='EOLTOTAL'">
            <tr>
                <td colspan='2' style="background-color:rgba(255,230,153,0.5);"> {{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }} </td>
                <template v-for="month in Months">
                    <template v-if="Actual !== month.isActual ">
                          <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> --- </td>
                        <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedProdQty/1000) }}</td>
                        <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> {{ roundoff(models[month?.month_year]?.mergedSalesQty/1000) }}</td>
                        <td style="background-color:rgba(255,230,153,0.5);" class="text-right"> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>
                    </template>


                    <template v-else>

                        <td style="background-color:rgba(255,230,153,0.5);" class="text-right" v-if="Hidden !== month.isHidden" hidden> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>

                        <td style="background-color:rgba(255,230,153,0.5);" class="text-right" v-else> {{ roundoff(models[month?.month_year]?.inventory/1000) }}</td>
                    </template>


                </template>
            </tr>
        </template>



        <template v-if="models.modelDT.cat_code =='GRANDTOTAL'">
            <tr>
                <td colspan='2' style="background-color:rgba(102,255,255,0.2);"> <b>{{ models.modelDT.models_desc }} {{ models.modelDT.model_remarks }}<b> </td>
                    <template v-for="month in Months">
                        <template v-if="Actual !== month.isActual ">

                            <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> --- </td>
                            <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.mergedProdQty/1000)) }} </td>
                            <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.mergedSalesQty/1000)) }} </td>
                            <td style="background-color:rgba(102,255,255,0.2);" class="text-right"> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>
                        </template>


                        <template v-else>

                            <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-if="Hidden !== month.isHidden" hidden> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>


                            <td style="background-color:rgba(102,255,255,0.2);" class="text-right" v-else> {{ number_format(roundoff(models[month?.month_year]?.auto_inventory/1000)) }}</td>


                        </template>


                    </template>

                </tr>
            </template>
        </template>
    </template>
</template>
</tbody>
</table>
</div>
</div>
</div>
</div>
</section>

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
                                            <form action="<?= base_url() ?>index.php/PSI/create_new_revision" method="POST">
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
                        *** GENERATE DOWNLOAD FILE MODAL
                        --------------------------------------------------------------------------->


                        <div class="modal fade  mx-auto" id="generate_upload_file">


                            <div class="modal-dialog modal-md modal-dialog-centered">       

                                <div class="callout callout-warning">
                                    <h4>Alert!</h4>

                                    <p>Do not Edit (id , model , revision , month year) , Insert New Models and Delete Value based on Template downloads . 
                                        Here's link to add New Model. <a  style="color: skyblue; text-align: center;" href="http://10000.216.128.10/Datawarehouse/index.php/User_Maintenance/add_model">PSI MAINTENANCE!</a></p>
                                    </div>
                                    <div class="modal-content text-center mx-auto">
                                        <div class="modal-header">

                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <div class="modal-title fw-bold h3 text-center"><b>THIS CONTAINS THE PSI TEMPLATE TO UPLOAD </b> </div>
                                            <hr>


                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="box box-success">
                                                        <div class="box-body">
                                                            <div class="form-group">

                                                                <select class="form-control" style="width: 100%;" id="txt_selected_month">
                                                                    <option value="">Select Month</option>
                                                                    <template v-for="month in Months">
                                                                        <template v-if="Actual !== month.isActual  " >
                                                                            <option :value="month?.month_year"> {{ month?.month_year }} </option>
                                                                        </template>
                                                                    </template>
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
                                                                    <option value=""><?= $selectedDPRNO; ?></option>
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
                                                        $models = $this->db->query(
                                                            "SELECT  * FROM tbl_new_plan 
                                                            INNER JOIN tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                                            INNER JOIN tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                                            WHERE isActual= 'PLAN' AND revision='$selectedDPRNO'
                                                            BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31", strtotime("+6 month")) . "' 
                                                            ORDER BY tbl_customer.sorting_code,tbl_models.sorting_code,month_year_numerical ASC "
                                                        )->result();
                                                        foreach ($models as $m):
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
                                                            WHERE isActual= 'PLAN' AND revision='$selectedDPRNO'
                                                            BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31", strtotime("+6 month")) . "' 
                                                            ORDER BY tbl_customer.sorting_code,tbl_models.sorting_code,month_year_numerical ASC "
                                                        )->result(); foreach ($models as $m):
                                                        ?>
                                                        <tr>
                                                            <td><?= $m->models_id ?></td>
                                                            <td><?= $m->models_desc ?></td>
                                                            <td>'<?= $m->month_year ?></td>
                                                            <td><?= $m->revision ?></td>
                                                            <td><?= $m->price ?></td>
                                                            <td><?= $m->mergedProdQty ?></td>
                                                            <td><?= $m->mergedSalesQty ?></td>
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
                        GENERATE DOWNLOAD FILE MODAL ***
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
                                                        <form class="px-3 py-3" action="<?php echo base_url(); ?>index.php/PSI/importFile" method="post" enctype="multipart/form-data">
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




                    

                    <script>

                       function change_value(data , cellID){
                        document.getElementById(cellID).value=data;

                    }

                    function one_decimal(data , cellID) {
                      document.getElementById(cellID).value=data;

                  }


                  function two_decimal(number) {
                    let num = number;
                    var data = num.toFixed(3);
                    return data;

                }



            </script>


            <script>
             $(document).ready(() => {

                $("#btn_submit_psi").on('click', () => {
                        // alert("Sending Email.... Please Wait");
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url() ?>index.php/PSI/SubmitPSI",
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

             function alert_success() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "PSI REPORT SENT",
                    text: "PSI Submitted to Approvers via Email",
                    showConfirmButton: false,
                })

            }


        </script>



        <!-- rclick function -->




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
        {
            name: "Insert",
            iconClass: 'fa-copy text-black',
            onClick: function(row) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>index.php/Prod_Sales_Amount/duplicate_model",
                    data: {
                        SelectedModel: selected_model
                    },
                    success: (data) => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1)
                    }

                })
            },

        },
        {
            name: "Hide",
            iconClass: 'fa-eye-slash text-black',
            onClick: function(row) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>index.php/Prod_Sales_Amount/hideModel",
                    data: {
                        SelectedModel: selected_model
                    },
                    success: (data) => {
                        alert_hide();
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000)
                    }
                })
            },

        },

           {
            name: "Remove",
            iconClass: 'fa-remove text-black',
            onClick: function(row) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>index.php/Prod_Sales_Amount/remove",
                    data: {
                        SelectedModel: selected_model
                    },
                    success: (data) => {
                        alert_remove();
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000)
                    }
                })
            },

        },



        ]
    });


     function alert_hide() {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: "Your PSI Model is hidden ",
            text: "based on your chosen model",
            showConfirmButton: false,
        })

    }

    // function alert_duplicate() {
    //     Swal.fire({
    //         position: 'top-end',
    //         icon: 'success',
    //         title: "Breakdown successfully",
    //         text: "Check your PSI SALES AMOUNT",
    //         showConfirmButton: false,
    //     })

    // }

    function alert_remove() {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: "Remove successfully",
            text: "Check your PSI SALES AMOUNT",
            showConfirmButton: false,
        })

    }



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
                    // Create Automatic Cell
            function createCellID() {
                var counter = 0;
                $(".psiCell").each(function() {
                    counter = counter + 1;
                    $(this).attr("id", "cell_" + counter);
                });
            }
                    // Set Cell in Local Storage
            function set_cell(id) {
                var cell = window.localStorage.getItem("cell");
                if (cell) {
                    window.localStorage.removeItem("cell");
                    window.localStorage.setItem("cell", id);
                }
            }
            function roundoff(number) {
                let num = number;
                var data = num.toFixed(1);
                return data;
                
            }

            function number_format(number) {
                let formatCurrency = new Intl.NumberFormat('en-US');
                var data = formatCurrency.format(number)
                return data;
            }

            

        </script>


        <script>
            $(document).ready(() => {
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
            var app = new Vue({
                el: '#psiEdit',
                data: {
                    Months: [],
                    begin_invty: [],
                    AllModels: [],
                    customers: [],
                    psiData: [],
                    row: {name: '<?= $role_text; ?>'},
                    row_admin : {role: 'System Administrator'},
                    previousmonth:'<?= date('Y-m-01', strtotime('-1 month')) ?>',
                    Actual:'ACTUAL',
                    Hidden:'Display',


                    CurrentRevisionData: [],
                    current_revision: '<?= $selectedDPRNO; ?>',
                },
                methods: {
                    GeneratePSIedit: function() {
                        axios.post("<?= base_url() ?>index.php/PSI/GeneratePSIedit", {
                            SelectedRevision: this.current_revision,
                        })
                        .then((response) => {
                            console.log(response.data);
                            app.customers = response.data.customers;
                            app.Months = response.data.months;
                            app.begin_invty = response.data.begin_invty;
                            app.psiData = response.data.psiData;
                                        // OUTSIDE JS FUNCTIONS
                            freezetable(1);
                            setTimeout(() => {
                                createCellID();
                            }, 500);
                        })

                    },
                    update_new_plan: function(id, month, model, models_id, value, psi, e, cellID) {
                        const updateTriggerKeys = [13];
                                        // Update only when these keys are used | Reduce Server Usage
                        if (updateTriggerKeys.includes(e.keyCode) || !isNaN(e.key)) {
                            axios.post("<?= base_url() ?>index.php/PSI/update_new_plan", {
                                SelectedRevision: this.current_revision,
                                id: id,
                                month: month,
                                model: model,
                                models_id: models_id,
                                value: value,
                                psi: psi,
                                cellID: cellID
                            })
                            .then((response) => {
                                console.log(response.data);
                                app.month = response.data.month;
                                
                            })
                        }
                                        // Generate the PSI | Event: Press Enter
                        if (e.keyCode === 13) {
                            this.GeneratePSIedit();
                        }
                        const gap = (app.Months.length * 4) -3;
                        if (e.key == "ArrowLeft") {
                            cellIDNum = parseInt(cellID.substring(5));
                            cellIDNum--;
                            $("#cell_" + cellIDNum).focus();
                            set_cell('cell_' + cellIDNum);
                        } else if (e.key == "ArrowRight") {
                            cellIDNum = parseInt(cellID.substring(5));
                            cellIDNum++;
                            $("#cell_" + cellIDNum).focus();
                            set_cell('cell_' + cellIDNum);
                        } else if (e.key == "ArrowUp") {
                            cellIDNum = parseInt(cellID.substring(5));
                            cellIDNum = cellIDNum - gap;
                            $("#cell_" + cellIDNum).focus();
                            set_cell('cell_' + cellIDNum);
                        } else if (e.key == "ArrowDown") {
                            cellIDNum = parseInt(cellID.substring(5));
                            cellIDNum = cellIDNum + gap;
                            $("#cell_" + cellIDNum).focus();
                            set_cell('cell_' + cellIDNum);
                        }
                    },
                    GetRevisionData: function() {
                        axios.post("<?= base_url() ?>index.php/PSI/GetCurrentRevisionData", {
                            SelectedRevision: this.current_revision,
                        }).then((response) => {
                                                                // console.log(response.data);
                            app.CurrentRevisionData = response.data.CurrentRevisionData;
                        })
                    },
                    PageLoad: function() {
                                                                // this.GeneratePSIedit();
                        this.GetRevisionData();
                    }
                },
                created: function() {
                    this.GeneratePSIedit();
                    this.GetRevisionData();
                }
            });
        </script>
