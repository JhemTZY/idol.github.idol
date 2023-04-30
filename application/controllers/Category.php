<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Category extends BaseController
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
       
    }

    function index()
    {
        echo "<code>DIRECTORY ACCESS FORBIDDEN</code>";
    }

    function Category()
    {
        $selectedDPRNO = $this->tm->get_dpr_no();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Category Maintenance ';
            $data['selectedDPRNO'] = $selectedDPRNO;
            $this->loadViews("user_maintenance/category", $this->global, $data, NULL);
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
         $selectedDPRNO = $this->tm->get_dpr_no();
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $cus = $dt->SelectedCustomers;

            $get_model = $this->db->query(
                "SELECT *
                FROM cias.tbl_models 
                INNER JOIN cias.tbl_customer 
                ON tbl_models.customer_models_id=tbl_customer.customers_id
                WHERE customers_id = '$cus' AND customer_models_id= '$cus'
                ORDER BY tbl_models.sorting_code ASC"
            )->result();

            $category = $this->db->query("SELECT * FROM cias.tbl_model_category")->result();

            $months = $this->db->query("SELECT DISTINCT(month_year), month_year_numerical FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$selectedDPRNO'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC")->result();




            $get_model_data = [];
            foreach ($get_model as $modelKey =>$model) :
                $models_desc = $model->models_desc;
                $models_id = $model->models_id;
                $customers_id = $model->customer_models_id;
                $customer = $model->customers_desc;

                foreach ($months as $month):
                    $get_maintenance_cat = $this->db->query(
            "SELECT models_id , auto_id , category , cat_code from cias.tbl_new_plan 
            INNER JOIN cias.tbl_models ON tbl_new_plan.category=tbl_models.models_id
            INNER JOIN cias.tbl_model_category ON tbl_models.models_id=tbl_model_category.cat_id
            WHERE model = '$models_id'
            AND revision='$selectedDPRNO'
            AND month_year='$month->month_year'"
        )->row();

            
             



                    $get_model_data[$customer][$models_desc][$month->month_year] = $get_maintenance_cat;
            
                    endforeach;
                    endforeach;
        

            $data = array(

                "months" => $months,
                "get_model" => $get_model,
                "category" => $category,
                "get_model_data" => $get_model_data,
            );
        }
        echo json_encode($data);
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

            if ($psi == 'category') {
                echo "Category Updated";
                $this->db->query("UPDATE tbl_new_plan SET category='$value' WHERE auto_id='$id' AND revision='$revision' ");
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

    
}
