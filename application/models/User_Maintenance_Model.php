<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : UserMaintenance_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class User_Maintenance_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("ps_query");
    }

    public function add_new_model($model_name, $customer_id, $model_price,$item_code, $category, $models_code)
    {
        $model_data = array(
            "models_id" => NULL,
            "models_desc" => $model_name,
            "customer_models_id" => $customer_id,
            "active_price" => $model_price,
             "item_code" => $item_code,
             "model_category_id" => $category,
            "status" => "Active",
            "models_code" => $models_code,
        );
        $this->db->insert('tbl_models', $model_data);
    }



     public function add_prod_new_model($model_name, $customer_id, $model_price,$isbreakdown, $item_code, $category, $models_code)
    {
        $model_data = array(
            "models_id" => NULL,
            "models_desc" => $model_name,
            "customer_models_id" => $customer_id,
            "active_price" => $model_price,
            "is_breakdown" => $isbreakdown,
             "item_code" => $item_code,
             "model_category_id" => $category,
            "status" => "Active",
            "models_code" => $models_code,
        );
        $this->db->insert('tbl_models_proda', $model_data);
    }


    public function add_new_customer($customer_code, $customer_name)
    {
        $customer_data = array(
            "customers_id" => NULL,
            "customers_code" => $customer_code,
            "customers_desc" => $customer_name,

        );
        $this->db->insert('tbl_customer', $customer_data);
    }

    public function get_psi_approvers()
    {
        $approvers = $this->db->query(
            "SELECT auto_id, tbl_approver.approver_id,tbl_approver.email,header,name,role,hierarchy_order,isActive
        FROM tbl_approver 
        INNER JOIN tbl_users 
        ON tbl_approver.approver_id=tbl_users.userId 
        INNER JOIN tbl_roles
        ON tbl_users.roleId=tbl_roles.roleId
        WHERE documentType='PSI'
        ORDER BY hierarchy_order ASC
        "
        )->result();
        return $approvers;
    }

    public function getAllUsers()
    {
        $AllUsers = $this->db->query(
            "SELECT userId, name FROM tbl_users "
        )->result();
        return $AllUsers;
    }

    public function UpdateApprover($auto_id,$apprvrID, $email, $header, $hierarchy, $status)
    {
        $data = array(
            'approver_id' => $apprvrID,
            'email' => $email,
            'header' => $header,
            'hierarchy_order' => $hierarchy,
            'isActive' => $status,
        );
        $this->db->where('auto_id',$auto_id);
        $this->db->update('tbl_approver', $data);
    }



    /* MODELS EDIT MODE*/
     function GetCustomers()
    {
        $data = $this->db->query(
            "SELECT * FROM tbl_customer ORDER BY sorting_code"
        )->result();
        return $data;
    }

    function GetModelCategories()
    {
        $data = $this->db->query(
            "SELECT * FROM tbl_model_category"
        )->result();
        return $data;
    }

    function GetMonths($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days FROM tbl_new_plan WHERE revision='$revision' ORDER BY month_year_numerical ASC"
        )->result();
        return $data;
    }
    function GetModels($customer, $model_category_id)
    {
        $data = $this->db->query(
            "SELECT * FROM tbl_models WHERE customer_models_id='$customer' AND model_category_id = '$model_category_id' ORDER BY sorting_code"
        )->row();
        return $data;
    }


    function GetPSIData($customer, $modelCatID, $revision, $month)
    {
        $data = $this->db->query(
            "SELECT model_category_id,models_desc,prod_qty, sales_qty,
            (prod_qty-sales_qty) AS inventory
            FROM tbl_new_plan 
            INNER JOIN tbl_models
            ON tbl_new_plan.model = tbl_models.models_id
            WHERE customer='$customer'
            AND model_category_id='$modelCatID'
            AND revision='$revision'
            AND month_year='$month'
            ORDER BY tbl_models.sorting_code"
        )->result();
        return $data;
    }

}
