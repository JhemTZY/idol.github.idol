<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Material_cost_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library("ps_query");
	}

	

	/*----------------------------------------------------------------------------------------------
				***GET CURRENT REVISION FROM tbl_revision
	-----------------------------------------------------------------------------------------------*/
	function get_dpr_no()
	{

		$selectedDPRNO = "";
		$query = $this->db->query('SELECT * FROM tbl_revision WHERE isActive="1" ');
		foreach ($query->result() as $row) {
			$selectedDPRNO = $row->revision_date;
		}
		return $selectedDPRNO;
		// return $selectedDPRNO;
	}
	/*----------------------------------------------------------------------------------------------
				GET CURRENT REVISION FROM tbl_revision***
	-----------------------------------------------------------------------------------------------*/

	public function get_active_plans_revisions_list($fields = null, $condition = null, $group = null, $order = null, $limit = null)
	{
		$sqlQuery = $this->ps_query->plans_revisions($fields, $condition, $group, $order, $limit);
		//echo $sqlQuery."<br><br>";

		$sql = $this->db->query($sqlQuery);
		if ($sql) {
			$result = $sql->result();
		} else {
			$error = $this->db->error();
			echo $error["message"];
			exit;
		}
		return $result;
	}

	//REVISION VIEWING
	function showTables()
	{
		$query = $this->db->query("SELECT DISTINCT customer, model , revision , month_year, sales_qty , prod_qty , price FROM cias.tbl_new_plan ORDER BY auto_id");
		return $query->result();
	}

	function get_revision()
	{

		$query = $this->db->query("SELECT DISTINCT revision from cias.tbl_new_plan");
		return $query->result();
	}


	//REVISION VIEWING
	function get_dates_from_current_revision($revision, $dateFrom, $dateTo)
	{
		$query = $this->db->query("SELECT DISTINCT month_year,month_year_numerical FROM cias.tbl_new_plan WHERE revision='$revision' AND month_year_numerical BETWEEN '$dateFrom' AND '$dateTo' ORDER BY month_year_numerical ASC");
		return $query->result();
	}

	function get_models($revision)
	{
		$query = $this->db->query("SELECT DISTINCT customer,model,revision,price FROM cias.tbl_new_plan WHERE revision='$revision' ORDER BY customer ASC");
		return $query->result();
	}

	/* -----------------------------------------------------------------------------------------------------
										*** PSI | BREAKDOWN
	-----------------------------------------------------------------------------------------------------*/
	// 1. First Loop - Get customers to get products/models.
	function get_customers($current_rev, $dateFrom, $dateTo)
	{
		$query = $this->db->query("SELECT DISTINCT customer FROM tbl_new_plan WHERE revision='$current_rev' AND month_year_numerical BETWEEN '$dateFrom' AND '$dateTo' ORDER BY customer ASC");
		return $query->result();
	}
	// 2. Second Loop - Get products from their designated customers
	function get_products($customer, $revision, $dateFrom, $dateTo)
	{
		$query = $this->db->query("SELECT  model AS products FROM tbl_new_plan WHERE customer = '$customer' AND revision='$revision' AND month_year_numerical BETWEEN '$dateFrom' AND '$dateTo' ORDER BY month_year_numerical,model ASC");
		return $query->result();
	}
	// 3. Third Loop - Get production and sales quantity of every product/models from each month.
	function get_products_data($customer, $model, $revision, $dateFrom, $dateTo)
	{
		$query = $this->db->query("SELECT * FROM tbl_new_plan WHERE customer='$customer' AND model='$model' AND revision='$revision'  AND month_year_numerical BETWEEN '$dateFrom' AND '$dateTo' ORDER BY month_year_numerical ASC");
		return $query->result();
	}
	/* -----------------------------------------------------------------------------------------------------
										PSI | BREAKDOWN***
	-----------------------------------------------------------------------------------------------------*/

	/* -----------------------------------------------------------------------------------------------------
					***UPDATING OF PRICE, PRODUCTION AND SALES QUANTITY OF MODELS
	-----------------------------------------------------------------------------------------------------*/
	function get_models_by_id($id)
	{
		$query = $this->db->query("SELECT * FROM tbl_new_plan INNER JOIN tbl_models ON tbl_models.models_id=tbl_new_plan.model WHERE auto_id='$id' ");
		return $query->result();
	}
	function update_model_prod_qty($new_prod_qty, $id)
	{
		$query = $this->db->query("UPDATE  tbl_new_plan SET prod_qty='$new_prod_qty' WHERE auto_id='$id' ");
	}
	function update_model_sales_qty($new_sales_qty, $id)
	{
		$query = $this->db->query("UPDATE  tbl_new_plan SET sales_qty='$new_sales_qty' WHERE auto_id='$id' ");
	}
	function update_model_monthly_price($new_monthly_price, $id)
	{
		$query = $this->db->query("UPDATE  tbl_new_plan SET price='$new_monthly_price' WHERE auto_id='$id' ");
	}
	/* -----------------------------------------------------------------------------------------------------
					UPDATING OF PRICE, PRODUCTION AND SALES QUANTITY OF MODELS***
	-----------------------------------------------------------------------------------------------------*/


	function get_new_plan()
	{
		$query = $this->db->get('tbl_new_plan');
		return $query;
	}


	public function exportList()
	{
		$this->db->select(array('auto_id', 'model', 'month_year', 'revision', 'sales_qty', 'prod_qty', 'price'));
		$this->db->from('cias.tbl_new_plan');
		$query = $this->db->get();
		return $query->result();
	}

	function month_year_range($current_rev, $dateFrom, $dateTo)
	{
		$query = $this->db->query("SELECT DISTINCT customer FROM tbl_new_plan WHERE month_year='$current_rev' AND month_year_numerical BETWEEN '$dateFrom' AND '$dateTo' ORDER BY customer ASC");
		return $query->result();
	}

	// FIRST MONTH OF THE CURRENT REVISION
	function getFirstMonthOfCurrentRevision($selectedDPRNO)
	{
		$data = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$selectedDPRNO' ORDER BY month_year_numerical ASC LIMIT 1")->row();
		return $data;
	}

	// LAST MONTH OF THE CURRENT REVISION
	function getLastMonthOfCurrentRevision($selectedDPRNO)
	{
		$data = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$selectedDPRNO' ORDER BY month_year_numerical DESC LIMIT 1")->row();
		return $data;
	}

	/*-------------------------------------------------------------------------------------------------------
									***	PSI | MERGED
	-------------------------------------------------------------------------------------------------------*/

	function get_distinct_models()
	{
		$data = $this->db->query(
			"SELECT DISTINCT(models_code),customers_id,customers_code,customers_desc,models_desc
			FROM tbl_models 
			INNER JOIN tbl_customer 
			ON tbl_models.customer_models_id=tbl_customer.customers_id
			ORDER BY tbl_customer.sorting_code ASC, tbl_models.sorting_code"
		)->result();
		return $data;
	}

	function get_table_customer()
	{
		$data = $this->db->query(
			"SELECT * FROM tbl_customer ORDER BY sorting_code"
		)->result();
		return $data;
	}

	function get_models_merged_data($customers_id,$month_year,$models_code,$revision)
	{
		$data = $this->db->query(
			"SELECT 
			customers_id,
			customers_code,
			customers_desc,
			models_code,
			models_desc ,
			month_year,
			item_code,
			SUM(sales_qty) AS mergedSalesQty,
			SUM(prod_qty) AS mergedProdQty,
			SUM(prod_qty-sales_qty) AS mergedInventory
			FROM tbl_new_plan 
			INNER JOIN tbl_customer
			ON tbl_new_plan.customer=tbl_customer.customers_id
			INNER JOIN tbl_models
			ON tbl_new_plan.model=tbl_models.models_id
			WHERE tbl_customer.customers_id = '$customers_id'
			AND month_year='$month_year'
			AND models_code='$models_code'
			AND revision='$revision'
			ORDER BY tbl_models.sorting_code ASC
			"
		)->row();
		return $data;
	}


	/*-------------------------------------------------------------------------------------------------------
									PSI | MERGED ***
	-------------------------------------------------------------------------------------------------------*/
}
