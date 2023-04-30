<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class User_Maintenance extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('User_Maintenance_Model', 'um');
        $this->load->model('PSI_MODEL', 'psim');
        $this->load->model('Task_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Task';
    }

    public function index()
    {
        $this->load->view("user_maintenance/index");
    }

    public function home()
    {
        $selectedMonth = date("F-Y");
        $page = "ps_plan_form";
        $data["page"] = $page;
        $this->global['pageTitle'] = 'DWH : User Maintenance - Home';
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
        $data['models'] = $this->db->query("SELECT DISTINCT models_code FROM tbl_models")->result();
        $this->loadViews("user_maintenance/home", $this->global, $data);
    }

    public function add_model()
    {
        $selectedMonth = date("F-Y");
        $page = "ps_plan_form";
        $data["page"] = $page;
        $this->global['pageTitle'] = 'DWH : User Maintenance - Add Model';
        $data['category'] = $this->db->query("SELECT * FROM tbl_model_category")->result();
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
        $this->loadViews("user_maintenance/add_model", $this->global, $data);
    }

    function update_model_price()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Update Model Price';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("user_maintenance/update_model_price", $this->global, $data, NULL);
        }
    }



      function Update_customer_isActive()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Update Model Price';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("user_maintenance/customer_update", $this->global, $data, NULL);
        }
    }





    function approvers()
    {
        $page = "ps_plan_form";
        $data["page"] = $page;

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Update PSI Approvers';
            $this->loadViews("user_maintenance/approvers", $this->global, $data, NULL);
        }
    }

    function GetApprovers()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $data['PSI_Approvers'] = $this->um->get_psi_approvers();
            $data['all_users'] = $this->um->getAllUsers();
            echo json_encode($data);
        }
    }

    function UpdateApprover()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            print_r($dt->NewApproverData);
            $autoID = $dt->NewApproverData->autoID;
            $apprvrID = $dt->NewApproverData->userID;
            $email = $dt->NewApproverData->email;
            $header = $dt->NewApproverData->header;
            $hierarchy = $dt->NewApproverData->hierarchy;
            $status = $dt->NewApproverData->activeStatus;
            $this->um->UpdateApprover($autoID, $apprvrID, $email, $header, $hierarchy, $status);
        }
    }

    public function all_revisions()
    {
        $selectedMonth = date("F-Y");
        $page = "ps_plan_form";
        $data["page"] = $page;
        $this->global['pageTitle'] = 'DWH : User Maintenance - Home';
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
        $data['models'] = $this->db->query("SELECT * FROM tbl_models")->result();
        $this->loadViews("user_maintenance/revisions", $this->global, $data);
    }



    /* -----------------------------------------------------------------------------------------------------
						***UPDATE TBL_MODELS
	-----------------------------------------------------------------------------------------------------*/


    function update_model_active_price()
    {
        $pd = $_POST;
        if (isset($pd['model_id'])) {
            $new_price = $pd['newPrice'];
            $model_id = $pd['model_id'];
            $this->db->query("UPDATE tbl_models SET active_price='$new_price' WHERE models_id='$model_id' ");
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						UPDATE TBL_MODELS***
	-----------------------------------------------------------------------------------------------------*/

    /* -----------------------------------------------------------------------------------------------------
						*** ADD NEW MODEL - TBL MODELS
	-----------------------------------------------------------------------------------------------------*/
    public function add_new_model()
    {
        $pd = $_POST;
        if (count($pd) > 0) {
            $customerID = $pd['customerID'];
            $itemCode = $pd['itemCode'];
            $modelName = $pd['modelName'];
            $CATEGORY = $pd['CATEGORY'];
            $modelPrice = $pd['modelPrice'];

            $ModelsCode = 'DWH' . substr($modelName, 0, 1) . mt_rand(1000, 1000000);
            $allCodes = array();

            $models = $this->db->query('SELECT * FROM tbl_models')->result();
            foreach ($models as $model) {
                array_push($allCodes, $model->models_code);
            }
            if (in_array($ModelsCode, $allCodes)) {
                $ModelsCode = 'DWH' . substr($modelName, 0, 1) . mt_rand(1000, 1000000);
            }

            if (empty($modelName) || empty($modelPrice)  || !is_numeric($modelPrice) || empty($itemCode) || empty($CATEGORY)) {
                echo 0;
            } else {
                // echo $ModelsCode;
                $this->um->add_new_model(str_replace(' ', '', trim($modelName)), $customerID, $modelPrice, $itemCode, $CATEGORY, $ModelsCode);
                echo 1;
            }
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						 ADD NEW MODEL - TBL MODELS ***
	-----------------------------------------------------------------------------------------------------*/


     /* -----------------------------------------------------------------------------------------------------
                        *** ADD NEW MODEL - TBL MODELS
    -----------------------------------------------------------------------------------------------------*/
    public function prodA_add_new_model()
    {
        $pd = $_POST;
        if (count($pd) > 0) {
            $customerID = $pd['customerID'];
            $itemCode = $pd['itemCode'];
            $modelName = $pd['modelName'];
            $isbreakdown = $pd['isbreakdown'];
            $CATEGORY = $pd['CATEGORY'];
            $modelPrice = $pd['modelPrice'];

            $ModelsCode = 'DWH' . substr($modelName, 0, 1) . mt_rand(1000, 1000000);
            $allCodes = array();

            $models = $this->db->query('SELECT * FROM tbl_models_proda')->result();
            foreach ($models as $model) {
                array_push($allCodes, $model->models_code);
            }
            if (in_array($ModelsCode, $allCodes)) {
                $ModelsCode = 'DWH' . substr($modelName, 0, 1) . mt_rand(1000, 1000000);
            }

            if (empty($modelName) || empty($modelPrice)  || !is_numeric($modelPrice) || empty($itemCode) || empty($CATEGORY)) {
                echo 0;
            } else {
                // echo $ModelsCode;
                $this->um->add_prod_new_model(str_replace(' ', '', trim($modelName)), $customerID, $modelPrice, $isbreakdown, $itemCode, $CATEGORY, $ModelsCode);
                echo 1;
            }
        }
    }
    /* -----------------------------------------------------------------------------------------------------
                         ADD NEW MODEL - TBL MODELS ***
    -----------------------------------------------------------------------------------------------------*/


    /* -----------------------------------------------------------------------------------------------------
						*** ADD NEW CUSTOMER - TBL CUSTOMER
	-----------------------------------------------------------------------------------------------------*/
    public function add_new_customer()
    {
        $pd = $_POST;
        if (count($pd) > 0) {
            $customer_name = $pd['customerName'];
            $customer_code = substr($customer_name, 3);
            $customer = $this->db->query("SELECT * FROM tbl_customer WHERE customers_desc='$customer_name' ")->row();
            if (count($customer) > 0 || empty($customer_name)) {
                echo "0";
            } else {
                $this->um->add_new_customer($customer_code, $customer_name);
                echo "1";
            }
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						 ADD NEW CUSTOMER - TBL CUSTOMER ***
	-----------------------------------------------------------------------------------------------------*/
    /* -----------------------------------------------------------------------------------------------------
						*** ADD MODEL PRICE IN  HISTORY - tbl_model_price_history
	-----------------------------------------------------------------------------------------------------*/
    public function add_modelPriceHistory()
    {
        $pd = $_POST;
        if (count($pd) > 0) {
            // print_r($pd);
            $date_created = date('m-w-Y');
            $data = array(
                "auto_id" => "NULL",
                "model_id" => $pd["Model_ID"],
                "model_price" => $pd["NewPrice"],
                "date_created" => $date_created
            );
            $this->db->insert("tbl_model_price_history", $data);
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						 ADD MODEL PRICE IN  HISTORY - tbl_model_price_history ***
	-----------------------------------------------------------------------------------------------------*/

    /* -----------------------------------------------------------------------------------------------------
						*** CHANGE REVISION NAME
	-----------------------------------------------------------------------------------------------------*/

    public function change_revision_name()
    {
        $pd = $_POST;
        // print_r($pd);
        if (isset($pd['RevID']) && !empty($pd['NewRevName']) && $pd['NewRevName'] !== " ") {
            $RevID = $pd['RevID'];
            $oldRevName = $pd['OldRevName'];
            $NewRevName = $pd['NewRevName'];
            $this->db->query("UPDATE tbl_revision SET revision_date='$NewRevName' WHERE ID='$RevID' ");
            $this->db->query("UPDATE tbl_new_plan SET revision='$NewRevName' WHERE revision='$oldRevName' ");
            $this->session->set_flashdata('success', 'SUCCESSFULLY CHANGED REVISION NAME');
        } else {
            $this->session->set_flashdata('error', 'REVISION NAME IS NOT SET');
        }

        redirect('index.php/User_Maintenance/all_revisions');
    }

    /* -----------------------------------------------------------------------------------------------------
						 CHANGE REVISION NAME ***
	-----------------------------------------------------------------------------------------------------*/




    function edit_model()
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
        $page = "ps_plan_form";
        $data["page"] = $page;

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Update Model Price';
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer")->result();
            $this->loadViews("user_maintenance/edit_models_mode", $this->global, $data, NULL);
        }
    }

    function get_model_customers()
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
    function psi_model_maintenance()
    {
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $cus = $dt->SelectedCustomers;

            $get_model = $this->db->query(
                "SELECT tbl_models.sorting_code, customers_id , item_code , cat_code , tbl_models.status , tbl_models.isHidden , model_remarks , model_category_id , models_desc ,models_id , customers_desc , customer_models_id
                FROM cias.tbl_models 
                INNER JOIN cias.tbl_customer 
                ON tbl_models.customer_models_id=tbl_customer.customers_id
                INNER JOIN cias.tbl_model_category 
                ON tbl_models.model_category_id=tbl_model_category.cat_id  
                WHERE customers_id = '$cus' AND customer_models_id= '$cus'
                ORDER BY tbl_models.sorting_code ASC"
            )->result();

            $category = $this->db->query("SELECT * FROM cias.tbl_model_category")->result();

            $get_model_data = [];
            foreach ($get_model as $modelKey =>$model) :
                $models_desc = $model->models_desc;
                $models_id = $model->models_id;
                $customers_id = $model->customer_models_id;
                $customer = $model->customers_desc;
                $cating = $model->cat_code;
                foreach ($category as $cat) :
                    $model_cat = $cat->cat_code;
                    $get_model_data[$customer][$modelKey] = $model;

                endforeach;
            endforeach;

            $data = array(

                "get_model" => $get_model,
                "category" => $category,
                "get_model_data" => $get_model_data,
            );
        }
        echo json_encode($data);
    }


    function update_tbl_models()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            // print_r($dt);
            $cus = $dt->SelectedCustomers;
            $id = $dt->id;
            $value = $dt->value;
            $column = $dt->column;
            print_r($dt);
            if ($column == 'itemCode') {
                $this->db->query(" UPDATE cias.tbl_models set  item_code ='$value' where models_id = '$id'  AND customer_models_id = '$cus' ");
            }
            if ($column == 'Remarks') {
                $this->db->query(" UPDATE cias.tbl_models set  model_remarks ='$value' where models_id = '$id'  AND customer_models_id = '$cus' ");
            }
            if ($column == 'Checking') {
                echo $value;
                $this->db->query(" UPDATE cias.tbl_models set  status ='$value' where models_id = '$id'  AND customer_models_id = '$cus' ");
            }

             if ($column == 'Checkbox') {
                echo $value;
                $this->db->query(" UPDATE cias.tbl_models set  isHidden ='$value' where models_id = '$id'  AND customer_models_id = '$cus' ");
            }

            if ($column == 'Category') {
                $this->db->query(" UPDATE cias.tbl_models set  model_category_id ='$value' where models_id = '$id' ");
            }

            if ($column == 'SortCode') {
                $this->db->query(" UPDATE cias.tbl_models set  sorting_code ='$value' where models_id = '$id' ");
            }
        }
    }




    function update_isActive_status()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            print_r($dt);
            $id = $dt->id;
            $value = $dt->value;
            $column = $dt->column;
            print_r($dt);
            if ($column == 'Checkbox') {
                echo $value;
                $this->db->query(" UPDATE cias.tbl_customer set  isActive ='$value' where customers_id = '$id' ");
            }

        }
    }



    function working_days()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["selectedMonth"] = $selectedMonth;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["page"] = $page;
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
            $this->global['pageTitle'] = 'DWH : Maintenance Working Days';
            $this->loadViews("user_maintenance/working_days", $this->global, $data, NULL);
        }
    }

    function working_month()
    {

        $data = array();
        $dt = json_decode(file_get_contents("php://input"));

        if (isset($dt)) {
            $selectedDPRNO = $this->tm->get_dpr_no();
            $months = $this->tm->get_dates_from_current_revision($selectedDPRNO);



            $data = array(

                "months" => $months,

            );
        }
        echo json_encode($data);
    }


      function customer_Isactive()
    {

        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $get_customer = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $data = array(
                "get_customer" => $get_customer,
            );
            echo json_encode($data);
        }
    }



    function working_updates()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            print_r($dt);
            $selectedDPRNO = $this->tm->get_dpr_no();
            $months = $this->tm->get_dates_from_current_revision($selectedDPRNO);
            $value = $dt->value;
            $monthYear = $dt->month_year;
            $column = $dt->column;
                 $user_id =  $this->session->userdata('userId');
            $name =  $this->session->userdata('name');
            $role_text =  $this->session->userdata('roleText');

            print_r($dt);
            if ($column == 'WorkingDays') {
                $this->db->query("UPDATE tbl_new_plan SET working_days='$value' WHERE revision='$selectedDPRNO' AND month_year='$monthYear'");
             $action_made = "UPDATED WORKINGDAYS AS A {$value}.";
            }

                $this->psim->UserLog($user_id,  $role_text, $name, $action_made);
        }
    }

      function plans_or_actual()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            print_r($dt);
            $selectedDPRNO = $this->tm->get_dpr_no();
            $months = $this->tm->get_dates_from_current_revision($selectedDPRNO);
            $value = $dt->value;
            $monthYear = $dt->month_year;
            $column = $dt->column;
                 $user_id =  $this->session->userdata('userId');
            $name =  $this->session->userdata('name');
            $role_text =  $this->session->userdata('roleText');

            print_r($dt);
            if ($column == 'IsActual') {
                $this->db->query("UPDATE tbl_new_plan SET isActual='$value' WHERE revision='$selectedDPRNO' AND month_year='$monthYear'");
             $action_made = "UPDATED TYPE AS A {$value}.";
            }

                $this->psim->UserLog($user_id,  $role_text, $name, $action_made);
        }
    }

      function hidden_or_not()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            print_r($dt);
            $selectedDPRNO = $this->tm->get_dpr_no();
            $months = $this->tm->get_dates_from_current_revision($selectedDPRNO);
            $value = $dt->value;
            $monthYear = $dt->month_year;
            $column = $dt->column;
            $user_id =  $this->session->userdata('userId');
            $name =  $this->session->userdata('name');
            $role_text =  $this->session->userdata('roleText');

            print_r($dt);
            if ($column == 'IsHidden') {
                $this->db->query("UPDATE tbl_new_plan SET isHidden='$value' WHERE revision='$selectedDPRNO' AND month_year='$monthYear'");
                $action_made = "UPDATED STATUS AS A {$value}. ";
            }

                $this->psim->UserLog($user_id,  $role_text, $name, $action_made);
        }
    }

}
