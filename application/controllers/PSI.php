<?php if (!defined('BASEPATH'))
exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class PSI extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('PSI_MODEL', 'psim');
        $this->load->model('Task_model', 'tm');
        $this->load->library('functions', 'ci');
        $this->isLoggedIn();
        $this->module = 'PSI';
    }

    function index()
    {
        echo "<code>DIRECTORY ACCESS FORBIDDEN</code>";
    }

    function psi_edit()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $dateFrom = $dateTo = "";

            $dashngayon = substr($selectedDPRNO, 7, 1);
            if ($dashngayon == 1 || $dashngayon == 4) {
                $dateFrom = date("Y-m-01", strtotime("-1 month"));
            } else {
                $dateFrom = date("Y-m-01");
            }

            /*
            UPDATE CURRENT PSI TO "APPROVED" IF APPROVERS COUNT IS >= 3 ON LOAD
            */
            $approve_counts = $this->db->query(" SELECT COUNT(tbl_approved_psi.status) AS approved_count FROM tbl_approved_psi
                INNER JOIN tbl_revision ON tbl_approved_psi.rev_id=tbl_revision.ID
                WHERE tbl_revision.revision_date='$selectedDPRNO'
                AND tbl_approved_psi.status ='1'
                ")->row()->approved_count;

            if ($approve_counts >= 3) {
                $query = $this->db->query(
                    "UPDATE tbl_revision
                    SET STATUS = 'Approved'
                    WHERE revision_date = '$selectedDPRNO' "
                );
            }

            $reject_counts = $this->db->query(" SELECT COUNT(tbl_approved_psi.status) AS reject_count FROM tbl_approved_psi
                INNER JOIN tbl_revision ON tbl_approved_psi.rev_id=tbl_revision.ID
                WHERE tbl_revision.revision_date='$selectedDPRNO'
                AND tbl_approved_psi.status ='Rejected'
                ")->row()->reject_count;

            if ($reject_counts >= 3) {
                $query = $this->db->query(
                    "UPDATE tbl_revision
                    SET STATUS = 'Rejected'
                    WHERE revision_date = '$selectedDPRNO' "
                );
            }

            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selectedDPRNO);
            if (isset($LastMonthOfCurrentRevision)) {
                $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;
            }
            $this->global['pageTitle'] = 'DWH : PSI EDIT MODE';
            $data['psiEdit_dateFrom'] = $dateFrom;
            $data['psiEdit_dateTo'] = $dateTo;
            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $this->loadViews("psi/psi_edit", $this->global, $data, NULL);

            // $this->loadViews("psi/psi_break_down", $this->global, $data, NULL);
        }
    }

    function psi()
    {

        $this->load->view('psi/psi');
    }


    public function create_new_revision()
    {
        $revision_date = $_POST['revision_date'];
        $user_id = $this->session->userdata('userId');
        $name = $this->session->userdata('name');
        $role_text = $this->session->userdata('roleText');
        if (isset($revision_date) && !empty($revision_date)) {

            // *** 1. Get the current revision ***
            $query = $this->db->query('SELECT * FROM tbl_revision WHERE isActive="1" ');
            $current_revision = $query->row()->revision_date;

            // *** 2. Get all data from current revision ***

            $data_from_current_rev = array();
            if (isset($current_revision)) {
                $q = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$current_revision' ");
                $data_from_current_rev = $q->result();
            }

            // Check tbl_new_plan if current revision already exist.
            $rev_exist = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$revision_date' LIMIT 1 ")->result();
            if (count($rev_exist) >= 1) {
                // print_r($rev_exist);
                // echo "Revision already made! ";
                $this->session->set_flashdata('error', 'REVISION ALREADY MADE !');
            } else {
                // *** 3. Insert all the data from current revision and change revision to new revision ***
                if (count($data_from_current_rev) > 0) {
                    foreach ($data_from_current_rev as $row) {
                        $data = array(
                            "customer" => $row->customer,
                            "model" => $row->model,
                            "price" => $row->price,
                            "month_year" => $row->month_year,
                            "revision" => $revision_date,
                            "sales_qty" => $row->sales_qty,
                            "prod_qty" => $row->prod_qty,
                            "month_year_numerical" => $row->month_year_numerical,
                            "working_days" => $row->working_days,
                            "isActual" => $row->isActual,
                            "isHidden" => $row->isHidden
                        );
                        $this->db->insert('tbl_new_plan', $data);

                    }
                }

                // 4. SET ALL EXISTING REVISION TO INACTIVE
                $this->db->query("UPDATE tbl_revision SET isActive='0' WHERE 1");

                // 5. CREATE NEW REVISION IN tbl_revision and set it as current revision
                $date_created = date("Y-m-j");
                $data = array(
                    "ID" => NULL,
                    "revision_date" => "$revision_date",
                    "isActive" => 1,
                    "date_created" => $date_created,
                    "status" => "Pending"
                );
                $this->db->insert('tbl_revision', $data);
                $this->session->set_flashdata('success', 'SUCCESSFULLY CREATED A NEW REVISION');

                // 6. GET ALL ACTIVE APPROVERS OF THE PSI
                $approvers = $this->db->query("SELECT * FROM tbl_approver WHERE documentType='PSI' AND isActive='1' ")->result();
                // 7. CREATE APPROVERS ON THE NEWLY CREATED PSI
                $newlyCreatedPSI = $this->db->query("SELECT * FROM tbl_revision WHERE isActive='1' ")->row();
                foreach ($approvers as $approver) {
                    $data = array(
                        "approver_id" => $approver->approver_id,
                        "rev_id" => $newlyCreatedPSI->ID,
                        "status" => 0
                    );
                    $this->db->insert('tbl_approved_psi', $data);
                }
                // 8. EMAIL ALERT THE APPROVERS
                // file_get_contents('http://10.216.2.202/Datawarehouse_approval/prepared_by.php');
                $action_made = "Create New Revision : {$revision_date}";
            }

            $this->psim->UserLog($user_id, $role_text, $name, $action_made);
        } else {

            $this->session->set_flashdata('error', 'REVISION IS NOT SET !');
        }
        redirect('index.php/PSI/psi_edit');


    }

    // GENERATE PSI EDIT
    function GeneratePSIedit()
    {
        $current_revision = $this->tm->get_dpr_no();
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $customers = $this->psim->GetCustomers();
            $months = $this->psim->MONTHS($current_revision);
            /*  $begin_invty = $this->psim->GET_BEGIN();*/
            $allModels = [];
            $psi = [];
            $break = [];
            foreach ($customers as $customer):
                $models = $this->psim->MODEL($customer->customers_id);
                $allModels[$customer->customers_desc] = $models; foreach ($models as $modelKey => $model):
                $psi[$customer->customers_desc][$modelKey]['modelDT'] = $model;

                $inventory = 0; foreach ($months as $month):
                $psiDT = $this->psim->PSIDATA($model->models_id, $current_revision, $month->month_year);
                $salesbreak = $this->psim->SALESBREAKDOWN($model->models_code, $current_revision, $month->month_year);
                if ($psiDT !== NULL || $salesbreak !== NULL) {


                    if ($model->cat_code == "EOL") {
                        $inventory = $psiDT->inventory;

                    }
                    $inventory += $salesbreak->auto_inventory;
                    $salesbreak->auto_inventory = $inventory;
                    $psiDT->auto_inventory = $inventory;
                    $psi[$customer->customers_desc][$modelKey][$month->month_year] = $psiDT;
                    $break[$customer->customers_desc][$modelKey][$month->month_year] = $salesbreak;



                    if ($model->cat_code == "MP" || $model->cat_code == "SMPL" || $model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                        if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] += $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] += $salesbreak->auto_inventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        } else {
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] = $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] = $salesbreak->auto_inventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        }
                    }

                    if ($model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                        if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {

                            $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        } else {

                            $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        }
                    }




                    if ($model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                        if (isset($psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'])) {
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedSalesQty'] += $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] += $psiDT->inventory;
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                        } else {
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedSalesQty'] = $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] = $psiDT->inventory;
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                        }
                    }


                            // if ($model->cat_code == "EOL") {

                            //     // EOL
                            //     if (isset($psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'])) {
                            //         $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] += $psiDT->inventory;
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                            //     } else {
                            //         $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] = $psiDT->inventory;
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                            //     }
                            // }

                    if ($model->cat_code == "MP" || $model->cat_code == "SMPL") {
                        if (isset($psi['GrandTotal']['cat_code'][$month->month_year]['mergedProdQty'])) {
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedSalesQty'] += $psiDT->mergedSalesQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                            $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                            $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] = " GRAND TOTAL";

                        } else {
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedSalesQty'] = $psiDT->mergedSalesQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                            $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                            $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] = " GRAND TOTAL";
                        }
                        $this->movetobottom($psi, "GrandTotal");
                    }


                            // if ($model->cat_code == "EOL") {
                            //      if (isset($psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedProdQty'])) {
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['cat_code'] = "GRANDTOTAL";
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['models_desc'] = " GRAND TOTAL";

                            //     } else {
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['cat_code'] = "GRANDTOTAL";
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['models_desc'] =  " GRAND TOTAL";
                            //     }
                            //     $this->movetobottom($psi, "GrandTotal");
                            // }

                }
            endforeach;
        endforeach;
    endforeach;

    $data = array(
        "customers" => $customers,
        "months" => $months,
        /*"begin_invty" => $begin_invty,*/
        "AllModels" => $allModels,
        "Breakdown" => $break,
        "psiData" => $psi

    );
    echo json_encode($data);
}
}





function update_new_plan()
{
    $dt = json_decode(file_get_contents("php://input"));
    if (isset($dt)) {
            // print_r($dt);
        $revision = $dt->SelectedRevision;
        $id = $dt->id;
        $value = $dt->value;
        $psi = $dt->psi;
        $month = $dt->month;
        $model = $dt->model;
        // $models_code = $dt->models_code;
        $cellnum = $dt->cellID;
        $user_id = $this->session->userdata('userId');
        $name = $this->session->userdata('name');
        $role_text = $this->session->userdata('roleText');



        if ($psi == 'price') {
            echo "Price Updated";
            $this->db->query("UPDATE tbl_new_plan SET price='$value' WHERE auto_id='$id' AND revision='$revision' ");
            $action_made = "updated the Price of {$cellnum} in PSI EDIT MODE";
        }


        if ($psi == 'prod_qty') {
  // $this->db->query(" UPDATE cias.tbl_new_plan, cias.tbl_models SET cias.tbl_new_plan.prod_qty=0 WHERE tbl_models.models_code='$models_code' AND tbl_new_plan.revision='$revision' AND tbl_new_plan.month_year='$month' AND tbl_new_plan.model=tbl_models.models_id");

            $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 1  WHERE auto_id='$id' AND revision='$revision' ");

            $action_made = "updated the Prod Qty of {$cellnum} in PSI EDIT MODE";

            $previous_revision = $this->db->query("SELECT revision_date AS prevrev FROM cias.tbl_revision LIMIT 2")->row()->prevrev;
            $previous_rev = $this->db->query("SELECT prod_qty FROM tbl_new_plan WHERE revision='$previous_revision' AND month_year='$month'  AND model='$model'")->row();
            $current_rev = $this->db->query("SELECT prod_qty FROM tbl_new_plan WHERE revision='$revision' AND month_year='$month'  AND model='$model'")->row();

            if ($previous_rev->prod_qty == $current_rev->prod_qty) {

                $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 0  WHERE auto_id='$id' AND revision='$revision' ");

                $action_made = "Same data in previous Prod Qty of {$cellnum} in PSI EDIT MODE";
            }

        }




        if ($psi == 'sales_qty') {
            echo "Sales Updated";

  // $this->db->query(" UPDATE cias.tbl_new_plan, cias.tbl_models SET cias.tbl_new_plan.sales_qty=0 WHERE tbl_models.models_code='$models_code' AND tbl_new_plan.revision='$revision' AND tbl_new_plan.month_year='$month' AND tbl_new_plan.model=tbl_models.models_id");

            $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");

            $action_made = "updated the Sales Qty of {$cellnum} in PSI EDIT MODE";

            $previous_revision = $this->db->query("SELECT revision_date AS prevrev FROM cias.tbl_revision LIMIT 2")->row()->prevrev;
            $previous_rev = $this->db->query("SELECT sales_qty FROM tbl_new_plan WHERE revision='$previous_revision' AND month_year='$month'  AND model='$model'")->row();
            $current_rev = $this->db->query("SELECT sales_qty FROM tbl_new_plan WHERE revision='$revision' AND month_year='$month'  AND model='$model'")->row();

            if ($previous_rev->sales_qty == $current_rev->sales_qty) {

              

                $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 0 WHERE auto_id='$id' AND revision='$revision' ");

                $action_made = "Same data in previous Sales Qty of {$cellnum} in PSI EDIT MODE";
            }
        }

        if ($psi == 'inventory') {
            echo "Inventory Updated";
            $this->db->query("UPDATE tbl_new_plan SET inventory='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
            $action_made = "updated the Inventory of {$cellnum} in PSI EDIT MODE";
        }

        if ($psi == 'inventory') {
            echo "Inventory Updated";
            $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
            $action_made = "updated the Inventory of {$cellnum} in PSI EDIT MODE";
        }

        $this->psim->UserLog($user_id, $role_text, $name, $action_made);
    }

}



function GetCurrentRevisionData()
{
    $selectedDPRNO = $this->tm->get_dpr_no();
    $data = array();
    $CurrentRevisionData = $this->psim->GetCurrentRevisionData($selectedDPRNO);
    $data = array(
        "CurrentRevisionData" => $CurrentRevisionData
    );
    echo json_encode($data);
}

function importFile()
{
    $selectedDPRNO = $this->tm->get_dpr_no();
    if ($this->input->post('submit')) {
        $path = 'assets/uploads/';
        require_once APPPATH . "/third_party/PHPExcel.php";
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('uploadFile')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
        }

        if (empty($error)) {
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $path . $import_xls_file;

            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                $countData = count($allDataInSheet);
                $arr = array();
                $hasErrors = $this->session->flashdata('error');

                    // Determine what template user uploaded by counting the rows;
                $uploadCount = abs(count($allDataInSheet) - 1);
                $modelsCount6Months = $this->db->query("SELECT COUNT(*) AS modelCount FROM tbl_new_plan WHERE revision='$selectedDPRNO' AND month_year_numerical BETWEEN '" . date("Y-m-01") . "' AND '" . date("Y-m-31", strtotime("+6 month")) . "' ")->row();

                for ($x = 2; $x <= $countData; $x++) {
                    $data['model_id'] = $allDataInSheet[$x]["A"];
                    $data['model'] = $allDataInSheet[$x]["B"];
                    $data['month_year'] = $allDataInSheet[$x]["C"];
                    $data['revision'] = $allDataInSheet[$x]["D"];
                    $data['new_price'] = $allDataInSheet[$x]["E"];
                    $data['new_prod_qty'] = $allDataInSheet[$x]["F"];
                    $data['new_sales_qty'] = $allDataInSheet[$x]["G"];
                    $query = "UPDATE tbl_new_plan SET
                    prod_qty ='" . $data['new_prod_qty'] . "',
                    sales_qty ='" . $data['new_sales_qty'] . "',
                    price ='" . $data['new_price'] . "'
                    WHERE month_year='" . substr($data['month_year'], 1) . "'
                    AND revision= '" . $data['revision'] . "'
                    AND model= '" . $data['model_id'] . "'
                    ";

                    if (!is_numeric($data['new_price']) || !is_numeric($data['new_prod_qty']) || !is_numeric($data['new_sales_qty'])) {
                        $this->session->set_flashdata('error', 'Non numeric number on row ' . $x . ', please check your excel file!');
                    } else if ($data['revision'] !== $selectedDPRNO) {
                        $this->session->set_flashdata('error', 'You can update current revision only! Please check your excel file on row ' . $x . ' ');
                    } elseif ($data['model_id'] == "" || empty($data['model_id'])) {
                        $this->session->set_flashdata('error', 'Missing Model ID on row ' . $x . '. ');
                    } else {
                        if (!isset($hasErrors)) {
                            $this->db->query($query);
                                // Check if Update didn't occur then notify user for the row that has error.
                            $updated_data = $this->db->query("SELECT model FROM tbl_new_plan WHERE  month_year='" . substr($data['month_year'], 1) . "' AND revision='" . $data['revision'] . "' AND model='" . $data['model_id'] . "' AND price='" . $data['new_price'] . "' ")->row();
                            if ($updated_data == NULL) {
                                $this->session->set_flashdata('error', 'Wrong Data on Row ' . $x . '. Please Check. ');
                            } else {
                                    // Push to array, to check for duplicate.
                                array_push($arr, $updated_data->model);
                            }
                        }
                    }
                }
                    // Check for Duplicate Model ID then set error if true;
                    // $duplicates = array_count_values($arr);
                    // foreach ($duplicates as $dp => $d) {
                    //     if ($uploadCount >= $modelsCount6Months->modelCount) {
                    //         if ($d > 6) {
                    //             $this->session->set_flashdata('error', 'Duplicate Data on Model ID:' . $dp . '. Upload Count: ' . $uploadCount . ' <hr> Models Count ' . $modelsCount6Months->modelCount . ' ');
                    //         }
                    //     } else {
                    //         if ($d > 1) {
                    //             $this->session->set_flashdata('error', 'Found Duplicate Data on Model ID:' . $dp . '. Upload Count: ' . $uploadCount . ' <hr> Models Count ' . $modelsCount6Months->modelCount . ' ');
                    //         }
                    //     }
                    // }

                    // If flashdata(error) is not set, set flash data success and display affected rows.
                $affected_rows = count($arr);
                if (!isset($hasErrors)) {
                    $this->session->set_flashdata('success', 'Successfully uploaded. ' . $affected_rows . ' Rows Affected. ');
                    $action_made = "UPLOADED , $affected_rows ROWS AFFECTED";
                }

                $this->psim->UserLog($user_id, $role_text, $name, $action_made);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                    . '": ' . $e->getMessage());
            }
        } else {
            $this->session->set_flashdata('error', $error['error']);
        }
    }
    redirect('index.php/PSI/psi_edit');
}


public function SubmitPSI()
{
    $pd = $_POST;
    if (isset($pd)) {
        $selectedDPRNO = $this->tm->get_dpr_no();
        $revId = $this->db->query("SELECT ID FROM tbl_revision WHERE revision_date='$selectedDPRNO' ")->row();
        $this->db->query("UPDATE tbl_approved_psi SET status=0 WHERE rev_id='$revId->ID' ");
        $this->db->query("UPDATE tbl_revision SET status='Pending' WHERE ID='$revId->ID' ");
            // SEND EMAIL TO PSI APPROVERS
        file_get_contents('http://10.216.2.202/Datawarehouse_approval/prepared_by.php');
    }
}




  

  function duplicate_model()
{
    echo "UNDER DEVELOPMENT!";
    $pd = $_POST;
    if (isset($pd)) {
        $selectedModelID = $pd['SelectedModel'];
        $modelData = $this->db->query("SELECT * FROM tbl_models WHERE models_id=" . $selectedModelID . " ")->row();


        echo "$selectedModelID";
    }
    $data = array(
     'item_code' => "$modelData->item_code",
     'models_code' => "$modelData->models_code",
     'models_desc' => "$modelData->models_desc",
     'customer_models_id' => "$modelData->customer_models_id",
     'active_price' => "$modelData->active_price",
     'status' => "$modelData->status",
     'isHidden' => "$modelData->isHidden",
     'model_category_id' => "$modelData->model_category_id",
     'sorting_code' => "$modelData->sorting_code",
     'model_remarks' => "$modelData->model_remarks"


 );
    $this->db->insert('tbl_models',$data);

    $current_revision = $this->tm->get_dpr_no();

    $get_last_row = $this->db->query("SELECT * FROM cias.tbl_models ORDER BY models_id DESC LIMIT 1")->row();
    $model_data_plan = $this->db->query("SELECT * FROM tbl_new_plan WHERE model= '$selectedModelID' AND revision = '$current_revision '")->result();
    foreach ($model_data_plan as $model_plan ) : 

        $get_planning = array(
         'customer' => "$model_plan->customer",
         'model' => "$get_last_row->models_id",
         'price' => "$model_plan->price",
         'month_year' => "$model_plan->month_year",
         'revision' => "$current_revision",
         'sales_qty' => "0",
         'prod_qty' => "0",
         'inventory' => "0",
         'beginning_inv' => "0",
         'month_year_numerical' => "$model_plan->month_year_numerical",
         'category' => "$model_plan->category",
         'working_days' => "$model_plan->working_days",
         'isProdEdit' => "0",
         'isSalesEdit' => "0",
         'isActual' => "$model_plan->isActual",
         'isHidden' => "$model_plan->isHidden",
         'isDuplicate' => "0"


     );
        $this->db->insert('tbl_new_plan',$get_planning);

    endforeach;

}

function hideModel()
{
    $pd = $_POST;
    if (isset($pd)) {
        $selectedModelID = $pd['SelectedModel'];
        $modelData = $this->db->query("SELECT * FROM tbl_models WHERE models_id=" . $selectedModelID . " ")->row();
            // print_r($modelData);
        $query = $this->db->query("UPDATE tbl_models SET isHidden='1' WHERE models_id='$modelData->models_id' ");
        if ($query) {
            echo "<h3>" . $modelData->models_desc . "</h3> is now Hidden";
        }
    }
}


 function remove()
{
   $pd = $_POST;
    if (isset($pd)) {
        $selectedModelID = $pd['SelectedModel'];
        $modelData = $this->db->query("SELECT * FROM tbl_models WHERE models_id=" . $selectedModelID . " ")->row();
            // print_r($modelData);
    $current_revision = $this->tm->get_dpr_no();
        $query = $this->db->query("DELETE FROM  tbl_new_plan  WHERE model='$modelData->models_id' ");
        $query2 = $this->db->query("DELETE FROM  tbl_models  WHERE models_id='$modelData->models_id' ");
        $query3 = $this->db->query("ALTER TABLE tbl_new_plan  AUTO_INCREMENT = 1");
        $query3 = $this->db->query("ALTER TABLE tbl_models  AUTO_INCREMENT = 1");

      
    }
}




}


// PSI NEW CONTROLLER

