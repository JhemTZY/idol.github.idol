<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ps_query {
	public $ci;
	
	public function __construct(){
		$this->ci = get_instance();
		$this->ci->load->library("generate");
	}
	
	public function plans_raw($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlFields = "*";
		if($fields != ""){
			$sqlFields = $fields;
		}
		
		$sqlTables = "tbl_plans pl";
		
		$sqlCondition = "pl.status = 'active'";
		if($condition != ""){
			$sqlCondition .= " and ".$condition;
		}
		
		$sqlQuery = $this->ci->generate->sql_query($sqlFields, $sqlTables, $sqlCondition, $group, $order, $limit);
		
		return $sqlQuery;
	}
	
	public function plans($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlFields = $this->ci->generate->sql_fields("ps", "plan_raw");
		if($fields != ""){
			$sqlFields = $fields;
		}
		
		$sqlTables = "tbl_plans pl";
		
			/*start of model details*/
				$ci2 = get_instance();
				$ci2->load->library("maintenance_query");
				$modelsQuery = $ci2->maintenance_query->models_details();
			/*end of model details*/
		$sqlTables .= " join (".$modelsQuery.") models_details ON models_details.models_details_id = pl.models_details_id";
		
		$sqlCondition = "pl.status = 'active'";
		if($condition != ""){
			$sqlCondition .= " and ".$condition;
		}
		
		$sqlQuery = $this->ci->generate->sql_query($sqlFields, $sqlTables, $sqlCondition, $group, $order, $limit);
		
		return $sqlQuery;
	}
	
	public function plans_revisions($fields = null, $condition = null, $group = null, $order = null, $limit = null){
		$sqlFields = $this->ci->generate->sql_fields("ps", "plan_revisions_raw");
		if($fields != ""){
			$sqlFields = $fields;
		}
		
		$sqlTables = "tbl_plans_revisions pr
						JOIN
					tbl_plans pl ON pl.plans_id = pr.plans_id
						AND pl.status = 'active'";
		
			
		
		$sqlCondition = "pr.status = 'active'";
		if($condition != ""){
			$sqlCondition .= " and ".$condition;
		}
		
		$sqlQuery = $this->ci->generate->sql_query($sqlFields, $sqlTables, $sqlCondition, $group, $order, $limit);
		
		return $sqlQuery;
	}
}