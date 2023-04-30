<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
    class Export_model extends CI_Model {
 
        public function __construct()
        {
            $this->load->database();
        }
        
        public function exportList() {
            $this->db->select(array('auto_id', 'customer', 'model', 'month_year', 'revision'));
            $this->db->from('cias.tbl_new_plan');
            $query = $this->db->get();
            return $query->result();
        }
    }
?>