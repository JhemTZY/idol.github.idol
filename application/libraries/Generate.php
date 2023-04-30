<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generate {
	public $ci;
	
	public function __construct() {
		$this->ci = get_instance();
		$this->ci->load->library("functions");
	}
	// public function sql_query($fields = null, $table, $condition = null, $group = null, $order = null, $limit = null){
	// 	$useFields = "*";
	// 	if($fields != ""){
	// 		$useFields = $fields;
	// 	}
		
	// 	$sqlCondition = $condition;
	// 	$sqlString = "select ".$useFields." from ".$table;
	// 	if($condition != "")
	// 	{
	// 		$sqlString .= " WHERE ".$sqlCondition;
	// 	}
		
	// 	if($group != "")
	// 	{
	// 		$sqlString .= " GROUP BY ".$group;
	// 	}
		
	// 	if($order != "")
	// 	{
	// 		$sqlString .= " ORDER BY ".$order;
	// 	}
		
	// 	if($limit != "")
	// 	{
	// 		$sqlString .= " LIMIT ".$limit;
	// 	}
		
	// 	return $sqlString;
	// }
	
	public function alert_message($type, $message){
		//$response = "<div class = 'alert alert-".$type."'>".$message."</div>";
		$icon = "bell";
		$textColor = "text-white";
		switch($type){
			case "primary":
				$icon = "bx-bookmark-heart";
				$textColor = "text-black";
			break;
			
			case "secondary":
				$icon = "bx-tag-alt";
				$textColor = "text-black";
			break;
			
			case "success":
				$icon = "bxs-check-circle";
				$textColor = "text-white";
			break;
			
			case "danger":
				$icon = "bxs-message-square-x";
			break;
			
			case "warning":
				$icon = "bx-info-circle";
				$textColor = "text-black";
			break;
			
			case "info":
				$icon = "bx-info-square";
				$textColor = "text-black";
			break;
			
			
		}
		$response = "<div class = 'alert alert-".$type." border-0 bg-".$type." alert-dismissible fade show py-2'>
						<div class = 'd-flex align-items-center'>
							<div class = 'font-35 '>
								<i class = 'bx ".$icon."'></i>
							</div>
							<div class = 'ms-3'>
								<h6 class = 'mb-0 ".$textColor."'>Message</h6>
								<div class= '".$textColor."'>".$message."</div>
							</div>
						</div>
						<button type = 'button' class= 'btn-close' data-bs-dismiss = 'alert' aria-label= 'Close'></button>
					</div>";
		return $response;
	}
	
	
	public function range_date($start, $end, $format, $type = "P1D", $sequence = null){
		$periodDate 	= array();
		$utc 			= new DateTimeZone('UTC');
		$periodStart 	= new DateTime($start, $utc);
		$periodEnd 		= new DateTime($end, $utc);
		$periodEnd->modify('+1 day');
		$interval 		= new DateInterval($type);
		$period   		= new DatePeriod($periodStart, $interval, $periodEnd);
		//$this->echo_array($period);
		//echo $format;
		foreach($period as $dt){
			$date = $dt->format($format);
			//echo "Date: ".$date."<bR>";
			if($sequence != ""){
				$startDate = new DateTime($date);
				//$this->echo_array($startDate);
				$startDate->modify($sequence);
				$nextDate = $startDate->format($format);
				//echo "Date To: ".$nextDate."<bR>";
				$date = $date."=".$nextDate;
				
			}
			$periodDate[] = $date;
		}
		
		return $periodDate;
	}
	
	public function ip_address(){
		$response = "";
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$response = $_SERVER['HTTP_CLIENT_IP'];
		} 
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$response = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} 
		else {
			$response = $_SERVER['REMOTE_ADDR'];
		}
		return $response;
	}
	
	public function sql_fields($systemName, $functionName){
		$response = "";
		$ci = get_instance();
		$libraryName = $systemName."_fields";
		$ci->load->library($libraryName);
		$response = $ci->{$libraryName}->{$functionName}();
		
		return $response;
	}
	
	public function sql_tables($systemName, $functionName){
		$response = "";
		$ci = get_instance();
		$libraryName = $systemName."_tables";
		$ci->load->library($libraryName);
		$response = $ci->{$libraryName}->{$functionName}();
		
		return $response;
	}
	
	public function global_variables(){
		$session = isset($_SESSION[PROJECT]) ? $_SESSION[PROJECT] : array();
		$sessionUsers = isset($session["users"]) ? $session["users"] : array();
		$userId = isset($sessionUsers["users_id"]) ? $sessionUsers["users_id"] : "";
		$userEmployeeNumber = isset($sessionUsers["users_employee_number"]) ? $sessionUsers["users_employee_number"] : "";
		$userFirstName = isset($sessionUsers["users_first_name"]) ? $sessionUsers["users_first_name"] : "";
		$userLastName = isset($sessionUsers["users_last_name"]) ? $sessionUsers["users_last_name"] : "";
		$userFullName = isset($sessionUsers["users_full_name"]) ? $sessionUsers["users_full_name"] : "";
		$usersDepartment = isset($sessionUsers["users_department"]) ? $sessionUsers["users_department"] : "";
		$usersDivision = isset($sessionUsers["users_division"]) ? $sessionUsers["users_division"] : "";
		
		//echo "<pre>";
		//print_r($session);
		//echo "</pre>";
		//exit;
		define("ID", $userId);
		define("EMPLOYEE_NUMBER", $userEmployeeNumber);
		define("FIRST_NAME", $userFirstName);
		define("LAST_NAME", $userLastName);
		define("FULL_NAME", $userFullName);
		define("DEPARTMENT", $usersDepartment);
		define("DIVISION", $usersDivision);
		/*define("PRIVILEGES", $privileges);
		define("PRIVILEGES_DESC", $privilegesDesc);*/
	}
	
	public function left_side_menu($systemName, $active){
		$response = array();
		$ci2 = get_instance();
		$libraryName = $systemName."_left_menu";
		$ci2->load->library($libraryName);
		$response = $ci2->{$libraryName}->menu($active);
		
		return $response;
	}
	
	public function users_systems(){
		$response = array();
		$session = isset($_SESSION[PROJECT]) ? $_SESSION[PROJECT] : array();
		$sessionUsers = isset($session["users"]) ? $session["users"] : array();
		$sessionUsersSystems = isset($sessionUsers["systems_list"]) ? $sessionUsers["systems_list"] : "";
		foreach($sessionUsersSystems as $key => $value){
			$response[] = $value["systems_owners_id"];
		}
		
		return $response;
		//echo "<pre>";
		//print_r($sessionUsersSystems);
		//echo "</pre>";
		//exit;
	}
	
	public function request_query($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlFields = $this->sql_fields("requests", "request_raw");
		if($fields != ""){
			$sqlFields = $fields;
		}
		
		$sqlTables = $this->sql_tables("requests", "request_main");
		
			/*Start for Immediate Superior*/
			$immediateSuperiorFields = $this->sql_fields("requests", "requestors_immediate_superior");
			$immediateSuperiorTables = $this->sql_tables("requests", "request_status_history_main");
			$immediateSuperiorCondition = "ss.status_order = '2' 
											and pri.privileges_id = '5' 
											and rsh.status = 'active'";
			$immediateSuperiorQuery = $this->sql_query($immediateSuperiorFields, $immediateSuperiorTables, $immediateSuperiorCondition, "rsh.requests_id");
			/*End for Immediate Superior*/
		$sqlTables .= " join (".$immediateSuperiorQuery.") immediate_superior on immediate_superior.requests_id = r.requests_id";
			
			/*start for Requested Documents*/
			$requestedDocumentsFields = $this->sql_fields("requests", "requests_requested_documents_fields");
			$requestedDocumentsTables = $this->sql_tables("requests", "request_requests_documents_main");
			$requestedDocumentsCondition = "rd.status = 'active'";
			$requestedDocumentsGroup = "r.requests_id";
			$requestedDocumentsQuery = $this->sql_query($requestedDocumentsFields, $requestedDocumentsTables, $requestedDocumentsCondition, $requestedDocumentsGroup);
			/*end for Requested Documents*/
		$sqlTables .= " join (".$requestedDocumentsQuery.") requested_documents on requested_documents.requests_id = r.requests_id";
			
			/*start for status details*/
			$requestStatusHistoryFieldsList = $this->sql_fields("requests", "request_history_display");
			$requestStatusFields = $requestStatusHistoryFieldsList["table_fields"];
			$requestStatusGroup = "rsh.requests_id";
			$requestStatusQuery = $this->requeste_status_query($requestStatusFields, "", $requestStatusGroup);
			/*end for status details*/
		$sqlTables .= " join (".$requestStatusQuery.") requests_status on requests_status.requests_id = r.requests_id";
			
			/*start for additional approver*/
			$additionalApproverFields = $this->sql_fields("requests", "additional_approvers");
			$additionalApproverCondition = "ss.status_order = '5'";
			$additionalApproverGroup = "rsh.requests_id";
			$additionalApproverQuery = $this->requeste_status_query($additionalApproverFields, $additionalApproverCondition, $additionalApproverGroup);
			/*end for additional approver*/
		$sqlTables .= " left join (".$additionalApproverQuery.") additional_approvers on additional_approvers.requests_id = r.requests_id";
		
			/*start for Next User*/
			/*$nextStatusFields = $this->sql_fields("requests", "request_next_action");
			$nextStatusCondition = "ss.status_order > 1 
									and rsh.status = 'active' 
									and rsh.date_transaction is null";
			$nextStatusQuery = $this->requeste_status_query($nextStatusFields, $nextStatusCondition, "rsh.requests_id");*/
			//echo $nextStatusQuery;
			/*end for Next User*/
		//$sqlTables .= " left join (".$nextStatusQuery.") next_status on next_status.requests_id = r.requests_id";
		
		$sqlCondition = "r.status = 'active'";
		
		if($condition != ""){
			$sqlCondition .= " and ".$condition;
		}
		$sqlQuery = $this->sql_query($sqlFields, $sqlTables, $sqlCondition, $group, $order, $limit);
		//echo $sqlQuery."<br><br>";
		//exit;
		$response = $sqlQuery;
		
		return $response;
	}
	
	public function requeste_status_query($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$response = "";
		$sqlFields = $this->sql_fields("requests", "request_status_history_raw");;
		if($fields != ""){
			$sqlFields = $fields;
		}
		
		$sqlTables = $this->sql_tables("requests", "request_status_history_main");
		
		$sqlCondition = "rsh.status = 'active'";
		
		if($condition != ""){
			$sqlCondition .= " and ".$condition;
		}
		$sqlQuery = $this->sql_query($sqlFields, $sqlTables, $sqlCondition, $group, $order, $limit);
		//echo $sqlQuery;
		$response = $sqlQuery;
		
		return $response;
	}
	
	public function requested_document($file){
		$alert = "";
		$message = "";
		$response = array();
		$documentList = array();
		$ci = get_instance();
		$ci->load->library("excel");
		
		$uploadPath = "./assets/uploads/E-RCD/documents";
		$fileName = $file["name"];
		$fileTempName = $file["tmp_name"];
		$uploadFileName = $fileName;
		$uploadPathFullName = $uploadPath."/".$uploadFileName;
		if(!move_uploaded_file($fileTempName, $uploadPathFullName)){
			$alert = "danger";
			$message = "Error in uploading Requested Document file";
		}
		
		if($alert == ""){
			$inputFileType = PHPExcel_IOFactory::identify($uploadPathFullName);
			$phpExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
			$phpExcelReader->setReadDataOnly(true);
			$objPHPExcel = $phpExcelReader->load($uploadPathFullName);
			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			$highestRow = $objWorksheet->getHighestRow();
			for ($row = 1; $row <= $highestRow; $row++){ 
				$documentCodeRow = ($row + 1);
				$cell = "A".$documentCodeRow;
				$value = $objWorksheet->getCell($cell)->getValue();
				if($value != ""){
					$documentList[] = $value;
				}
			}
		}
		
		if(empty($documentList)){
			$alert = "danger";
			$message = "No Document Code Attached";
		}
		
		if(file_exists($uploadPathFullName)){
			unlink($uploadPathFullName);
		}
		
		$response = array(
			"alert" => $alert, 
			"message" => $message, 
			"document_list" => $documentList
		);
		
		return $response;
	}
	
	public function rcd_request_condition($usersId, $privileges){
		$response = "";
		$approversCondition = "";
		if(!in_array("1", $privileges) && !in_array("4", $privileges)){
			if(in_array("5", $privileges)){
				$approversCondition = "(ss.status_order = '1' and (requests_status.date_approved is null and immediate_superior.superior_id = '".$usersId."'))";
			}
			
			if(in_array("6", $privileges)){
				$managersCondition = "(ss.status_order in ('2', '4', '5') and (requests_status.date_approved is not null))";
				$approversCondition .= ($approversCondition != "" ? " or " : "").$managersCondition;
			}
		}
		
		$response = $approversCondition;
		
		return $response;
	}
	
	public function da_condition($usersId, $privileges){
		$response = "";
		$condition = "";
		if(in_array("5", $privileges)){ //for immediate supervisor
			$condition = "(case_status.id_approve_by_immediate_superior = '".$usersId."' 
							and (status_table.status_order = '4' or (status_table.status_order = '7' and offenses_table.final_suspension_days > 0)))";
		}
		
		if(in_array("4", $privileges)){ //for HRD-PIC
			$picCondition = "(status_table.status_order = '5')";
			$condition .= ($condition != "" ? " or " : "").$picCondition;
		}
		
		if(in_array("6", $privileges)){ //for HRD-Manager
			$picCondition = "(status_table.status_order in ('6', '9'))";
			$condition .= ($condition != "" ? " or " : "").$picCondition;
		}
		$response = $condition;
		
		return $response;
	}
	
	public function status_condition($systemOwners, $privileges){
		$response = "";
		$statusCondition = "so.systems_owners_id = '".$systemOwners."'";
		
	}
	
	public function months($start, $numberOfMonths, $format){
		$response = array();

		for($x = 0; $x < $numberOfMonths; $x++){
			$selectedMonth = $this->ci->functions->format_date_2($start, "Y-m-d", $format);
			if($x > 0){
				$addMonth = "+".$x." month";
				$selectedMonth = $this->ci->functions->format_date_3($start, $addMonth, $format );
			}
			
			$response[] = $selectedMonth;
		}
		
		return $response;
	}
}