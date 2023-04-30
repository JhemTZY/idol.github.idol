<?php defined('BASEPATH') or exit('No direct script access allowed');

class BaseController extends CI_Controller
{
	protected $role = '';
	protected $vendorId = '';
	protected $name = '';
	protected $email = '';
	protected $roleText = '';
	protected $isAdmin = 0;
	protected $isUser = 0;
	protected $accessInfo = [];
	protected $global = array();
	protected $lastLogin = '';
	protected $module = '';

	/**
	 * This is default constructor
	 */
	public function __construct()
	{
		parent::__construct();
		 header('Access-Control-Allow-Origin: *');
		// $this->ncfldb = $this->load->database('masterdb',TRUE);	

	}

	/**
	 * This function used to check the user is logged in or not
	 */
	function isLoggedIn()
	{
		$isLoggedIn = $this->session->userdata('isLoggedIn');

		if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
			redirect('index.php/login');
		} else {
			$this->role = $this->session->userdata('role');
			$this->vendorId = $this->session->userdata('userId');
			$this->name = $this->session->userdata('name');
			$this->email = $this->db->query("SELECT * FROM tbl_users where name = '$this->name' ")->row();
			$this->customer_handle = $this->db->query("SELECT * FROM cias.tbl_roles INNER JOIN cias.tbl_users ON tbl_roles.roleId=tbl_users.roleId INNER JOIN cias.tbl_customer ON tbl_users.customer_handle=tbl_customer.customers_id ")->row();
			$this->roleText = $this->session->userdata('roleText');
			$this->lastLogin = $this->session->userdata('lastLogin');
			$this->isAdmin = $this->session->userdata('isAdmin');
			$this->isUser = $this->session->userdata('isUser');
			$this->accessInfo = $this->session->userdata('accessInfo');

			$this->global['name'] = $this->name;
			$this->global['email'] = $this->email->email;
			$this->global['customer_handle'] = $this->customer_handle->customers_code;
			$this->global['userId'] = $this->vendorId;
			$this->global['role'] = $this->role;
			$this->global['role_text'] = $this->roleText;
			$this->global['last_login'] = $this->lastLogin;
			$this->global['is_admin'] = $this->isAdmin;
			$this->global['is_user'] = $this->isUser;
			$this->global['access_info'] = $this->accessInfo;
		}
	}

	/**
	 * This function is used to check the access
	 */


	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */


	function isAdmin()
	{
		if ($this->isAdmin == SYSTEM_ADMIN) {
			return true;
		} else {
			return false;
		}
	}

	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */

	// 

	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */

	function isUser()
	{
		if ($this->isAdmin == REGULAR_USER || SYSTEM_ADMIN) {
			return true;
		} else {
			return false;
		}
	}

	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */



	/**
	 * This function is used to check the user having list access or not
	 */


	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */
	protected function hasListAccess()
	{
		if (
			$this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['list'] == 1
					|| $this->accessInfo[$this->module]['total_access'] == 1))
		) {
			return true;
		}
		return false;
	}

	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */



	/**
	 * This function is used to check the user having list access or not
	 */


	protected function hasUserListAccess()
	{
		if (
			$this->isUser() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['list'] == 2
					|| $this->accessInfo[$this->module]['total_access'] == 2))
		) {
			return true;
		}
		return false;
	}


	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */




	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */


	/**
	 * This function is used to check the user having create access or not
	 */
	protected function hasCreateAccess()
	{
		if (
			$this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['create_records'] == 1
					|| $this->accessInfo[$this->module]['total_access'] == 1))
		) {
			return true;
		}
		return false;
	}



	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */




	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */

	/**
	 * This function is used to check the user having create access or not
	 */
	protected function hasUserCreateAccess()
	{
		if (
			$this->isUser() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['create_records'] == 1
					|| $this->accessInfo[$this->module]['total_access'] == 1))
		) {
			return true;
		}
		return false;
	}

	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */



	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */
	/**
	 * This function is used to check the user having update access or not
	 */
	protected function hasUpdateAccess()
	{
		if (
			$this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['edit_records'] == 1
					|| $this->accessInfo[$this->module]['total_access'] == 1))
		) {
			return true;
		}
		return false;
	}
	/*----------------------------------------------------------------------------------------
       isAdmin ***
    ---------------------------------------------------------------------------------------- */

	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */
	/**
	 * This function is used to check the user having update access or not
	 */

	protected function hasUsersUpdateAccess()
	{
		if (
			$this->isUser() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['edit_records'] == 1
					|| $this->accessInfo[$this->module]['total_access'] == 1))
		) {
			return true;
		}
		return false;
	}
	/*----------------------------------------------------------------------------------------
       isUser ***
    ---------------------------------------------------------------------------------------- */



	/**
	 * This function is used to check the user having delete access or not
	 */
	protected function hasDeleteAccess()
	{
		if (
			$this->isAdmin() ||
			(array_key_exists($this->module, $this->accessInfo)
				&& ($this->accessInfo[$this->module]['delete_records'] == 1
					|| $this->accessInfo[$this->module]['total_access'] == 1))
		) {
			return true;
		}
		return false;
	}

	/**
	 * This function is used to load the set of views
	 */
	function loadThis()
	{
		$this->global['pageTitle'] = 'CodeInsect : Access Denied';

		$this->load->view('includes/header', $this->global);
		$this->load->view('access');
		$this->load->view('includes/footer');
	}

	/**
	 * This function is used to logged out user from system
	 */
	function logout()
	{
		$this->session->sess_destroy();
		redirect('index.php/login');
	}

	/**
	 * This function used to load views
	 * @param {string} $viewName : This is view name
	 * @param {mixed} $headerInfo : This is array of header information
	 * @param {mixed} $pageInfo : This is array of page information
	 * @param {mixed} $footerInfo : This is array of footer information
	 * @return {null} $result : null
	 */
	function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL)
	{
		// pre($this->global); die;
		$this->load->view('includes/header', $headerInfo);
		$this->load->view($viewName, $pageInfo);
		$this->load->view('includes/footer', $footerInfo);
	}

	/**
	 * This function used provide the pagination resources
	 * @param {string} $link : This is page link
	 * @param {number} $count : This is page count
	 * @param {number} $perPage : This is records per page limit
	 * @return {mixed} $result : This is array of records and pagination data
	 */
	function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT)
	{
		$this->load->library('pagination');

		$config['base_url'] = base_url() . $link;
		$config['total_rows'] = $count;
		$config['uri_segment'] = $segment;
		$config['per_page'] = $perPage;
		$config['num_links'] = 5;
		$config['full_tag_open'] = '<nav><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';
		$config['first_tag_open'] = '<li class="arrow">';
		$config['first_link'] = 'First';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="arrow">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="arrow">';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="arrow">';
		$config['last_link'] = 'Last';
		$config['last_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$page = $config['per_page'];
		$segment = $this->uri->segment($segment);

		return array(
			"page" => $page,
			"segment" => $segment
		);
	}

	function object_to_array($data)
	{
		$data = var_dump((array) $data);
		// if (is_array($data) || is_object($data)) {
		// 	$result = [];
		// 	foreach ($data as $key => $value) {
		// 		$result[$key] = (is_array($value) || is_object($value)) ;
		// 	}
		// 	return $result;
		// }
		return $data;
	}


	function movetobottom(&$array, $key)
	{
		$value = $array[$key];
		unset($array[$key]);
		$array[$key] = $value;
	}
}
