<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ps_fields {
	public $ci;
	
	public function __construct(){
		$this->ci = get_instance();
		$this->ci->load->library("models_fields");
	}
	
	public function plan_raw(){
		$response = "pl.plans_id, 
					pl.revision_number,
					pl.number_of_months, 
					pl.date_created, 
					pl.start_month, 
					month(pl.date_created) as month_created, 
					year(pl.date_created) as year_created";
		$modelsFields = $this->ci->models_fields->plan_models();
		
		$response .= ", ".$modelsFields;
		
		return $response;
	}
	
	public function plan_revisions_raw(){
		$response = "pr.plans_revisions_id,
					pl.plans_id,
					pl.start_month,
					pl.date_created,
					pr.number_of_months,
					pr.revisions_number,
					pr.revisions_status, 
					month(pl.date_created) as month_created, 
					year(pl.date_created) as year_created";
		$modelsFields = $this->ci->models_fields->plan_models();
		
		$response .= ", ".$modelsFields;
		
		return $response;
	}
}