<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActionsExtender{
	public $select;
	public $select_mulms;
	public $insert;
	public $update;
	public $erase;
	public $functions;
	public $flash_messages,
			$ip_address, 
			$validate;
	
	public function __construct() 
	{
		$this->flash_messages  = new \Plasticbrain\FlashMessages\FlashMessages();
		$this->select = new SelectModel();
		$this->select_mulms = new MULMSSelectModel();
		$this->insert = new InsertModel();
		$this->update = new UpdateModel();
		$this->erase = new DeleteModel();
		$this->functions = new Functions();
		$this->ip_address = gethostby                                                            addr($_SERVER["REMOTE_ADDR"]);
		$this->validate = new Validators();
	}
	
	public function upload_SPC_data($iqaUploadedFileId, $excelFile){
		$error = array();
		$response = array();
		$highestRow = 0;
		$colNumber = 0;
		$uploadedFileCondition = "uploaded_file = '".$excelFile."' 
									and iqa_uploaded_file_id = '".$iqaUploadedFileId."' 
									and status = 'active'";
		$uploadedFile = $this->select->get_details("", "tbl_iqa_uploaded_file", $uploadedFileCondition, "", "", "", "new_server");
		if(isset($uploadedFile["error"])){
			$uploadedFileError = "Error : ".$uploadedFile["error"]."<br>";
			$uploadedFileError .= "SQL Query: ".$uploadedFile["sql_query"];
			$error[] = $uploadedFileError;
		}
		else{
			$iqaUploadedFileId = isset($uploadedFile["iqa_uploaded_file_id"]) ? $uploadedFile["iqa_uploaded_file_id"] : "";
			if($iqaUploadedFileId != ""){
				$excelColumnsCondition = "iqa_uploaded_file_id = '".$iqaUploadedFileId."'";
				$excelColumnsList = $this->select->get_list("", "tbl_excel_columns", $excelColumnsCondition, "", "", "", "new_server");
				if(isset($excelColumnsList["error"])){
					$excelColumnsError = "Error : ".$excelColumnsList["error"]."<br>";
					$excelColumnsError .= "SQL Query: ".$excelColumnsList["sql_query"];
					$error[] = $uploadedFileError;
				}
				else{
					ini_set('max_execution_time', 3000);
					$inputFileType = PHPExcel_IOFactory::identify($excelFile);
					$phpExcelReader = PHPExcel_IOFactory::createReader($inputFileType);
					$phpExcelReader->setReadDataOnly(true);
					$objPHPExcel = $phpExcelReader->load($excelFile);
					$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
					PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$highestColumn = $objWorksheet->getHighestColumn();
					$colNumber = PHPExcel_Cell::columnIndexFromString($highestColumn);
					$highestRow = $objWorksheet->getHighestRow();
					if(count($excelColumnsList) == 0){
						for($col = 0; $col <= $colNumber; $col++){
							$columnsError = "";
							$columnsData = array(
								"columns_id" => $col, 
								"columns_desc" => PHPExcel_Cell::stringFromColumnIndex($col), 
								"iqa_uploaded_file_id" => $iqaUploadedFileId
							);
							$insertExcelColumns = $this->insert->insert_excel_columns($columnsData);
							if(!is_numeric($insertExcelColumns)){
								$columnsError = "error in inserting excel columns".$insertExcelColumns."<br>";
								$columnsError .= "Columns".$col."<br>";
								$error[] = $columnsError;
								//exit;
							}
						}
						//exit;
						if(count($error) == 0){
							$columnsList = $this->select->get_list("", "tbl_excel_columns", $excelColumnsCondition, "", "", "", "new_server");
							$chunkColumns = array_chunk($columnsList, 10);
							foreach($chunkColumns as $chunkRows){
								$rowsError = "";
								$excelColumnsRowValueData = array();
								foreach($chunkRows as $cRows){
									for($row = 1; $row <= $highestRow; $row++){
										try {
											$rowsValue = $objWorksheet->getCellByColumnAndRow($cRows["columns_id"], $row)->getCalculatedValue();
										} catch (Exception $e) {
											$rowsValue = $objWorksheet->getCellByColumnAndRow($cRows["columns_id"], $row)->getOldCalculatedValue();
										}
										$value = str_replace("'", "", $rowsValue);
										$excelColumnsRowValueData[] = array(
											"excel_columns_id" => $cRows["excel_columns_id"], 
											"rows_id" => $row, 
											"rows_value" => $value
										);
									}
								}
								//$this->functions->echo_array($excelColumnsRowValueData);
								$insertExcelColumnsRowValue = $this->insert->insert_excel_columns_row_value($excelColumnsRowValueData);
								if(!is_numeric($insertExcelColumnsRowValue)){
									$rowsError = "error in inserting excel columns rows value".$insertExcelColumnsRowValue."<br>";
									$rowsError .= "Columns ".$cRows["columns_id"]."<br>";
									$rowsError .= "Rows ".$row."<br>";
									$error[] = $rowsError;
									//exit;
								}
							}
						}
					}
					/*else{
						$errorMessage = "Error: Have existing data.<br>";
						$errorMessage .= "Excel File: ".$excelFile."<br>";
						$errorMessage .= "IQA Uploaded File ID: ".$iqaUploadedFileId."<br>";
						$error[] = $errorMessage;
					}*/
				}
			}
			else{
				$error[] = "Error No IQA Uploaded ID";
			}
		}
		
		if(count($error) > 0){
			$response = array(
				"error" => implode("<bR>", $error)
			);
		}
		else{
			$response = array(
				"highest_row" => $highestRow, 
				"highest_col" => $colNumber
			);
		}
		return $response;
	}
	
	public function generate_lot_data($lot){
		$response = array();
		$validateLot = $this->validate->lot($lot);
		$dateTime = date("Y-m-d H:i:s");
		if(isset($validateLot["error"])){
			$response["error"] = $validateLot["error"];
		}
		else{
			if($validateLot["id"] == 0){
				$lotData = array(
					"lot_desc" => $lot, 
					"date_added" => $dateTime, 
					"added_by" => EMPLOYEE_NUMBER
				);
				$insertLot = $this->insert->insert_lot_data($lotData);
				if(!is_numeric($insertLot)){
					$lotError = "Error in inserting new Lot: ".$insertLot."<br>";
					$response["error"] = $lotError;
				}
				else{
					$response["id"] = $insertLot;
				}
			}
			else{
				$response["id"] = $validateLot["id"];
			}
		}
		
		return $response;
	}
	
	public function generate_iqa_records_data($lotId, $iqaUploadedFileId, $lotStatus, $invoiceNumber, $quantity){
		$response = array();
		$dateTime = date("Y-m-d H:i:s");
		$validateIqaRecords = $this->validate->iqa_records($lotId, $iqaUploadedFileId, $invoiceNumber, $quantity);
		if(isset($validateIqaRecords["error"])){
			$response["error"] = $validateIqaRecords["error"];
		}
		else{
			if($validateIqaRecords["id"] == 0){
				$iqaRecordsData = array(
					"lot_data_id" => $lotId, 
					"iqa_uploaded_file_id" => $iqaUploadedFileId, 
					"invoice_number" => $invoiceNumber, 
					"quantity" => $quantity, 
					"lot_status" => $lotStatus, 
					"date_added" => $dateTime, 
					"added_by" => EMPLOYEE_NUMBER
				);
				$insertIqaRecords = $this->insert->insert_iqa_records($iqaRecordsData);
				if(!is_numeric($insertIqaRecords)){
					$response["error"] = "Error in inserting new IQA Records: ".$insertIqaRecords."<br>";
				}
				else{
					$response["id"] = $insertIqaRecords;
				}
			}
			else{
				$response["id"] = $validateIqaRecords["id"];
			}
		}
		
		return $response;
	}
	
	public function generate_iqa_records_cavity_data($iqaRecordRrId, $cavityNumber){
		$response = array();
		$dateTime = date("Y-m-d H:i:s");
		$validateCavityNumber = $this->validate->iqa_cavity_number($iqaRecordRrId, $cavityNumber);
		if(isset($validateCavityNumber["error"])){
			$response["error"] = $validateCavityNumber["error"];
		}
		else{
			if($validateCavityNumber["id"] == 0){
				$iqaRecordsCavityData = array(
					"iqa_records_rr_id" => $iqaRecordRrId, 
					"cavity_number" => $cavityNumber, 
					"date_added" => $dateTime, 
					"added_by" => EMPLOYEE_NUMBER
				);
				$insertIqaRecordsCavity = $this->insert->insert_iqa_records_cavity($iqaRecordsCavityData);
				if(!is_numeric($insertIqaRecordsCavity)){
					$iqaRecordsCavityError = "Error in inserting new IQA Records Cavity: ".$insertIqaRecordsCavity."<br>";
					$response["error"] = $iqaRecordsCavityError;
				}
				else{
					$response["id"] = $insertIqaRecordsCavity;
				}
			}
			else{
				$response["id"] = $validateCavityNumber["id"];
			}
		}
		
		return $response;
	}
	
	public function generate_iqa_records_rr_data($iqaRecordsId, $rrNumber){
		$response = array();
		$dateTime = date("Y-m-d H:i:s");
		$validateRrNumber = $this->validate->iqa_rr_number($iqaRecordsId, $rrNumber);
		if(isset($validateRrNumber["error"])){
			$response["error"] = $validateRrNumber["error"];
		}
		else{
			if($validateRrNumber["id"] == 0){
				$iqaRecordsRrData = array(
					"iqa_records_id" => $iqaRecordsId, 
					"rr_number" => $rrNumber, 
					"date_added" => $dateTime, 
					"added_by" => EMPLOYEE_NUMBER
				);
				$insertRecordsRr = $this->insert->insert_iqa_records_rr($iqaRecordsRrData);
				if(!is_numeric($insertRecordsRr)){
					$iqaRecordsRrError = "Error in inserting new IQA Records Cavity: ".$insertIqaRecordsCavity."<br>";
					$response["error"] = $iqaRecordsRrError;
				}
				else{
					$response["id"] = $insertRecordsRr;
				}
			}
			else{
				$response["id"] = $validateRrNumber["id"];
			}
		}
		return $response;
	}
	
	public function generate_models_parameter_height_data($parameters){
		$response = array();
		$dateTime = date("Y-m-d H:i:s");
		$modelSupplierId = $parameters["models_suppliers_id"];
		$parameterName = $parameters["parameter_name"];
		$parameterHeightNumber = $parameters["height_number"];
		$parameterHeight = $parameters["parameters_height"];
		$usl = $parameters["usl"];
		$lsl = $parameters["lsl"];
		
		$parameterId = "";
		$validateParameter = $this->validate->parameters($parameterName);
		if(isset($validateParameter["error"])){
			$response["error"] = $validateParameter["error"];
		}
		else{
			if($validateParameter["id"] == 0){
				$parameterData = array(
					"parameters_desc" => $parameterName, 
					"date_added" => $dateTime, 
					"added_by" => EMPLOYEE_NUMBER
				);
				$insertParameters = $this->insert->insert_parameters($parameterData);
				if(!is_numeric($insertParameters)){
					$response["error"] = "Error in inserting new parameter : ".$insertParameters."<br>";
				}
				else{
					$parameterId = $insertParameters;
				}
			}
			else{
				$parameterId = $validateParameter["id"];
			}
		}
		
		$modelParameterId = "";
		if($parameterId != ""){
			$validateModelsParameter = $this->validate->models_parameter($modelSupplierId, $parameterId);
			if(isset($validateModelsParameter["error"])){
				$response["error"] = $validateModelsParameter["error"];
			}
			else{
				if($validateModelsParameter["id"] == 0){
					$modelsParameterData = array(
						"models_parts_supplier_id" => $modelSupplierId, 
						"parameters_id" => $parameterId, 
						"points" => "", 
						"date_added" => $dateTime, 
						"added_by" => EMPLOYEE_NUMBER
					);
					$insertModelParameter = $this->insert->insert_models_parameter($modelsParameterData);
					if(!is_numeric($insertModelParameter)){
						$response["error"] = "Error in inserting models parameters. ".$insertModelParameter."<br>";
					}
					else{
						$modelParameterId = $insertModelParameter;
					}
				}
				else{
					$modelParameterId = $validateModelsParameter["id"];
				}
			}
		}
		
		$modelsParameterHeightId = "";
		if($modelParameterId != ""){
			$validateModelsParameterHeight = $this->validate->models_parameter_height($modelParameterId, $parameterHeight);
			if(isset($validateModelsParameterHeight["error"])){
				$response["error"] = $validateModelsParameterHeight["error"]."<br>";
			}
			else{
				if($validateModelsParameterHeight["id"] == 0){
					$modelsParameterHeightData = array(
						"models_parameter_id" => $modelParameterId, 
						"models_parameter_height_code" => $parameterHeightNumber, 
						"height_value" => $parameterHeight,
						"date_added" => $dateTime, 
						"added_by" => EMPLOYEE_NUMBER, 
						"status" => "active"
					);
					$insertModelsParameterHeight = $this->insert->insert_models_parameter_height($modelsParameterHeightData);
					if(!is_numeric($insertModelsParameterHeight)){
						$response["error"] = "Error in inserting models parameters height. ".$insertModelsParameterHeight."<br>";
					}
					else{
						$modelsParameterHeightId = $insertModelsParameterHeight;
					}
				}
				else{
					$modelsParameterHeightId = $validateModelsParameterHeight["id"];
				}
			}
		}
		
		if($modelsParameterHeightId != ""){
			$limitsList = array(
				"1" => $usl, 
				"2" => $lsl
			);
			$limitError = "";
			$modelsParameterLimitHistoryData = array();
			foreach($limitsList as $keys => $value){
				$validateModelsParameterLimit = $this->validate->iqa_models_parameter_limit($modelsParameterHeightId, $keys, $value);
				if(isset($validateModelsParameterLimit["error"])){
					$limitError .= $validateModelsParameterLimit["error"]."<br>";
					
				}
				else{
					$action = $validateModelsParameterLimit["action"];
					if($action != ""){
						switch($action){
							case "insert":
								$modelsParameterLimitData = array(
									"models_parameters_height_id" => $modelsParameterHeightId, 
									"limits_id" => $keys, 
									"limits_value" => $value, 
									"date_added" => $dateTime, 
									"added_by" => EMPLOYEE_NUMBER, 
									"status" => "active"
								);
								$insertModelsParameterLimit = $this->insert->insert_iqa_models_parameter_height_limit($modelsParameterLimitData);
								if(!is_numeric($insertModelsParameterLimit)){
									$response["error"] = "Error in inserting models parameters limit.".$insertModelsParameterLimit."<br>";
								}
								else{
									//$insertModelsParameterLimit
									$modelsParameterLimitHistoryData[] = array(
										"iqa_models_parameters_limit_id" => $insertModelsParameterLimit, 
										"new_value" => $value, 
										"old_value" => "", 
										"date_added" => $dateTime, 
										"added_by" => EMPLOYEE_NUMBER, 
										"status" => "active"
									);
								}
							break;
							
							case "update":
								$oldValue = $validateModelsParameterLimit["old_value"];
								$iqaModelsParameterLimitId = $validateModelsParameterLimit["iqa_models_parameter_limit_id"];
								$modelsParameterLimitData = array(
									"limits_value" => $value, 
									"date_updated" => $dateTime, 
									"added_by" => EMPLOYEE_NUMBER, 
									"status" => "active"
								);
								$modelsParameterLimitCondition = array(
									"iqa_models_parameters_limit_id" => $iqaModelsParameterLimitId
								);
								$updateModelsParameterLimit = $this->update->update_iqa_models_parameter_limit($modelsParameterLimitData, $modelsParameterLimitCondition);
								if($updateModelsParameterLimit != "Success"){
									$response["error"] = "Error in updating models parameters limit.".$updateModelsParameterLimit."<br>";
								}
								else{
									$modelsParameterLimitHistoryData[] = array(
										"iqa_models_parameters_limit_id" => $iqaModelsParameterLimitId, 
										"new_value" => $value, 
										"old_value" => $oldValue, 
										"date_added" => $dateTime, 
										"added_by" => EMPLOYEE_NUMBER, 
										"status" => "active"
									);
								}
							break;
						}
					}
				}
			}
			
			if($limitError != ""){
				$response["error"] = $limitError;
			}
			
			if(count($modelsParameterLimitHistoryData) > 0){
				$insertModelsParameterLimitHistory = $this->insert->insert_iqa_models_parameter_limit_history($modelsParameterLimitHistoryData);
				if(!is_numeric($insertModelsParameterLimitHistory)){
					$response["error"] = "Error in insert IQA models Parameter Limit History : ".$insertModelsParameterLimitHistory;
				}
			}
		}
		
		if(!isset($response["error"])){
			$response["id"] = $modelsParameterHeightId;
		}
		return $response;
	}
	
	public function generate_iqa_uploaded_file($uploadedFile, $drawingNumber, $sampleNumber){
		$response = array();
		$iqaUploadedFileCondition = "uploaded_file = '".$uploadedFile."' 
										and sample_numbers_id = '".$sampleNumber."' 
										and models_drawing_number_id = '".$drawingNumber."' 
										and status = 'active'";
		$iqaUploadedFile = $this->select->get_details("", "tbl_iqa_uploaded_file", $iqaUploadedFileCondition, "", "", "", "new_server");
		if(isset($iqaUploadedFile["error"])){
			$uploadedFileError = "Error: ".$iqaUploadedFile["error"]."<br>";
			$uploadedFileError .= "SQL Query: ".$iqaUploadedFile["sql_query"];
			$response["error"] = $uploadedFileError;
		}
		else{
			if(count($iqaUploadedFile) == 0){
				$iqaUploadedFileError = "";
				$iqaUploadedFileData = array(
					"uploaded_file" => $uploadedFile, 
					"sample_numbers_id" => $sampleNumber, 
					"models_drawing_number_id" => $drawingNumber, 
					"ip_address" => $this->ip_address, 
					"date_added" => date("Y-m-d H:i:s"), 
					"added_by" => EMPLOYEE_NUMBER
				);
				$insertIqaUploadedFile = $this->insert->insert_iqa_uploaded_file($iqaUploadedFileData);
				if(!is_numeric($insertIqaUploadedFile)){
					$iqaUploadedFileError = "Error in inserting New Uploaded File".$insertIqaUploadedFile;
					$response["error"] = $iqaUploadedFileError;
				}
				else{
					$response["id"] = $insertIqaUploadedFile;
				}
			}
			else{
				$response["id"] = $iqaUploadedFile["iqa_uploaded_file_id"];
			}
		}
		return $response;
	}
	
	public function generate_row_values($iqaUploadedFileId){
		$response = array();
		$error = "";
		$rowData = 0;
		$rowDateInspected = 0;
		$rowInspector = 0;
		$rowLotNumber = 0;
		$colLot = 0;
		$colInspector = 0;
		$colDateInspected = 0;
		$colCavityNumber = 0;
		$colRrNumber = 0;
		$colLotStatus = 0;
		$colSampleNumber = 0;
		$rowDataCondition = "(columns_id = 0 or columns_id = 1) 
								and ecrv.rows_value like concat('%', 'DATE', '%') 
								and ec.iqa_uploaded_file_id = '".$iqaUploadedFileId."'";
		$rowDataList = $this->select->get_excel_columns("", $rowDataCondition, "", "", "1");
		if(isset($rowDataList["error"])){
			$errorRowData = "Error : ".$rowDataList["error"]."<br>";
			$errorRowData = "SQL Query : ".$rowDataList["sql_query"];
			$error = $errorRowData;
		}
		else{
			if(count($rowDataList) > 0){
				$rowData = isset($rowDataList[0]["rows_id"]) ? $rowDataList[0]["rows_id"] : 0;
				$rowDateInspected = isset($rowDataList[0]["rows_id"]) ? $rowDataList[0]["rows_id"] : 0;
				$rowInspector = ($rowData + 3);
				$rowDisposition = ($rowData - 1);
				$columnsDesc = isset($rowDataList[0]["columns_desc"]) ? $rowDataList[0]["columns_desc"] : "";
				$colLot = $rowDataList[0]["columns_id"];
				$colInspector = ($colLot + 1);
				$colDateInspected = ($colLot + 1);
				$colCavityNumber = ($colDateInspected + 1);
				$colRrNumber = ($colCavityNumber + 1);
				$colLotStatus = ($colRrNumber + 1);
				$colSampleNumber = ($colLotStatus + 1);
				$rowDataLotCondition = "ecrv.rows_value REGEXP '^[0-9]+$' 
										and ec.columns_desc = '".$columnsDesc."' 
										and ec.iqa_uploaded_file_id = '".$iqaUploadedFileId."'";
				$rowDataLot = $this->select->get_excel_columns("", $rowDataLotCondition, "", "", "1");
				if(isset($rowDataLot["error"])){
					$errorRowData = "Error : ".$rowDataLot["error"]."<br>";
					$errorRowData = "SQL Query : ".$rowDataLot["sql_query"];
					$error = $errorRowData;
				}
				else{
					if(count($rowDataLot) > 0){
						$rowLotNumber = $rowDataLot[0]["rows_id"];
					}
					else{
						$error = "Error : No Row Lot Data Columns";
					}
				}
			}
			else{
				$error = "Error : No Row Data Columns";
			}
		}
		if($error == ""){
			$rowDataValueCondition = "ecrv.rows_value like concat('%', 'Remarks', '%') 
								and ec.iqa_uploaded_file_id = '".$iqaUploadedFileId."'";
			$rowDataValueList = $this->select->get_excel_columns("", $rowDataValueCondition, "", "", "1");
			
			if(isset($rowDataValueList["error"])){
				$errorRowData = "Error : ".$rowDataValueList["error"]."<br>";
				$errorRowData = "SQL Query : ".$rowDataValueList["sql_query"];
				$error = $errorRowData;
			}
			else{
				if(count($rowDataValueList) > 0){
					$rowRemarks = isset($rowDataValueList[0]["rows_id"]) ? $rowDataValueList[0]["rows_id"] : 0;
					$colRemarks = isset($rowDataValueList[0]["columns_id"]) ? $rowDataValueList[0]["columns_id"] : 0;
					$colData = ($colRemarks + 1);
					$rowHeightNumber = ($rowRemarks + 1);
					$rowParameterName = ($rowHeightNumber + 1);
					$rowParameterHeight = ($rowParameterName + 1);
					$rowUsl = ($rowParameterHeight + 1);
					$rowLsl = ($rowUsl + 1);
				}
				else{
					$error = "Error : No Row Data Value Columns";
				}
			}
			
		}
		if($error != ""){
			$response = array(
				"error" => $error
			);
		}
		else{
			$response = array(
				"row" => array(
					"lot_data" => $rowData, 
					"date_inspected" => $rowDateInspected, 
					"inspector" => $rowInspector, 
					"disposition" => $rowDisposition, 
					"lot_number" => $rowLotNumber, 
					"remarks" => $rowRemarks, 
					"height_number" => $rowHeightNumber, 
					"parameter_name" => $rowParameterName, 
					"parameter_height" => $rowParameterHeight, 
					"usl" => $rowUsl, 
					"lsl" => $rowLsl
				), 
				"col" => array(
					"lot" => $colLot, 
					"date_inspected" => $colDateInspected, 
					"inspector" => $colInspector, 
					"cavity_number" => $colCavityNumber, 
					"rr_number" => $colRrNumber, 
					"lot_status" => $colLotStatus, 
					"sample_number" => $colSampleNumber, 
					"data" => $colData
				)
			);
		}
		
		return $response;
	}
	
	public function generate_iqa_invoice_number_data($iqaRecordsId, $invoiceNumber, $quantity, $rrNumber){
		$response = array();
		$error = "";
		$iqaInvoiceNumberId = "";
		$dateTime = date("Y-m-d H:i:s");
		$invoiceNumberList = explode("/", $invoiceNumber);
		$quantityList = explode("/", $quantity);
		$invoiceNumberData = array();
		foreach($invoiceNumberList as $keys => $value){
			$quantityOfInvoice = (isset($quantityList[$keys]) ? (str_replace(",", "", $quantityList[$keys])) : 0);
			$validateInvoiceNumber = $this->validate->invoice_number($value, $quantityOfInvoice, $iqaRecordsId);
			if(!is_numeric($validateInvoiceNumber)){
				$error .= $validateInvoiceNumber."<br>";
			}
			else{
				if($validateInvoiceNumber == 1){
					$invoiceNumberData[] = array(
						"iqa_records_id" => $iqaRecordsId, 
						"invoice_number_desc" => $value, 
						"quantity" => $quantityOfInvoice, 
						"date_added" => $dateTime, 
						"added_by" => EMPLOYEE_NUMBER
					);
				}
			}
		}
		if(count($invoiceNumberData) > 0){
			$insertIqaInvoiceNumber = $this->insert->insert_iqa_invoice_number($invoiceNumberData);
			if(!is_numeric($insertIqaInvoiceNumber)){
				$error = "Error in inserting Iqa Invoice Number: ".$insertIqaInvoiceNumber;
			}
		}
		
		if($error == ""){
			$iqaRecordsRrId = "";
			if (strpos($rrNumber, '-') !== false) {
				$concatenatorPosition = strpos($rrNumber, "-");
				$realRR = substr($rrNumber, ($concatenatorPosition + 1));
			}
			else{
				$realRR = $this->functions->get_numeric($rrNumber);
			}
			
			$invoiceNumberCondition = "invoice_number_desc like concat('%', '".$realRR."', '%') 
										and iqa_records_id = ".$iqaRecordsId." 
										and status = 'active'";
			$invoiceNumberDetails = $this->select->get_details("", "tbl_iqa_invoice_number", $invoiceNumberCondition, "", "", "", "new_server");
			if(isset($invoiceNumberDetails["error"])){
				$error .= "Error : ".$invoiceNumberDetails["error"]."<br>";
				$error .= "SQL Query: ".$invoiceNumberDetails["sql_query"]."<br>";
			}
			else{
				if(count($invoiceNumberDetails) > 0){
					$iqaInvoiceNumberId = $invoiceNumberDetails["iqa_invoice_number_id"];
				}
				else{
					$iqaInvoiceNumberId = 1;
				}
			}
		}
		
		if($error != ""){
			$response["error"] = $error;
		}
		
		if($iqaInvoiceNumberId != ""){
			$response["id"] = $iqaInvoiceNumberId;
		}
		//$response = ($error != "" ? $error : $iqaInvoiceNumberId);
		
		return $response;
	}
	
}
