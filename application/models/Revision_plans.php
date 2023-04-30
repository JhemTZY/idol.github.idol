<!--PANG REVISION TALAGO -->


<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Revision_plans extends CI_Model
{

    public function __construct(){
        parent::__construct();
       
		$this->load->library("ps_query");
    }

    function taskListingCount($searchText = '')
    {
        $this->db->select('pr.plans_revisions_id, pr.plans_id,  pr.customers, pr.models_details_id, pr.number_of_months, pr.revisions_number,  pr.status, pr.createdDtm');
        $this->db->from('tbl_plans_revisions as pr');
        if(!empty($searchText)) {
            $likeCriteria = "(pr.customers LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('pr.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }



	


	function planninglistingCount($searchText = '')
	{
	$this->db->select('pr.ID,  pr.customers,  pr.models, pr.revision, pr.price,  pr.prod_qty, pr.prod_amount, pr.sales_qty, pr.sales_amount, pr.invty_end, pr.invty_end_amount');
	$this->db->from('planning as pr');
	if(!empty($searchText)) {
		$likeCriteria = "(pr.customers LIKE '%".$searchText."%')";
		$this->db->where($likeCriteria);
	}
	$this->db->where('pr.isDeleted', 0);
	$query = $this->db->get();
	
	$result = $query->result();        
	return $result;
}
    
    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */




    // function taskListing($searchText = '', $page, $segment)
    // {
    //     $this->db->select('pr.plans_revisions_id, pr.plans_id, pr.models_details_id, pr.number_of_months, pr.revisions_number,  pr.status, pr.createdDtm');
    //     $this->db->from('tbl_plans_revisions as pr');
    //     if(!empty($searchText)) {
    //         $likeCriteria = "(pr.plans_id LIKE '%".$searchText."%')";
    //         $this->db->where($likeCriteria);
    //     }
    //     $this->db->where('pr.isDeleted', 0);
    //     $this->db->order_by('pr.plans_revisions_id', 'DESC');
    //     $this->db->limit($page, $segment);
    //     $query = $this->db->get();
        
    //     $result = $query->result();        
    //     return $result;
    // }



	function taskListing2($searchText = '', $page, $segment)
	{
		
		$query = $this->db->query("select * from tbl_planning_list_1");
		$query = $this-> db->get('tbl_planning_list_1');
		
		$result = $query->result();        
		return $result;
	}



    function Updates()
    {

		$query = $this->db->query("SELECT * FROM tbl_planning_list_1 ORDER BY id");
		return $query->result(); 
		
        // $this->db->select('tbl_plans_revisions.plans_revisions_id, tbl_plans_revisions.models_details_id, tbl_plans_revisions.customers, tbl_planning_list_1.price1, tbl_planning_list_1.prod_qty1, tbl_planning_list_1.sales_qty1, tbl_planning_list_1.prod_amount1, tbl_planning_list_1.sales_amount1, tbl_planning_list_1.invty_end1, tbl_planning_list_1.invty_end_amount1,tbl_planning_list_1.price2, tbl_planning_list_1.prod_qty2, tbl_planning_list_1.sales_qty2, tbl_planning_list_1.prod_amount2, tbl_planning_list_1.sales_amount2, tbl_planning_list_1.invty_end2, tbl_planning_list_1.invty_end_amount2,tbl_planning_list_1.price3, tbl_planning_list_1.prod_qty3, tbl_planning_list_1.sales_qty3, tbl_planning_list_1.prod_amount3, tbl_planning_list_1.sales_amount3, tbl_planning_list_1.invty_end3, tbl_planning_list_1.invty_end_amount3,tbl_planning_list_1.price4, tbl_planning_list_1.prod_qty4, tbl_planning_list_1.sales_qty4, tbl_planning_list_1.prod_amount4, tbl_planning_list_1.sales_amount4, tbl_planning_list_1.invty_end4, tbl_planning_list_1.invty_end_amount4,tbl_planning_list_1.price5, tbl_planning_list_1.prod_qty5, tbl_planning_list_1.sales_qty5, tbl_planning_list_1.prod_amount5, tbl_planning_list_1.sales_amount5, tbl_planning_list_1.invty_end5, tbl_planning_list_1.invty_end_amount5,tbl_planning_list_1.price6, tbl_planning_list_1.prod_qty6, tbl_planning_list_1.sales_qty6, tbl_planning_list_1.prod_amount6, tbl_planning_list_1.sales_amount6, tbl_planning_list_1.invty_end6, tbl_planning_list_1.invty_end_amount6,tbl_planning_list_1.price7, tbl_planning_list_1.prod_qty7, tbl_planning_list_1.sales_qty7, tbl_planning_list_1.prod_amount7, tbl_planning_list_1.sales_amount7, tbl_planning_list_1.invty_end7, tbl_planning_list_1.invty_end_amount7,tbl_planning_list_1.price8, tbl_planning_list_1.prod_qty8, tbl_planning_list_1.sales_qty8, tbl_planning_list_1.prod_amount8, tbl_planning_list_1.sales_amount8, tbl_planning_list_1.invty_end8, tbl_planning_list_1.invty_end_amount8,tbl_planning_list_1.price9, tbl_planning_list_1.prod_qty9, tbl_planning_list_1.sales_qty9, tbl_planning_list_1.prod_amount9, tbl_planning_list_1.sales_amount9, tbl_planning_list_1.invty_end9, tbl_planning_list_1.invty_end_amount9,tbl_planning_list_1.price10, tbl_planning_list_1.prod_qty10, tbl_planning_list_1.sales_qty10, tbl_planning_list_1.prod_amount10, tbl_planning_list_1.sales_amount10, tbl_planning_list_1.invty_end10, tbl_planning_list_1.invty_end_amount10');
        // $this->db->from('tbl_plans_revisions');
		// $this->db->join('tbl_planning_list_1', 'tbl_plans_revisions.plans_revisions_id = tbl_planning_list_1.id'); 
        // $query = $this->db->get();
        
        // $result = $query->result();        
        // return $result;
    }




	

    
    /**
     * This function is used to add new task to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewTask($taskInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_plans', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }


    

    function addNewTask2($taskInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_plans_revisions', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }



    function addNewTask3($taskInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_customer', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }



	function addNewPrice($taskInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ncf_price', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }



	function planning($taskInfo)
    {
	
		
        $this->db->trans_start();
        $this->db->insert('tbl_planning_list_1', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }


    function getTaskInfo($taskId)
    {
        $this->db->select('plans_revisions_id, plans_id, customers ,models_details_id , number_of_months ');
        $this->db->from('tbl_plans_revisions');
        $this->db->where('plans_revisions_id', $taskId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    function getUserRoles()
    {
        $query = $this->db->get('tbl_customer');
        return $query;	
    }


	function get_NCF_price()
    {
        $query = $this->db->get('ncf_price');
        return $query;	
    }

 

  
    function getUserRoles2()
    {
        $query = $this->db->get('tbl_models');
        return $query;	
    }

    /**
     * This function is used to update the task information
     * @param array $taskInfo : This is task updated information
     * @param number $taskId : This is task id
     */
    function editTask($taskInfo, $taskId)
    {
        $this->db->where('plans_revisions_id', $taskId);
        $this->db->update('tbl_plans_revisions', $taskInfo);
        
        return TRUE;
    }

 
	function get_model_customer($model){
		$query = $this->db->get_where('tbl_models', array('customer_models_id' =>$model));
		return $query;
	}




    // rev ++
	function get_dpr_no() {
		$Dates=strftime('%m');
		
		$servername = "10.216.128.10";
        $username = "root";
        $password = "";
        $dbname = "cias";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
    ?>
 
  
 <?php



        $query2 = "select * from revision order by ID desc limit 1";
        $result2 = mysqli_query($conn,$query2);
        $row = mysqli_fetch_array($result2);
        $last_id = $row['ID'];
        if ($last_id == "")
        {
			$selectedDPRNO = "REV-$Dates-1";
        }
        else
        {
        $rest = substr("$last_id", -3);  
		$insert_id = "$rest" + 1;
        $ars = sprintf("%03d", $insert_id);
		
		$emp_id = 	$selectedDPRNO ='REV-'."$Dates".'-'.$ars;
        }

		 return $selectedDPRNO;
	}



    // plans rev

	public function get_active_plans_revisions_list($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlQuery = $this->ps_query->plans_revisions($fields, $condition, $group, $order, $limit);
		//echo $sqlQuery."<br><br>";
		
		$sql = $this->db->query($sqlQuery);
		if($sql){
			$result = $sql->result();
		}
		else{
			$error = $this->db->error();
			echo $error["message"];
			exit;
		}
		return $result;
	}
	
	public function get_active_plans_revisions_details($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlQuery = $this->ps_query->plans_revisions($fields, $condition, $group, $order, $limit);
		
		$sql = $this->db->query($sqlQuery);
		if($sql){
			$result = $sql->row();
		}
		else{
			$error = $this->db->error();
			echo $error["message"];
			exit;
		}
		return $result;
	}
	
	public function insert_plans_revisions($data){
		$response = "";
		$insert = $this->db->insert('tbl_plans_revisions', $data);
		if($this->db->affected_rows() == 1){
			$response = $this->db->insert_id();
		}
		else{
			$error = $this->db->error();
			$response = $error["message"];
		}
		return $response;
	}
	
	public function insert_batch_plans_revisions($data){
		$response = "";
		$insert = $this->db->insert_batch("tbl_plans_revisions" , $data);
		if($insert){
			$response = "1";
		}
		else{
			$error = $this->db->error();
			$response = $error["message"];
		}
		return $response;
	}
	
	public function update_plans_revisions($data, $condition){
		$response = "";
		$this->db->where($condition);
		$update = $this->db->update("tbl_plans_revisions", $data);
		if($update){
			$response = 1;
		}
		else{
			$response = $update;
		}
		return $response;
	}
	
	public function delete_plans_revisions($condition, $remarks, $userId){
		$response = "";
		$data = array(
			"status" => "deleted", 
			"date_deleted" => date("Y-m-d H:i:s"), 
			"deleted_by" => $userId, 
			"remarks" => $remarks
		);
		$this->db->where($condition);
		$update = $this->db->update("tbl_plans_revisions", $data);
		if($update){
			$response = 1;
		}
		else{
			$response = $update;
		}
		return $response;
	}
	
	public function get_active_pending_plans($usersId){
		$plansRevisionsCondition = "pr.revisions_status = 'pending' 
									and pr.added_by = '".$usersId."'";
		$plansRevisionsList = $this->get_active_plans_revisions_list("", $plansRevisionsCondition);
		
		return $plansRevisionsList;
	}
	
	public function get_current_plan_month(){
		$response = array();
		$selectedMonth = date("F-Y");
		$planFields = "pl.plans_id, 
						pl.start_month, 
						pr.number_of_months, 
						date(pl.date_created) as created_date";
		$planCondition = "pl.start_month = '".$selectedMonth."'";
		$planDetails = $this->get_active_plans_revisions_details($planFields, $planCondition);
		if(!empty($planDetails)){
			$numberOfMonth = $planDetails->number_of_months;
			$createdDate = $planDetails->created_date;
			$startDate = date($createdDate);
			$monthsList = $this->generate->months($startDate, $numberOfMonth, "F_Y");
			
			$response = $monthsList;
		}

		return $response;
	}
	
	public function get_current_saved_models(){
		$response = "";
		$selectedMonth = date("F-Y");
		$planFields = "group_concat(models_details.models_details_id) as models_id_list";
		$planCondition = "pl.start_month = '".$selectedMonth."'";
		$planDetails = $this->get_active_plans_revisions_details($planFields, $planCondition);
		$modelsIdList = isset($planDetails->models_id_list) ? $planDetails->models_id_list : "";
		
		$response = $modelsIdList;
		
		return $response;
	}


// plans 


    
	public function get_active_plans_list($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlQuery = $this->ps_query->plans_raw($fields, $condition, $group, $order, $limit);
		//echo $sqlQuery."<br><br>";
		
		$sql = $this->db->query($sqlQuery);
		if($sql){
			$result = $sql->result();
		}
		else{
			$error = $this->db->error();
			echo $error["message"];
			exit;
		}
		return $result;
	}
	
	public function get_active_plans_details($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlQuery = $this->ps_query->plans_raw($fields, $condition, $group, $order, $limit);
		
		$sql = $this->db->query($sqlQuery);
		if($sql){
			$result = $sql->row();
		}
		else{
			$error = $this->db->error();
			echo $error["message"];
			exit;
		}
		return $result;
	}
	
	public function insert_plans($data){
		$response = "";
		$insert = $this->db->insert('tbl_plans', $data);
		if($this->db->affected_rows() == 1){
			$response = $this->db->insert_id();
		}
		else{
			$error = $this->db->error();
			$response = $error["message"];
		}
		return $response;
	}
	
	public function insert_batch_plans($data){
		$response = "";
		$insert = $this->db->insert_batch("tbl_plans" , $data);
		if($insert){
			$response = "1";
		}
		else{
			$error = $this->db->error();
			$response = $error["message"];
		}
		return $response;
	}
	
	public function update_plans($data, $condition){
		$response = "";
		$this->db->where($condition);
		$update = $this->db->update("tbl_plans", $data);
		if($update){
			$response = 1;
		}
		else{
			$response = $update;
		}
		return $response;
	}
	
	public function delete_plans($condition, $remarks, $userId){
		$response = "";
		$data = array(
			"status" => "deleted", 
			"date_deleted" => date("Y-m-d H:i:s"), 
			"deleted_by" => $userId, 
			"remarks" => $remarks
		);
		$this->db->where($condition);
		$update = $this->db->update("tbl_plans", $data);
		if($update){
			$response = 1;
		}
		else{
			$response = $update;
		}
		return $response;
	}
	
	public function get_current_plan_id(){
		$response = array();
		$alert = "";
		$message = "";
		$selectedMonth = date("F-Y");
		$planCondition = "start_month = '".$selectedMonth."'";
		$planDetails = $this->get_active_plans_details("", $planCondition);
		$plansId = isset($planDetails->plans_id) ? $planDetails->plans_id : "";
		
		if($plansId == ""){
			$plansData = array(
				"start_month" => $selectedMonth, 
				"date_created" => date("Y-m-d"), 
				"date_added" => date("Y-m-d H:i:s"), 
                "added_by" => ID
			
			);
			$insertPlan = $this->insert_plans($plansData);
			if(!is_numeric($insertPlan)){
				$alert = "danger";
				$message = "Error in inserting new plan: ".$insertPlan;
			}
			
		}
		
		$response = array(
			"alert" => $alert, 
			"message" => $message, 
			"plans_id" => $plansId
		);
		
		return $response;
	}


public function Create_table_if_not_exist(){
$tableName = "tbl_planning_list_1";
$selectedMonth = date("F-Y");



$sql = "CREATE TABLE IF NOT EXISTS `cias`.`$tableName`(
	id int(11) AUTO_INCREMENT,
	Customer varchar(255),
	Model varchar(255),
	Revision varchar(255),
	id_interversion varchar(255),
	isDeleted int,
	PRIMARY KEY  (id)
	)";

$result = $this->db->query($sql);

		}



}



