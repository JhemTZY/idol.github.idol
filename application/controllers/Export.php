<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Export extends CI_Controller {
    // construct
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->model('Export_model', 'export');
    }    
 
    public function index() {
        $data['export_list'] = $this->export->exportList();
        $this->load->view('export', $data);
    }

     
}

    // create xlsx
  