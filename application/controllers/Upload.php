<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller{
	
	function __construct(){
		parent::__construct();
	
	}
    
	function index(){

		$this->load->view('uploader');
    }

    function uploads(){

		$this->load->view('uploader');
    }
}