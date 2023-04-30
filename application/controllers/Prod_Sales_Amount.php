<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Prod_Sales_Amount extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('PS_amount', 'ps');
        $this->load->model('Task_model', 'tm');
        $this->load->library('functions', 'ci');
        $this->isLoggedIn();
        $this->module = 'PS_amount';
    }

    function index()
    {
        echo "<code>DIRECTORY ACCESS FORBIDDEN</code>";
    }

    public function Production_amount()
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
        $data['category'] = $this->db->query("SELECT * FROM tbl_model_category")->result();
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : PSI';
            // $this->loadViews("reports/prod_amount.php", $this->global, $data, NULL);
            $this->loadViews("prod&sales_amt/prod_amount", $this->global, $data, NULL);
              // $this->loadViews("reports/404", $this->global, $data, NULL);

          
        }
    }


  

   

 public function Sales()
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
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : PSI';
            // $this->loadViews("reports/sales_amount.php", $this->global, $data, NULL);
            // $this->loadViews("reports/404", $this->global, $data, NULL);
            $this->loadViews("prod&sales_amt/sales_amount", $this->global, $data, NULL);

            
        }
    }




        public function Production_amount_viewing()
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
        $data['category'] = $this->db->query("SELECT * FROM tbl_model_category")->result();
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : PSI';
            $this->loadViews("prod&sales_amt/prod_amount_viewing", $this->global, $data, NULL);

          
        }
    }


  

   

 public function Sales_amount_viewing()
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
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : PSI';
            $this->loadViews("prod&sales_amt/sales_amount_viewing", $this->global, $data, NULL);

            
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



function Prod_Sales_report()
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
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selectedDPRNO);
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selectedDPRNO);
            $dateFrom = $FirstMonthOfCurrentRevision->month_year_numerical;
            $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;
            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);

            $this->global['pageTitle'] = 'DWH : NCFL Plan Report ';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $this->loadViews("reports/ncfl_prod_sales_report", $this->global, $data, NULL);
        }
    }
    function new_ncfl_actual_report()
    {
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : NCFL Plan Report Actual ';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $this->loadViews("reports/new_ncfl_actual_report", $this->global, $data, NULL);
        }
    }

    function get_actual_data()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $SelectedMonth = $dt->selectedMonth;

            $actual_data = $this->db->query("SELECT * FROM tbl_actual_data INNER JOIN tbl_models ON tbl_actual_data.model=tbl_models.models_id INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id WHERE month_year='$SelectedMonth' ORDER BY tbl_customer.sorting_code,tbl_models.sorting_code ASC")->result();
            echo json_encode($actual_data);
        }
    }




     /* -----------------------------------------------------------------------------------------------------
                        *** UPLOADING ACTUAL DATA 
    -----------------------------------------------------------------------------------------------------*/

    function upload_actual_data()
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
                    $countDataInSheet = count($allDataInSheet);

                    $hasErrors = 0;

                    for ($x = 2; $x <= $countDataInSheet; $x++) {
                        $data['model_id'] = $allDataInSheet[$x]["A"];
                        $data['model'] = $allDataInSheet[$x]["B"];
                        $data['month_year'] = substr($allDataInSheet[$x]["C"], 1);
                        $data['actual_price'] = $allDataInSheet[$x]["D"];
                        $data['actual_prod_qty'] = $allDataInSheet[$x]["E"];
                        $data['actual_sales_qty'] = $allDataInSheet[$x]["F"];


                        if (!is_numeric($data['actual_price']) || !is_numeric($data['actual_prod_qty']) || !is_numeric($data['actual_sales_qty'])) {
                            $this->session->set_flashdata('error', 'Non numeric number on row ' . $x . ', please check your excel file!');
                            $hasErrors = 1;
                        } elseif ($data['model_id'] == "" || empty($data['model_id'])) {
                            $this->session->set_flashdata('error', 'Missing Model ID on row ' . $x . '. ');
                            $hasErrors = 1;
                        } else {
                            $hasErrors = 0;
                            if ($hasErrors == 0) {
                                $tbl_models = $this->db->query("SELECT * FROM tbl_models WHERE models_id='" . $data['model_id'] . "' ")->row();
                                $customer = $tbl_models->customer_models_id;
                                $month_year_numerical = date("Y-m-d", strtotime($data['month_year']));
                                $actual_data = array(
                                    "auto_id" => NULL,
                                    "customer" => $customer,
                                    "model" => $data['model_id'],
                                    "actual_price" => $data['actual_price'],
                                    "month_year" => $data['month_year'],
                                    "actual_sales_qty" => $data['actual_sales_qty'],
                                    "actual_prod_qty" => $data['actual_prod_qty'],
                                    "month_year_numerical" => $month_year_numerical
                                );
                                $tbl_actual_data = $this->db->query("SELECT * FROM tbl_actual_data WHERE month_year='" . $data['month_year'] . "' AND model='" . $data['model_id'] . "' ")->row();
                                if ($tbl_actual_data == NULL) {
                                    $this->db->insert('tbl_actual_data', $actual_data);
                                } else {
                                    $this->session->set_flashdata('info', 'Updated Actual Data for ' . $data['month_year'] . ' ');
                                }
                                $this->session->set_flashdata('success', 'Uploaded Actual Data for ' . $data['month_year'] . ' ');
                            }
                        }
                    }

                    // if ($hasErrors == 0) {
                    //     $this->session->set_flashdata('success', 'Successfully uploaded.');
                    // }

                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
                }
            } else {
                $this->session->set_flashdata('error', $error['error']);
            }
        }
        redirect('index.php/Prod_Sales_Amount/new_ncfl_actual_report');
    }
    /* -----------------------------------------------------------------------------------------------------
                        UPLOADING ACTUAL DATA *** 
    -----------------------------------------------------------------------------------------------------*/





    function GetCurrentRevisionData()
    {
        $data = array();
        $CurrentRevisionData = $this->ps->GetCurrentRevisionData();
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

            $customers = $this->ps->GETPSCUSTOMER();
            $months = $this->ps->GETPSMONTH($selected_revision);
            $get_invty = $this->ps->GETPSBEGINNINGINVTY($SelectedMonths);
            $allModels = [];
            $psi = [];
            foreach ($customers as $customer) :
                $models = $this->ps->GETPSMODEL($customer->customers_id);
                $allModels[$customer->customers_desc] = $models;
                foreach ($models as $modelKey => $model) :
                    $psi[$customer->customers_desc][$modelKey]['modelDT'] = $model;
                    $inventory = 0;
                    foreach ($months as $month) :
                        $psiDT = $this->ps->GETPSDATA($model->models_id, $selected_revision, $month->month_year);
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











    
}
