<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Models_fields{
	public function models_details_raw(){
		$response = "md.models_details_id,
					md.models_materials_code,
					md.models_drawing_number,
					md.models_status,
					cm.customers_models_id,
					mo.models_id,
					mo.models_code,
					mo.models_customer_code,
					mo.models_desc,
					mo.models_name,
					cbu.customers_business_units_id, 
					cus.customers_id, 
					cus.customers_desc, 
					cus.customers_code, 
					bu.business_units_id, 
					bu.business_units_code,
					bu.business_units_desc";
		return $response;
	}
	
	public function plan_models(){
		$response = "models_details.models_details_id,
					models_details.models_materials_code,
					models_details.models_drawing_number,
					models_details.models_status,
					models_details.customers_models_id,
					models_details.models_id,
					models_details.models_code,
					models_details.models_customer_code,
					models_details.models_desc,
					models_details.models_name,
					models_details.customers_business_units_id, 
					models_details.customers_id, 
					models_details.customers_desc, 
					models_details.customers_code, 
					models_details.business_units_id, 
					models_details.business_units_code,
					models_details.business_units_desc";
		return $response;
	}
}