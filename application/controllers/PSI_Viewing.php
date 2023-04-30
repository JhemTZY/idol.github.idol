<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class PSI_Viewing extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('PSI_MODEL', 'psim');
        $this->load->model('PS_amount', 'ps');
        $this->load->model('Task_model', 'tm');
        $this->load->library('functions', 'ci');
        $this->isLoggedIn();
        $this->module = 'PSI';
    }

    function index()
    {
        echo "<code>DIRECTORY ACCESS FORBIDDEN</code>";
    }

 /*   function PSI_View()
    {
       

          $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi'])) {
            $selected_revision = $p['revi'];
        }
        $selectedDPRNO = $this->tm->get_dpr_no();
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->tm->get_revision();
        $data['month_years'] = $this->tm->get_month();
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : PSI';
            $this->loadViews("reports/psi_viewing_excel.php", $this->global, $data, NULL);
        }
    }*/



    function PSI_View()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->tm->get_revision();

     

            $dateFrom = $dateTo = "";

            $dashngayon = substr($selectedDPRNO, 7, 1);
            if ($dashngayon == 1 || $dashngayon == 4) {
                $dateFrom = date("Y-m-01", strtotime("-1 month"));
            } else {
                $dateFrom = date("Y-m-01");
            }


            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selectedDPRNO);
            if (isset($LastMonthOfCurrentRevision)) {
                $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;
            }

               $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi'])) {
            $selected_revision = $p['revi'];
        }

               if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $this->global['pageTitle'] = 'DWH : PSI VIEWING';

            $data["selected_revision"] = $selected_revision;
            $data['revision'] = $this->tm->get_revision();
            $data['month_years'] = $this->tm->get_month();
            $data['psiEdit_dateFrom'] = $dateFrom;
            $data['psiEdit_dateTo'] = $dateTo;
            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $this->loadViews("reports/psi_viewing_excel", $this->global, $data, NULL);
        }
    }



    


 


function GeneratePSIedit()
    {
        $selected_revision = $this->tm->get_dpr_no();
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
             $selected_revision = $dt->SelectedRevision;
            $SelectedMonths = $dt->SelectedMonths;
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;

            $customers = $this->ps->GetCustomers();
            $months = $this->ps->GetMonths($selected_revision);
            $get_invty = $this->ps->GET_beginning_inv($SelectedMonths);
            $allModels = [];
            $psi = [];
            foreach ($customers as $customer) :
                $models = $this->ps->GetModels($customer->customers_id);
                $allModels[$customer->customers_desc] = $models;
                foreach ($models as $modelKey => $model) :
                    $psi[$customer->customers_desc][$modelKey]['modelDT'] = $model;
                    $inventory = 0;
                    foreach ($months as $month) :
                        $psiDT = $this->ps->GetPSIData($model->models_id, $selected_revision, $month->month_year);
                        if ($psiDT !== NULL) {
                          
                            if ($model->cat_code == "EOL") {
                                $inventory = $psiDT->inventory;
                            }
                            $inventory += $psiDT->auto_inventory;
                            $psiDT->auto_inventory = $inventory;
                            $psi[$customer->customers_desc][$modelKey][$month->month_year] = $psiDT;


                            if ($model->cat_code == "MP" || $model->cat_code == "SMPL" || $model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                                if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'])) {
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_PROD_AMOUNT'] += $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_SALES_AMOUNT'] += $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                                    $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                                } else {
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_PROD_AMOUNT'] = $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_SALES_AMOUNT'] = $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                                    $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                                }
                            }



   if ($model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                                if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'])) {
                               
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
                            
                                // EOL
                                if (isset($psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'])) {
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] += $psiDT->inventory;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                                } else {
                                      $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] = $psiDT->inventory;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                                }
                            }



                            if ($model->cat_code == "MP" || $model->cat_code == "SMPL" ) {
                                 if (isset($psi['GrandTotal']['cat_code'][$month->month_year]['prod_qty'])) {
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                                     $psi['GrandTotal']['cat_code'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_PROD_AMOUNT'] += $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_SALES_AMOUNT'] += $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                                    $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] = " GRAND TOTAL";
                                   
                                } else {
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_PROD_AMOUNT'] = $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_SALES_AMOUNT'] = $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                                    $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] =  " GRAND TOTAL";
                                }
                                $this->movetobottom($psi, "GrandTotal");
                            }



                        }
                    endforeach;
                endforeach;
            endforeach;

            $data = array(
                "customers" => $customers,
                "months" => $months,
                "get_invty" => $get_invty,
                "AllModels" => $allModels,
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

            if ($psi == 'price') {
                echo "Price Updated";
                $this->db->query("UPDATE tbl_new_plan SET price='$value ' WHERE auto_id='$id' AND revision='$revision' ");
            }
           if ($psi == 'prod_qty') {
                echo "Production Updated";
                $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value', inventory=prod_qty-sales_qty  WHERE auto_id='$id' AND revision='$revision' ");
            }
            if ($psi == 'sales_qty') {
                echo "Sales Updated";
                $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' , inventory=prod_qty-sales_qty   WHERE auto_id='$id' AND revision='$revision' ");
            }
            if ($psi == 'inventory') {
                echo "Inventory Updated";
                $this->db->query("UPDATE tbl_new_plan SET inventory='$value'  WHERE auto_id='$id' AND revision='$revision' ");
            }

            if ($psi == 'inventory') {
                echo "Inventory Updated";
                $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value'  WHERE auto_id='$id' AND revision='$revision' ");
            }
        }
    }
    function GetCurrentRevisionData()
    {
        $data = array();
        $CurrentRevisionData = $this->psim->GetCurrentRevisionData();
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
                    $duplicates = array_count_values($arr);
                    foreach ($duplicates as $dp => $d) {
                        if ($uploadCount >= $modelsCount6Months->modelCount) {
                            if ($d > 6) {
                                $this->session->set_flashdata('error', 'Duplicate Data on Model ID:' . $dp . '. Upload Count: ' . $uploadCount . ' <hr> Models Count ' . $modelsCount6Months->modelCount . ' ');
                            }
                        } else {
                            if ($d > 1) {
                                $this->session->set_flashdata('error', 'Found Duplicate Data on Model ID:' . $dp . '. Upload Count: ' . $uploadCount . ' <hr> Models Count ' . $modelsCount6Months->modelCount . ' ');
                            }
                        }
                    }

                    // If flashdata(error) is not set, set flash data success and display affected rows.
                    $affected_rows = count($arr);
                    if (!isset($hasErrors)) {
                        $this->session->set_flashdata('success', 'Successfully uploaded. ' . $affected_rows . ' Rows Affected. ');
                    }
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


    public function Actual(){
          $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi'])) {
            $selected_revision = $p['revi'];
        }
        $selectedDPRNO = $this->tm->get_dpr_no();
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->tm->get_revision();
        $data['month_years'] = $this->tm->get_month();
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : PSI';
            $this->loadViews("reports/actual_data_viewing.php", $this->global, $data, NULL);
        }
    
}

 function Actual_Data()
    {
        $selected_revision = $this->tm->get_dpr_no();
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
             $selected_revision = $dt->SelectedRevision;
            $SelectedMonths = $dt->SelectedMonths;
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;

            $customers = $this->ps->GetCustomers();
            $months = $this->ps->GetActualMonths($selected_revision);
            $get_invty = $this->ps->GET_beginning_inv($SelectedMonths);
            $allModels = [];
            $psi = [];
            foreach ($customers as $customer) :
                $models = $this->ps->GetModels($customer->customers_id);
                $allModels[$customer->customers_desc] = $models;
                foreach ($models as $modelKey => $model) :
                    $psi[$customer->customers_desc][$modelKey]['modelDT'] = $model;
                    $inventory = 0;
                    foreach ($months as $month) :
                        $psiDT = $this->ps->GetPSIData($model->models_id, $selected_revision, $month->month_year);
                        if ($psiDT !== NULL) {
                            if ($model->cat_code == "EOL") {
                                $inventory = $psiDT->inventory;
                            }
                            $inventory += $psiDT->auto_inventory;
                            $psiDT->auto_inventory = $inventory;
                            $psi[$customer->customers_desc][$modelKey][$month->month_year] = $psiDT;

                            if ($model->cat_code == "MP" || $model->cat_code == "SMPL" || $model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                                if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'])) {
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_PROD_AMOUNT'] += $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_SALES_AMOUNT'] += $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                                    $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                                } else {
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_PROD_AMOUNT'] = $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_SALES_AMOUNT'] = $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                                    $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                                }
                            }




   if ($model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                                if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'])) {
                               
                                       $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_PROD_AMOUNT'] += $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_SALES_AMOUNT'] += $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                                    $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                                } else {
                                    
                                             $psi[$customer->customers_desc]['Total'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_PROD_AMOUNT'] = $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['TOTAL_SALES_AMOUNT'] = $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi[$customer->customers_desc]['Total'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                                    $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                                }
                            }






                           

                            if ($model->cat_code == "EOL") {
                                $inventory = $psiDT->inventory;
                                // EOL
                                if (isset($psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'])) {
                                      $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] += $inventory;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                                } else {
                                      $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] = $inventory;
                                    $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                                    $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                                }
                            }



                            if ($model->cat_code == "MP" || $model->cat_code == "SMPL" ) {
                                 if (isset($psi['GrandTotal']['cat_code'][$month->month_year]['prod_qty'])) {
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                                     $psi['GrandTotal']['cat_code'][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_PROD_AMOUNT'] += $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_SALES_AMOUNT'] += $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['Inventory_Amount'] += $psiDT->Inventory_Amount;
                                    $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                                    $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] = " GRAND TOTAL";
                                   
                                } else {
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['auto_inventory'] = $psiDT->auto_inventory;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_PROD_AMOUNT'] = $psiDT->TOTAL_PROD_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['TOTAL_SALES_AMOUNT'] = $psiDT->TOTAL_SALES_AMOUNT;
                                    $psi['GrandTotal']['cat_code'][$month->month_year]['Inventory_Amount'] = $psiDT->Inventory_Amount;
                                    $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                                    $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] =  " GRAND TOTAL";
                                }
                                $this->movetobottom($psi, "GrandTotal");
                            }


                            // if ($model->cat_code == "EOL") {
                            //      if (isset($psi['GrandTotal'][$model->cat_code][$month->month_year]['prod_qty'])) {
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['prod_qty'] += $psiDT->prod_qty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['auto_inventory'] += $psiDT->auto_inventory;
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['cat_code'] = "GRANDTOTAL";
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['models_desc'] = " GRAND TOTAL";
                                   
                            //     } else {
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['prod_qty'] = $psiDT->prod_qty;
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
                "get_invty" => $get_invty,
                "AllModels" => $allModels,
                "psiData" => $psi
            );
            echo json_encode($data);
        }
    }



    
}
