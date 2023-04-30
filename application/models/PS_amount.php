<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class PS_amount extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
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
            ORDER BY sorting_code  ASC "
        )->result();
        return $data;
    }


   

    function GetMonths($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days , isActual , tbl_new_plan.isHidden  FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$revision'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }

     function GetActualMonths($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days , isActual ,tbl_new_plan.isHidden  FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE isActual = 'ACTUAL' AND revision='$revision'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }


      function GET_beginning_inv($begin)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), SUM(prod_qty-sales_qty) as auto_inventory , inventory , month_year_numerical , isActual , tbl_new_plan.isHidden FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE month_year='$begin' AND isActual = 'ACTUAL'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }






    function GetPSIData($modelID, $revision, $month)
    {
        $data = $this->db->query(
            "SELECT models_id,auto_id,price,models_desc,prod_qty,sales_qty, inventory,isProdEdit,isSalesEdit,category,
            (prod_qty-sales_qty) AS auto_inventory,
            (prod_qty-sales_qty) AS beginning_inv,
            (price*prod_qty)AS prod_amount,
            (price*sales_qty)AS sales_amount,
            (price*prod_qty-sales_qty) AS Inventory_Amount,
            (price*prod_qty) TOTAL_PROD_AMOUNT,
            (price*sales_qty) TOTAL_SALES_AMOUNT,
            SUM(sales_qty) AS mergedSalesQty,
            SUM(prod_qty) AS mergedProdQty,
            SUM(prod_qty-sales_qty) AS mergedInventory
            FROM cias.tbl_new_plan 
            INNER JOIN cias.tbl_models
            ON tbl_new_plan.model = tbl_models.models_id
            WHERE model='$modelID'
            AND revision='$revision'
            AND month_year='$month'"
        )->row();
        return $data;
    }

    function GetCurrentRevisionData($selectedDPRNO)
    {
          $data = $this->db->query("SELECT status FROM tbl_revision WHERE revision_date='$selectedDPRNO'")->row();
        return $data;
    }

/*FUCKING PROD AMOUNT*/

   function GETPSCUSTOMER()
    {
        $data = $this->db->query(
            "SELECT * FROM tbl_customer 
              WHERE isActive='Active'
            ORDER BY sorting_code ASC"
        )->result();
        return $data;
    }


    function GETPSMODEL($customerID)
    {
        $data = $this->db->query(
            " 
             SELECT * FROM cias.tbl_models 
           
            INNER JOIN cias.tbl_model_category
            ON tbl_models.model_category_id=tbl_model_category.cat_id
            WHERE customer_models_id='$customerID'
            AND status='Active' 
            ORDER BY sorting_code  ASC "
        )->result();
        return $data;
    }


   

    function GETPSMONTH($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days , isActual , tbl_new_plan.isHidden  FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE revision='$revision'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }

     function GETPSACTUALMONTH($revision)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), month_year_numerical, working_days , isActual ,tbl_new_plan.isHidden  FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE isActual = 'ACTUAL' AND revision='$revision'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }


      function GETPSBEGINNINGINVTY($begin)
    {
        $data = $this->db->query(
            "SELECT DISTINCT(month_year), SUM(prod_qty-sales_qty) as auto_inventory , inventory , month_year_numerical , isActual , tbl_new_plan.isHidden FROM cias.tbl_new_plan
                                    INNER JOIN cias.tbl_models ON tbl_new_plan.model=tbl_models.models_id 
                                    INNER JOIN cias.tbl_customer ON tbl_new_plan.customer = tbl_customer.customers_id
                                    WHERE month_year='$begin' AND isActual = 'ACTUAL'
                                    ORDER BY  month_year_numerical,tbl_customer.sorting_code,tbl_models.sorting_code ASC"

                                    
        )->result();
        return $data;
    }






    function GETPSDATA($modelID, $revision, $month)
    {
        $data = $this->db->query(
            "SELECT models_id,auto_id,price,models_desc,prod_qty,sales_qty, inventory,isProdEdit,isSalesEdit,category,
            (prod_qty-sales_qty) AS auto_inventory,
            (prod_qty-sales_qty) AS beginning_inv,
            (price*prod_qty)AS prod_amount,
            (price*sales_qty)AS sales_amount,
            (price*prod_qty-sales_qty) AS Inventory_Amount,
            (price*prod_qty) TOTAL_PROD_AMOUNT,
            (price*sales_qty) TOTAL_SALES_AMOUNT,
            SUM(sales_qty) AS mergedSalesQty,
            SUM(prod_qty) AS mergedProdQty,
            SUM(prod_qty-sales_qty) AS mergedInventory
            FROM cias.tbl_new_plan 
            INNER JOIN cias.tbl_models
            ON tbl_new_plan.model = tbl_models.models_id
            WHERE model='$modelID'
            AND revision='$revision'
            AND month_year='$month'"
        )->row();
        return $data;
    }


/*END FUCKING PROD AMOUNT*/



}
