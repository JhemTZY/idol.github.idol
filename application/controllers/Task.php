<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Task extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('Task_model', 'tm');
        $this->load->library('functions', 'ci');
         $this->load->model('PSI_MODEL', 'psim');
        $this->isLoggedIn();
        $this->module = 'Task';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    function index()
    {
        redirect('index.php/taskListing');
    }

    /**
     * This function is used to load the task list
     */


    /* -----------------------------------------------------------------------------------------------------
						***NCFL PLAN REPORT - MAM KRISHA
	-----------------------------------------------------------------------------------------------------*/


    function ncfl_plan_report()
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
            $this->loadViews("reports/ncfl_plan_report", $this->global, $data, NULL);
        }
    }
    function ncfl_plan_report_actual()
    {
        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : NCFL Plan Report Actual ';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $this->loadViews("reports/ncfl_plan_report_actual", $this->global, $data, NULL);
        }
    }

    function get_actual_data()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $SelectedMonth = $dt->selectedMonth;

            $actual_data = $this->db->query("SELECT * FROM tbl_actual_data INNER JOIN tbl_models ON tbl_actual_data.model=tbl_models.models_id INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id WHERE month_year='$SelectedMonth' ORDER BY tbl_actual_data.customer, tbl_models.sorting_code ASC ")->result();
            echo json_encode($actual_data);
        }
    }

    /* -----------------------------------------------------------------------------------------------------
						NCFL PLAN REPORT - MAM KRISHA***
	-----------------------------------------------------------------------------------------------------*/



    /* -----------------------------------------------------------------------------------------------------
						UPDATING PRODUCTION AND SALES QUANTITY OF MODELS - TBL_NEW_PLANS
	-----------------------------------------------------------------------------------------------------*/
    function get_models_by_id()
    {
        $d = $_POST;
        $model_to_update = $this->tm->get_models_by_id($d['modelID']);
        echo json_encode($model_to_update);
    }
    function update_model_prod_qty()
    {
        $d = $_POST;
        if (is_numeric($d['new_prod_qty'])) {
            $this->tm->update_model_prod_qty($d['new_prod_qty'], $d['modelID']);
        } else {
            // $this->session->set_flashdata("error", "");
            // echo "<script>alert('The production quantity you entered is not a number!')</script>";
        }
        // redirect('index.php/task/PSI_EDIT_BETA');
    }
    function update_model_sales_qty()
    {
        $d = $_POST;
        if (is_numeric($d['new_sales_qty'])) {
            $this->tm->update_model_sales_qty($d['new_sales_qty'], $d['modelID']);
        } else {
            $this->session->set_flashdata("error", "The sales quantity you entered is not a number!");
        }
        // redirect('index.php/task/PSI_EDIT_BETA');
    }
    function update_model_monthly_price()
    {
        $d = $_POST;
        if (is_numeric($d['new_monthly_price'])) {
            $this->tm->update_model_monthly_price($d['new_monthly_price'], $d['modelID']);
        } else {
            $this->session->set_flashdata("error", "The price you entered is not a number!");
        }
        redirect('index.php/task/PSI_EDIT_BETA');
    }
    /* -----------------------------------------------------------------------------------------------------
						UPDATING PRODUCTION AND SALES QUANTITY OF MODELS - TBL_NEW_PLANS
	-----------------------------------------------------------------------------------------------------*/


    /* -----------------------------------------------------------------------------------------------------
						Create Plans !
	-----------------------------------------------------------------------------------------------------*/

    function add()
    {
        $session = isset($this->page_session["plan_form"]) ? $this->page_session["plan_form"] : array();
        //$this->functions->echo_array($session);
        $selectedMonth = date("F-Y");
        $prd_qty = "prod_qty";
        $sales_qty = "sales_qty";


        $selectedDPRNO = $this->tm->get_dpr_no();

        //$this->functions->echo_array($pendingPlan);
        //exit;

        $page = "ps_plan_form";

        //$data["planList"] = $planList;
        $data["selectedMonth"] = $selectedMonth;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["prd_qty"] = $prd_qty;
        $data["sales_qty"] = $sales_qty;

        $data["page"] = $page;


        if (!$this->hasUserCreateAccess()) {
            $this->loadThis();
        } else {

            $this->load->library('form_validation');

            $this->form_validation->set_rules('role', 'Role Text', 'trim|required|max_length[50]');
            $this->load->model('task_model');
            $data['customers'] = $this->task_model->getUserRoles()->result();
            $data['models'] = $this->task_model->getUserRoles2()->result();
            $data['new_plan'] = $this->task_model->get_new_plan()->result();
            $numberofMonths = 7;
            $dateFrom = date('Y-m-d', strtotime("-1 month"));
            $dateTo = date('Y-m-d', strtotime("+" . $numberofMonths . "month"));
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            $this->load->library('pagination');
            $data['models'] = $this->tm->get_models($searchText);
            $data['recording'] = $this->tm->showTables($searchText);
            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);
            $data['modeling'] = $this->tm->get_models($selectedDPRNO);
            $this->global['pageTitle'] = 'DWH: Create Plan';
            $this->loadViews("task/add", $this->global, $data,  NULL);
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						Create Plans !
	-----------------------------------------------------------------------------------------------------*/



    /* -----------------------------------------------------------------------------------------------------
						*** PSI | MERGED
	-----------------------------------------------------------------------------------------------------*/

    function psi_revision_viewing()
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
            $this->loadViews("reports/psi_revision_viewing_copy.php", $this->global, $data, NULL);
        }
    }


    function get_customers()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $customers = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $data = array(
                "customers" => $customers,
            );
            echo json_encode($data);
        }
    }


    function generateMergedPSI()
    {
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $selected_revision = $dt->SelectedRevision;
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;

            $distinctCustomers = $this->tm->get_table_customer();
            $months = $this->tm->get_dates_from_current_revision($selected_revision, $FirstMonthOfCurrentRevision, $LastMonthOfCurrentRevision);
            $distinctModels = $this->tm->get_distinct_models();
            $psiMergedData = [];
            foreach ($distinctModels as $model) :
                $models_code = $model->models_code;
                $item_code = $model->item_code;
                $models_desc = $model->models_desc;
                $customers_id = $model->customers_id;
                $totalMergedInventory = 0;
                foreach ($months as $month) :
                    $mergedModelsData = $this->tm->get_models_merged_data($customers_id, $month->month_year, $models_code, $selected_revision);
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;
                    // Total Inventory
                    $totalMergedInventory += $mergedModelsData->mergedInventory; // Inventory = EndInventory + NewInventory - Sales
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedInventory'] =  $totalMergedInventory;
                    // Total Production Quantity
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'] += $mergedModelsData->mergedProdQty;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;
                    }
                    // Total Sales Quantity
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] += $mergedModelsData->mergedSalesQty;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    }
                    // Total Inventory
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'] += $totalMergedInventory;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'] = $totalMergedInventory;
                    }
                    $this->movetobottom($psiMergedData[$model->customers_desc], "Total");
                endforeach;
            endforeach;

            $data = array(
                "distinctCustomers" => $distinctCustomers,
                "distinctModels" => $distinctModels,
                "months" => $months,
                "psiMergedData" => $psiMergedData,
            );
        }
        echo json_encode($data);
    }








 function psimerge()
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
            $this->loadViews("reports/psi_merge.php", $this->global, $data, NULL);
        }
    }


    function generatemerge()
    {
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $selected_revision = $dt->SelectedRevision;
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;

            $distinctCustomers = $this->tm->get_table_customer();
            $months = $this->tm->get_dates_from_current_revision($selected_revision, $FirstMonthOfCurrentRevision, $LastMonthOfCurrentRevision);
            $distinctModels = $this->tm->get_distinct_models();
            $psiMergedData = [];
            foreach ($distinctModels as $model) :
                $models_code = $model->models_code;
                $models_desc = $model->models_desc;
                $customers_id = $model->customers_id;
                $totalMergedInventory = 0;
                foreach ($months as $month) :
                    $mergedModelsData = $this->tm->get_models_merged_data($customers_id, $month->month_year, $models_code, $selected_revision);
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;
                    // Total Inventory
                    $totalMergedInventory += $mergedModelsData->mergedInventory; // Inventory = EndInventory + NewInventory - Sales
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedInventory'] =  $totalMergedInventory;
                    // Total Production Quantity
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'] += $mergedModelsData->mergedProdQty;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;
                    }
                    // Total Sales Quantity
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] += $mergedModelsData->mergedSalesQty;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    }
                    // Total Inventory
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'] += $totalMergedInventory;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'] = $totalMergedInventory;
                    }
                    $this->movetobottom($psiMergedData[$model->customers_desc], "Total");
                endforeach;
            endforeach;

            $data = array(
                "distinctCustomers" => $distinctCustomers,
                "distinctModels" => $distinctModels,
                "months" => $months,
                "psiMergedData" => $psiMergedData,
            );
        }
        echo json_encode($data);
    }











    function generateMergePsi_Approver()
    {
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $selected_revision = $dt->SelectedRevision;
            /*$selected_revision = $dt->SelectedRevision;
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;

            $distinctCustomers = $this->tm->get_table_customer();
            $months = $this->tm->get_dates_from_current_revision($selected_revision, $FirstMonthOfCurrentRevision, $LastMonthOfCurrentRevision);
            $distinctModels = $this->tm->get_distinct_models();
            $psiMergedData = [];
            foreach ($distinctModels as $model) :
                $models_code = $model->models_code;
                $models_desc = $model->models_desc;
                $item_code = $model->item_code;
                $customers_id = $model->customers_id;
                $totalMergedInventory = 0;
                foreach ($months as $month) :
                    $mergedModelsData = $this->tm->get_models_merged_data($customers_id, $month->month_year, $models_code, $selected_revision);
                    $psiMergedData[$model->customers_desc][$mergedModelsData->item_code][$models_desc][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    $psiMergedData[$model->customers_desc][$mergedModelsData->item_code][$models_desc][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;*/
            /*
                endforeach;
            endforeach;*/
            $data = array(
                // "distinctCustomers" => $distinctCustomers,
                // "distinctModels" => $distinctModels,
                // "months" => $months,
                // "psiMergedData" => $psiMergedData,
            );
        }
        echo json_encode($data);
    }

    /* -----------------------------------------------------------------------------------------------------
							 PSI | MERGED ***
	-----------------------------------------------------------------------------------------------------*/



     




    /* -----------------------------------------------------------------------------------------------------
						***NCFL PLAN REPORT REVISION VIEWING
	-----------------------------------------------------------------------------------------------------*/

    function ncfl_plan_report_rev_view()
    {
        $post = $_POST;
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $post['revi'];
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $dateFrom = $dateTo = "";
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selectedDPRNO);
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selectedDPRNO);
            $dateFrom = $FirstMonthOfCurrentRevision->month_year_numerical;
            $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;
            $data['current_rev'] = $this->tm->get_dates_from_current_revision($post['revi'], $dateFrom, $dateTo);

            $this->global['pageTitle'] = 'DWH : NCFL Plan Report ';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("reports/ncfl_plan_report_rev_view", $this->global, $data, NULL);
        }
    }

    /* -----------------------------------------------------------------------------------------------------
						NCFL PLAN REPORT REVISION VIEWING***
	-----------------------------------------------------------------------------------------------------*/

    function edit($taskId = NULL)
    {
        if (!$this->hasUsersUpdateAccess()) {
            $this->loadThis();
        } else {
            if ($taskId == null) {
                redirect('index.php/taskListing');
            }

            $data['taskInfo'] = $this->tm->getTaskInfo($taskId);

            $this->global['pageTitle'] = 'Customer / Model : Edit Customers / Model';

            $this->loadViews("task/edit", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to edit the user information
     */
    function editTask()
    {
        if (!$this->hasUsersUpdateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $taskId = $this->input->post('auto_id');

            $this->form_validation->set_rules('customers', 'Task Title', 'trim|required|max_length[256]');
            $this->form_validation->set_rules('models_details_id', 'Description', 'trim|required|max_length[1024]');

            if ($this->form_validation->run() == FALSE) {
                $this->edit($taskId);
            } else {
                $taskTitle = $this->security->xss_clean($this->input->post('customers'));
                $description = $this->security->xss_clean($this->input->post('models_details_id'));

                $taskInfo = array('sales_qty' => $taskTitle, 'prod_qty' => $description, 'sales_qty');


                $result = $this->tm->editTask($taskInfo, $taskId);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Production / Sales updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Production / Sales updation failed');
                }

                redirect('index.php/task/add');
            }
        }
    }

    public function get_model_customer()
    {
        $model = $this->input->post('id', TRUE);
        $data = $this->tm->get_model_customer($model)->result();

        echo json_encode($data);
    }


    /*----------------------------------------------------------------------------------------
        ***NEW PLAN - SIX MONTHS
    ---------------------------------------------------------------------------------------- */
    public function new_plan()
    {
      
        $customer = $this->input->post('customers');
        $models_details_id = $this->input->post('models_details_id');
        $model_revision = $this->input->post('model_revision');
        $start_month = $this->input->post('start_month');
        $numberofMonths = $this->input->post('numberofMonths') - 1;
        $user_id =  $this->session->userdata('userId');
            $name =  $this->session->userdata('name');
            $role_text =  $this->session->userdata('roleText');
            $cus = $this->db->query("SELECT customers_desc FROM tbl_customer WHERE customers_id='$customer' ")->row();


        $startmonth_converted = date_parse($start_month);

        $starting_month = strval($startmonth_converted['year'] . '-' . $startmonth_converted['month'] . '-' . '01');
        $ending_month = date('F-Y-29', strtotime("+" . strval($numberofMonths) . "month"));

        $begin = new DateTime($starting_month);
        $end = new DateTime($ending_month);

        $daterange = new DatePeriod($begin, new DateInterval('P1M'), $end);
        foreach ($daterange as $date) {
            $date_range =  $date->format("F-Y");
            $date_range_numerical =  $date->format("Y-m-d");
            // Loop ng MODELS
            if (count($models_details_id) > 0) {
                foreach ($models_details_id as $models) :
                    $price = $this->db->query("SELECT active_price FROM tbl_models WHERE models_id='$models' ")->row();
                    $mod = $this->db->query("SELECT models_desc FROM tbl_models WHERE models_id='$models' ")->row();

                    // var_dump($price);
                    // print_r($models."<hr>");
                    $new_plan_data = array(
                        "auto_id" => NULL,
                        "customer" => $customer,
                        "model" => $models,
                        "price" => $price->active_price,
                        "month_year" => $date_range,
                        "revision" => $model_revision,
                        "sales_qty" => 0,
                        "prod_qty" => 0,
                        "month_year_numerical" => $date_range_numerical,
                        "working_days" => '0',
                        "isActual" => 'PLAN',
                        "isHidden" => 'Display',
                       
                    );


                    // DO NOT INSERT DATA to tbl_new_plan if models with the current revision and month ALREADY EXIST.
                    $tbl_new_plan_models = array();
                    $tbl_new_plan = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$models' AND month_year='$date_range' AND revision='$model_revision' ")->result();
                    if (count($tbl_new_plan) > 0) {
                        $tbl_new_plan_models = array($tbl_new_plan[0]->model);
                    }
                    if (in_array($models, $tbl_new_plan_models)) {
                        $this->session->set_flashdata('warning', 'There are Selected Models that already exist');
                    } else {
                        $result = $this->db->insert('tbl_new_plan', $new_plan_data);
                        if ($result == true) {
                            $this->session->set_flashdata('success', 'Sales Planning System created successfully');
                                $action_made = "Created Plan, $cus->customers_desc : $mod->models_desc";

                $this->psim->UserLog($user_id,  $role_text, $name, $action_made);
                        } else {
                            $this->session->set_flashdata('error', 'Sales Planning System failed to create');
                        }
                    }

                endforeach;
            }
        }

        redirect('index.php/task/add');
    }
    /*----------------------------------------------------------------------------------------
        NEW PLAN - SIX MONTHS***
    ---------------------------------------------------------------------------------------- */

    /*----------------------------------------------------------------------------------------
        ***NEW PLAN - ONE MONTH
    ---------------------------------------------------------------------------------------- */
    public function new_plan_one_month()
    {
        // POST DATA
        $pd = $_POST;
        $htmldateTOphpdate = strtotime($pd['oneMonth']);
        $month_year =  date('F-Y', $htmldateTOphpdate);
        $month_year_numerical =  date('Y-m-j', $htmldateTOphpdate);
        $model_revision = $pd['model_revision'];
        // Loop ng MODELS
        if (count($pd['models_details_id']) > 0) {
            foreach ($pd['models_details_id'] as $models) :
                $price = $this->db->query("SELECT active_price FROM tbl_models WHERE models_id='$models' ")->row();
                $new_plan_data = array(
                    "auto_id" => NULL,
                    "customer" => $pd['customers'],
                    "model" => $models,
                    "price" => $price->active_price,
                    "month_year" => $month_year,
                    "revision" => $model_revision,
                    "sales_qty" => 0,
                    "prod_qty" => 0,
                    "month_year_numerical" => $month_year_numerical,
                     "working_days" => '0',
                        "isActual" => 'PLAN',
                        "isHidden" => 'Display',
                );
                // DO NOT INSERT DATA to tbl_new_plan if models with the current revision and month ALREADY EXIST.
                $tbl_new_plan_models = array();
                $tbl_new_plan = $this->db->query("SELECT * FROM tbl_new_plan WHERE model='$models' AND month_year='$month_year' AND revision='$model_revision' ")->result();
                if (count($tbl_new_plan) > 0) {
                    $tbl_new_plan_models = array($tbl_new_plan[0]->model);
                }
                if (in_array($models, $tbl_new_plan_models)) {
                    $this->session->set_flashdata('error', 'Selected Models already exist');
                } else {
                    $result = $this->db->insert('tbl_new_plan', $new_plan_data);
                    if ($result == true) {
                        $this->session->set_flashdata('success', 'Successfully Generated 1 Month Plan');
                    }
                }
            endforeach;
        }
        redirect('index.php/task/add');
    }
    /*----------------------------------------------------------------------------------------
        NEW PLAN - ONE MONTH***
    ---------------------------------------------------------------------------------------- */

    // public function create_new_revision()
    // {
    //     $revision_date = $_POST['revision_date'];
    //     if (isset($revision_date) && !empty($revision_date)) {

    //         // *** 1. Get the current revision ***
    //         $query = $this->db->query('SELECT * FROM tbl_revision WHERE isActive="1" ');
    //         $current_revision = $query->row()->revision_date;

    //         // *** 2. Get all data from current revision ***

    //         $data_from_current_rev = array();
    //         if (isset($current_revision)) {
    //             $q = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$current_revision' ");
    //             $data_from_current_rev = $q->result();
    //         }

    //         // Check tbl_new_plan if current revision already exist.
    //         $rev_exist = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$revision_date' LIMIT 1 ")->result();
    //         if (count($rev_exist) >= 1) {
    //             // print_r($rev_exist);
    //             // echo "Revision already made! ";
    //             $this->session->set_flashdata('error', 'REVISION ALREADY MADE !');
    //         } else {
    //             // *** 3. Insert all the data from current revision and change revision to new revision ***
    //             if (count($data_from_current_rev) > 0) {
    //                 foreach ($data_from_current_rev as $row) {
    //                     $data = array(
    //                         "customer" => $row->customer,
    //                         "model" => $row->model,
    //                         "price" => $row->price,
    //                         "month_year" => $row->month_year,
    //                         "revision" => $revision_date,
    //                         "sales_qty" => $row->sales_qty,
    //                         "prod_qty" => $row->prod_qty,
    //                         "month_year_numerical" => $row->month_year_numerical
    //                     );
    //                     $this->db->insert('tbl_new_plan', $data);
    //                 }
    //             }

    //             // 4. SET ALL EXISTING REVISION TO INACTIVE
    //             $this->db->query("UPDATE tbl_revision SET isActive='0' WHERE 1");

    //             // 5. CREATE NEW REVISION IN tbl_revision and set it as current revision
    //             $date_created = date("Y-m-j");
    //             $data = array(
    //                 "ID" => NULL,
    //                 "revision_date" => "$revision_date",
    //                 "isActive" => 1,
    //                 "date_created" => $date_created,
    //                 "status" => "Pending"
    //             );
    //             $this->db->insert('tbl_revision', $data);
    //             $this->session->set_flashdata('success', 'SUCCESSFULLY CREATED A NEW REVISION');

    //             // 6. GET ALL ACTIVE APPROVERS OF THE PSI
    //             $approvers = $this->db->query("SELECT * FROM tbl_approver WHERE documentType='PSI' AND isActive='1' ")->result();
    //             // 7. CREATE APPROVERS ON THE NEWLY CREATED PSI
    //             $newlyCreatedPSI = $this->db->query("SELECT * FROM tbl_revision WHERE isActive='1' ")->row();
    //             foreach ($approvers as $approver) {
    //                 $data = array(
    //                     "approver_id" => $approver->approver_id,
    //                     "rev_id" => $newlyCreatedPSI->ID,
    //                     "status" => 0
    //                 );
    //                 $this->db->insert('tbl_approved_psi', $data);
    //             }
    //             // 8. EMAIL ALERT THE APPROVERS
    //             file_get_contents('http://10.216.2.202/Datawarehouse_approval/');
    //         }
    //     } else {
    //         $this->session->set_flashdata('error', 'REVISION IS NOT SET !');
    //     }
    //     redirect('index.php/task/PSI_EDIT_BETA');
    // }

    /* -----------------------------------------------------------------------------------------------------
						*** UPLOADING PLANS
	-----------------------------------------------------------------------------------------------------*/

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
        redirect('index.php/task/PSI_EDIT_BETA');
    }

    /* -----------------------------------------------------------------------------------------------------
						UPLOADING PLANS*** 
	-----------------------------------------------------------------------------------------------------*/

    /* -----------------------------------------------------------------------------------------------------
						*** PSI_EDIT_BETA
	-----------------------------------------------------------------------------------------------------*/

 function PSI_Report()
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
            $this->loadViews("reports/psi_report", $this->global, $data, NULL);
        }
    }


    public function PSI_EDIT_BETA()
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
            $this->loadViews("reports/psi_Edit_beta", $this->global, $data, NULL);
        }
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
            file_get_contents('http://10.216.2.202/Datawarehouse_approval/noted_by.php');
        }
    }

    public function duplicate_model()
    {
        echo "UNDER DEVELOPMENT!";
        $pd = $_POST;
        if (isset($pd)) {
            $selectedModelID = $pd['SelectedModel'];
            echo "$selectedModelID";
        }
    }

    public function hideModel()
    {
        $pd = $_POST;
        if (isset($pd)) {
            $selectedModelID = $pd['SelectedModel'];
            $modelData = $this->db->query("SELECT * FROM tbl_models WHERE models_id=" . $selectedModelID . " ")->row();
            // print_r($modelData);
            $query = $this->db->query("UPDATE tbl_models SET status='Inactive' WHERE models_code='$modelData->models_code' ");
            if ($query) {
                echo "<h3>" . $modelData->models_desc . "</h3> is now Hidden";
            }
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						    PSI_EDIT_BETA ***
	-----------------------------------------------------------------------------------------------------*/

    /* -----------------------------------------------------------------------------------------------------
						Production Amount *** 
	-----------------------------------------------------------------------------------------------------*/
   



    function prod_amount_viewing()
    {
        $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi']))
            $selected_revision = $p['revi'];

        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selected_revision;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $dateFrom = $dateTo = "";
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision);
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision);
            $dateFrom = $FirstMonthOfCurrentRevision->month_year_numerical;
            $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;

            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selected_revision, $dateFrom, $dateTo);
            $this->global['pageTitle'] = 'DWH : Production Amount';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("reports/prod_amount_rev_viewing", $this->global, $data, NULL);
        }
    }




        function prod_viewing()
    {
        $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi']))
            $selected_revision = $p['revi'];

        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selected_revision;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $dateFrom = $dateTo = "";
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selected_revision);
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selected_revision);
            $dateFrom = $FirstMonthOfCurrentRevision->month_year_numerical;
            $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;

            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selected_revision, $dateFrom, $dateTo);
            $this->global['pageTitle'] = 'DWH : Production Amount';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("reports/prod_amount", $this->global, $data, NULL);
        }
    }


    /* -----------------------------------------------------------------------------------------------------
						Sales Amount *** 
	-----------------------------------------------------------------------------------------------------*/



    public function sales_amount()
    {
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            // NOTE: In editing mode, we will display all data from the current revision

            $dateFrom = $dateTo = "";
            $FirstMonthOfCurrentRevision = $this->tm->getFirstMonthOfCurrentRevision($selectedDPRNO);
            $LastMonthOfCurrentRevision = $this->tm->getLastMonthOfCurrentRevision($selectedDPRNO);
            $dateFrom = $FirstMonthOfCurrentRevision->month_year_numerical;
            $dateTo = $LastMonthOfCurrentRevision->month_year_numerical;
            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);

            $this->global['pageTitle'] = 'DWH :  Sale Amount';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("reports/sales_amount", $this->global, $data, NULL);
        }
    }

    public function sales_amount_rev_view()
    {
        $selectedDPRNO = $_POST['revi'];
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

            $this->global['pageTitle'] = 'DWH : Sale Amount ';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("reports/sales_amount", $this->global, $data, NULL);
        }
    }

    public function End_invty_comparison()
    {
        $pd = $_POST;
        $revFrom = $revTo = "";
        if (count($pd) > 0) {
            $revFrom = $pd['txt_revision_from'];
            $revTo = $pd['txt_revision_to'];
        }

        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $this->global['pageTitle'] = 'DWH : CFO';
            $dateFrom = date("Y-01-01");
            $dateTo = date("Y-m-31", strtotime("+7 month"));

            $data['revFrom'] = $revFrom;
            $data['revTo'] = $revTo;

            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY customers_id ASC")->result();
            $this->loadViews("reports/end_invty_comparison", $this->global, $data, NULL);
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
        redirect('index.php/task/ncfl_plan_report_actual');
    }

    /* API */
    function GetCurrentPSIRevision(){
        $currentRevision = $this->tm->get_dpr_no();

        $getdata   = array('revision_date' => $currentRevision);

        echo json_encode($getdata);
    } 
    /* -----------------------------------------------------------------------------------------------------
						UPLOADING ACTUAL DATA *** 
	-----------------------------------------------------------------------------------------------------*/



    /* 
    CAUTION: DO NOT USE UNLESS AUTHORIZED
    THIS FUNCTION CREATES A RANDOM CODE FOR THE MODELS in tbl_models
     function CreateRandomModelsCode()
    {
        $models = $this->db->query('SELECT * FROM tbl_models')->result();
        foreach ($models as $model) {
            print("<pre>");
            print_r($model);
            print("</pre>");

            $models_code = 'DWH'.substr($model->models_desc,0,1) . mt_rand(1000, 1000000);
            $this->db->query("UPDATE tbl_models SET models_code='$models_code' WHERE models_id='$model->models_id' ");
        }
        echo count($models);
    }
    */
}
