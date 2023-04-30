<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class PSI_MODEL extends Ci_model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');

    }

    function GetCustomers()
    {
        $data = $this->db->query(
            "SELECT * FROM tbl_customer 
              WHERE isActive='Active'
            ORDER BY sorting_code ASC"
        )->result();
        return $data;
    }
    function GetModels($customerID)
    {
        $data = $this->db->query(
            " 
            SELECT * FROM cias.tbl_models 
           
            INNER JOIN cias.tbl_model_category
            ON tbl_models.model_category_id=tbl_model_category.cat_id
            WHERE customer_models_id='$customerID'
            AND status='Active' 
             GROUP BY models_code 
            ORDER BY sorting_code ASC "
        )->result();
        return $data;
    }
    function GetMonths($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days , isActual, tbl_new_plan.isHidden FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$revision'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }



 /* function GET_BEGIN()
    {
        $data = $this->db->query(
            "SELECT * FROM cias.tbl_new_plan  WHERE  revision = 'REV-11-4-22' ORDER BY month_year_numerical DESC limit 1  "

                                    
        )->result();
        return $data;
    }
*/


    function GetPSIData($modelID, $revision, $month)
    {
        $data = $this->db->query(
            "SELECT models_code , models_id,auto_id,price,models_desc,prod_qty,sales_qty,model, inventory,isProdEdit,isSalesEdit,category,month_year_numerical,
            (prod_qty-sales_qty) AS auto_inventory,
            -- (inventory+prod_qty-sales_qty) AS BEGIN_INVTY,
            SUM(sales_qty) AS mergedSalesQty,
            SUM(prod_qty) AS mergedProdQty,
            SUM(prod_qty-sales_qty) AS mergedInventory
            FROM tbl_new_plan 
            INNER JOIN tbl_models
            ON tbl_new_plan.model = tbl_models.models_id
            WHERE models_code='$modelID'
            AND revision='$revision'
            AND month_year='$month'
            group by models_code"
        )->row();
        return $data;
    }

    function GetCurrentRevisionData($selectedDPRNO)
    {
          $data = $this->db->query("SELECT status FROM tbl_revision WHERE revision_date='$selectedDPRNO'")->row();
        return $data;
    }


    function UserLog($user_id, $role_text, $name, $action_made)
    {
        $pagelink = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $ipadd = $_SERVER['REMOTE_ADDR'];
        $method = $_SERVER['REQUEST_METHOD'];
        $date_created = date("Y-m-j");
        $time_created = date("H:i:s");

        $data = array(
            "user_id"=>$user_id, 
            "name"=>$name, 
            "role" => $role_text, 
            "method"=>$method,
            "action_made"=> $action_made,
            "ip_add"=>$ipadd,
            "page_link"=>$pagelink,
            "time_created"=>$time_created,
            "date_created"=>$date_created
        );
        $this->db->insert('tbl_isedit_logs', $data);
    }


     function MODEL($customerID)
    {
        $data = $this->db->query(
            " 
            SELECT * FROM cias.tbl_models 
            INNER JOIN cias.tbl_model_category
            ON tbl_models.model_category_id=tbl_model_category.cat_id
            WHERE customer_models_id='$customerID'
            AND status='Active' 
            ORDER BY sorting_code ASC "
        )->result();
        return $data;
    }
    function MONTHS($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days , isActual, tbl_new_plan.isHidden FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$revision'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }



 /* function GET_BEGIN()
    {
        $data = $this->db->query(
            "SELECT * FROM cias.tbl_new_plan  WHERE  revision = 'REV-11-4-22' ORDER BY month_year_numerical DESC limit 1  "

                                    
        )->result();
        return $data;
    }
*/


    function PSIDATA($modelID, $revision, $month)
    {
        $data = $this->db->query(
            "SELECT models_code , models_id,auto_id,price,models_desc,prod_qty,sales_qty,model, inventory,isProdEdit,isSalesEdit,category,isDuplicate ,month_year_numerical,
            (prod_qty-sales_qty) AS auto_inventory,
            -- (inventory+prod_qty-sales_qty) AS BEGIN_INVTY,
            SUM(sales_qty) AS mergedSalesQty,
            SUM(prod_qty) AS mergedProdQty,
            SUM(prod_qty-sales_qty) AS mergedInventory
            FROM tbl_new_plan 
            INNER JOIN tbl_models
            ON tbl_new_plan.model = tbl_models.models_id
            WHERE model='$modelID'
            AND revision='$revision'
            AND month_year='$month'
            group by models_code"
        )->row();
        return $data;
    }

  

    function SALESBREAKDOWN($modelID, $revision, $month)
    {
       $data = $this->db->query(
            "SELECT models_code , models_id,auto_id,price,models_desc,prod_qty,sales_qty,model, inventory,isProdEdit,isSalesEdit,category,isDuplicate ,month_year_numerical,
         prod_qty- sum(sales_qty) AS auto_inventory,
            -- (inventory+prod_qty-sales_qty) AS BEGIN_INVTY,
            SUM(sales_qty) AS mergedSalesQty,
            SUM(prod_qty) AS mergedProdQty,
            SUM(prod_qty-sales_qty) AS mergedInventory
            FROM tbl_new_plan 
            INNER JOIN tbl_models
            ON tbl_new_plan.model = tbl_models.models_id
            WHERE models_code='$modelID'
            AND revision='$revision'
            AND month_year='$month'
            group by models_code"
        )->row();
        return $data;
        return $data;
    }





}
