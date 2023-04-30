<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Functions
{
	public function format_date($date, $format)
	{
		$replacedDate = ((strpos($date, '/') !== false) ? $date : str_replace('/', '-', $date));
		$strToTimeDate = strtotime($replacedDate);
		$newDate = date($format, $strToTimeDate);
		return $newDate;
	}

	public function format_date_2($date, $old_format, $new_format)
	{
		//echo $date;
		$date2 = DateTime::createFromFormat($old_format, $date);
		return $date2->format($new_format);
	}

	public function format_date_3($date, $modify, $format_result)
	{
		$dateCreated = new DateTime($date);
		$dateCreated->modify($modify);
		return $dateCreated->format($format_result);
	}


	public function echo_array($array)
	{
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}

	public function clean($string)
	{
		//$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
	}

	public function array_to_in($list)
	{
		$response = "";
		if (is_array($list)) {
			$response = implode("', '", $list);
		} else {
			$response = $list;
		}

		return $response;
	}

	public function go_to_dashboard()
	{
		$session = isset($_SESSION[PROJECT]) ? $_SESSION[PROJECT] : array();
		$sessionUsers = isset($session["users"]) ? $session["users"] : array();
		$usersSystemList = isset($sessionUsers["systems_list"]) ? $sessionUsers["systems_list"] : "";
		$this->echo_array($usersSystemList);

		$firstSystem = "";
		$redirect = "";
		foreach ($usersSystemList as $keys => $value) {
			$systemsCode = $keys;
			if ($firstSystem == "") {
				$firstSystem = $systemsCode;
				$departmentsSectionsId = $value["departments_sections_id"];
				$redirect = "users/index/" . $firstSystem . "/" . $departmentsSectionsId;
			}
		}
		//echo $redirect;
		//exit;
		redirect(site_url($redirect));
	}

	public function check_if_approvers($correctApprovers, $usersPrivileges)
	{
		$response = 0;
		foreach ($usersPrivileges as $upRows) {
			if (in_array($upRows, $correctApprovers)) {
				$response = 1;
			}
		}

		return $response;
	}

	public function movetobottom(&$array, $key)
    {
        $value = $array[$key];
        unset($array[$key]);
        $array[$key] = $value;
    }

}
